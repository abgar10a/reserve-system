<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\ResponseStatus;
use App\Repositories\Interfaces\IUserRepository;

readonly class UserService
{
    public function __construct(private IUserRepository $userRepository)
    {

    }

    public function updateUser(array $userData): array
    {
        $user = $this->userRepository->update($userData, auth()->id());

        return ResponseAction::build('User updated successfully', [
            'user' => $user,
        ], ResponseStatus::UPDATED->code());
    }

    public function getUser(): array
    {
        $user = $this->userRepository->find(auth()->id());

        return ResponseAction::build('User found', [
            'user' => $user,
        ], ResponseStatus::OK->code());
    }

    public function deleteUser(): array
    {
        $user = $this->userRepository->find(auth()->id());

        $tokens = $user->tokens();

        foreach ($tokens as $token) {
            $token->refresh_token?->revoke();
            $token->revoke();
        }

        $user->delete();

        return ResponseAction::build('User deleted successfully', status: ResponseStatus::DELETED->code());
    }
}
