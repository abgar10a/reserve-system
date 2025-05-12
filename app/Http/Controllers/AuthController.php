<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Services\AuthService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = app(AuthService::class);
    }

    public function register(Request $request): ?JsonResponse
    {
        try {
            $userData = $request->validate([
                'name' => ['required', 'min:3', 'max:10'],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => ['required', 'min:8', 'max:30']
            ]);

            $result = $this->authService->register($userData, $request->code);

            if (isset($result['error'])) {
                return ResponseAction::error($result['error']);
            }

            return ResponseAction::successData($result['message'], $result);

        } catch (Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function login(Request $request): ?JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $result = $this->authService->login($credentials);

            if (isset($result['error'])) {
                return ResponseAction::error($result['error']);
            }

            return ResponseAction::successData($result['message'], $result);
        } catch (Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_BAD_REQUEST);
        }

    }

    public function logout(): ?JsonResponse
    {
        try {
            $logoutResponse = $this->authService->logout();

            return ResponseAction::success($logoutResponse['message']);
        } catch (Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function redirectToProvider($provider): JsonResponse|RedirectResponse
    {
        try {
            return Socialite::driver($provider)->stateless()->redirect();
        } catch (Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function handleProviderCallback($provider): ?JsonResponse
    {
        try {
            $callbackResponse = $this->authService->handleOauthCallback($provider);

            if (isset($callbackResponse['error'])) {
                return ResponseAction::error($callbackResponse['error']);
            }

            return ResponseAction::successData($callbackResponse['message'], $callbackResponse);
        } catch (Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            $request->validate([
                'refresh_token' => 'required|string',
            ]);

            $response = Http::asForm()->post(config('services.passport.token_endpoint'), [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'scope' => '',
            ]);
            if ($response->failed()) {
                return response()->json(['error' => 'Token refresh failed'], 401);
            }

            return $response->json();
        } catch (Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
