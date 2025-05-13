<?php

namespace App\Http\Controllers;

use App\Actions\ResponseAction;
use App\Enums\OrderStatus;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {

    }

    public function update(Request $request, $id): ?JsonResponse
    {
        try {
            $userData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255',
            ]);

            $userResponse = $this->userService->updateUser($userData, $id);

            if (isset($userResponse['error'])) {
                return ResponseAction::error($userResponse['error']);
            }

            return ResponseAction::successData($userResponse['message'], $userResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): ?JsonResponse
    {
        try {
            $userResponse = $this->userService->getUser($id);

            if (isset($userResponse['error'])) {
                return ResponseAction::error($userResponse['error']);
            }

            return ResponseAction::successData($userResponse['message'], $userResponse);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id): ?JsonResponse
    {
        try {
            $userResponse = $this->userService->deleteUser($id);

            if (isset($userResponse['error'])) {
                return ResponseAction::error($userResponse['error']);
            }

            return ResponseAction::success($userResponse['message']);
        } catch (\Throwable $th) {
            return ResponseAction::error($th->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
