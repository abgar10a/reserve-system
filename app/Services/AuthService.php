<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

readonly class AuthService
{
    public function __construct(private IUserRepository $userRepository)
    {

    }

    public function register(array $userData): array
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
            ];
        } catch (Throwable $th) {
            return ResponseAction::build(error: $th->getMessage());
        }
    }

    public function login(array $credentials): array
    {
        try {
            if ($user = $this->attempt($credentials)) {

                $token = $user->createToken('API Token')->accessToken;

                return ResponseAction::build('User logged in successfully', [
                    'token' => $token,
                    'user' => $user,
                ]);
            }

            return ResponseAction::build(error: 'Invalid credentials');
        } catch (Throwable $th) {
            return ResponseAction::build(error: $th->getMessage());
        }
    }

    public function attempt(array $credentials): ?User
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    public function logout(): array
    {
        try {
            $user = auth()->user();

            if ($user->token()) {
                $user->token()->revoke();

                return ResponseAction::build('User logged out successfully');
            }

            return ResponseAction::build(error: 'Not authenticated');
        } catch (Throwable $th) {
            return ResponseAction::build(error: $th->getMessage());
        }
    }

    public function handleOauthCallback($provider): array
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = $this->userRepository->findByEmail($socialUser->getEmail());

        if (!$user) {
            $password = config('app.test_mode', false) ? '12345678' : Str::random(8);
            return $this->register([
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                'password' => $password,
            ]);
        }

        $token = $user->createToken('API Token')->accessToken;

        return ResponseAction::build('User logged in successfully', [
            'token' => $token,
            'user' => $user,
        ]);
    }
}
