<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {

    }

    public function update(UpdateUserRequest $request): ?JsonResponse
    {
        $userData = $request->validated();

        return ResponseAction::handleResponse($this->userService->updateUser($userData));
    }

    public function show(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->userService->getUser());
    }

    public function delete(): ?JsonResponse
    {
        return ResponseAction::handleResponse($this->userService->deleteUser());
    }
}
