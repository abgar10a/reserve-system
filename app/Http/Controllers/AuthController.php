<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthProviderCallbackRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{

    public function __construct(private readonly AuthService $authService)
    {

    }

    public function register(AuthRegisterRequest $request): ?JsonResponse
    {
        $userData = $request->validated();

        return ResponseAction::handleResponse($this->authService->register($userData));
    }

    public function login(AuthLoginRequest $request): ?JsonResponse
    {
        $credentials = $request->validated();

        return ResponseAction::handleResponse($this->authService->login($credentials));
    }

    public function logout(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->authService->logout());
    }

    public function redirectToProvider($provider): JsonResponse|RedirectResponse
    {
        if (empty(config('services.' . $provider))
            || empty(config('services.' . $provider . '.client_id'))
            || empty(config('services.' . $provider . '.client_secret'))
            || empty(config('services.' . $provider . '.redirect'))) {
            return ResponseAction::error('Invalid provider', ResponseAlias::HTTP_BAD_REQUEST);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(AuthProviderCallbackRequest $request, $provider): object
    {
        $tokenData = $request->validated();

        return ResponseAction::handleResponse($this->authService->handleOauthCallback($provider, $tokenData['token']));
    }

    public function refreshToken(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->authService->refreshAccessToken());
    }
}
