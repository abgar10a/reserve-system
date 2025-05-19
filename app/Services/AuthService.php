<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\ResponseStatus;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

readonly class AuthService
{
    public function __construct(private IUserRepository $userRepository)
    {

    }

    public
    function register(array $userData): array
    {
        try {
            $user = $this->userRepository->create($userData);

            $credentials = [
                'email' => $user->email,
                'password' => $userData['password']
            ];

            return [
                ...$this->login($credentials),
                'message' => 'User created successfully',
                'status' => ResponseStatus::CREATED->code(),
            ];
        } catch (Throwable $th) {
            return ResponseAction::build(error: $th->getMessage());
        }
    }

    public
    function login(array $credentials): array
    {
        try {
            if ($user = $this->attempt($credentials)) {

                $token = $this->createToken($credentials);

                return ResponseAction::build('User logged in successfully', [
                    'token' => $token,
                    'user' => $user,
                ], ResponseStatus::OK->code());
            }

            return ResponseAction::build(status: ResponseStatus::UNAUTHORIZED->code(), error: 'Invalid credentials');
        } catch (Throwable $th) {
            return ResponseAction::build(status: ResponseStatus::INTERNAL_ERROR->code(), error: $th->getMessage());
        }
    }

    public
    function attempt(array $credentials): ?User
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    public
    function logout(): array
    {
        try {
            $user = auth()->user();

            if ($user->token()) {
                $user->token()->revoke();

                return ResponseAction::build('User logged out successfully', status: ResponseStatus::OK->code());
            }

            return ResponseAction::build(status: ResponseStatus::UNAUTHORIZED->code(), error: 'Not authenticated');
        } catch (Throwable $th) {
            return ResponseAction::build(status: ResponseStatus::INTERNAL_ERROR->code(), error: $th->getMessage());
        }
    }

    public function handleOauthCallback(string $provider, string $token): array
    {
        $socialUser = Socialite::driver($provider)->userFromToken($token);

        if (empty($socialUser)) {
            return ResponseAction::build(status: ResponseStatus::UNAUTHORIZED->code(), error: 'Invalid token');
        }

        $user = $this->userRepository->findByEmail($socialUser->getEmail());
        $password = config('app.test_mode', false) ? '12345678' : Str::random(16);

        if (!$user) {
            return $this->register([
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                'password' => $password,
            ]);
        }

        $user->update([
            'password' => $password,
        ]);

        return [
            ...$this->login([
                'email' => $user->email,
                'password' => $password,
            ]),
            'message' => 'User created successfully',
            'status' => ResponseStatus::CREATED->code(),
        ];
    }

    public
    function createToken(array $credentials)
    {
        $response = Http::asForm()->post(config('services.passport.token_endpoint'), [
            'grant_type' => 'password',
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
            'username' => $credentials['email'],
            'password' => $credentials['password'],
            'scope' => ''
        ]);

        return $response->json();
    }

    public function refreshAccessToken()
    {
        $user = auth()->user();

        $response = Http::post(config('services.oauth_server.uri') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->token->refresh_token,
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
//            'redirect_uri' => config('services.passport.redirect'),
            'scope' => ''
        ]);

        if ($response->status() !== 200) {
            $user->token->revoke();

            return ResponseAction::build(status: ResponseStatus::INTERNAL_ERROR->code(), error: 'Something went wrong');
        }

        $response = $response->json();
        $user->token->update([
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ]);

        return ResponseAction::build('Token refreshed successfully', [
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']
        ], ResponseStatus::OK->code());
    }
}
