<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Repositories\Interfaces\IUserRepository;

readonly class UserService
{
    public function __construct(private IUserRepository $userRepository)
    {

    }

    public function updateUser($userData, $id): array
    {
        if (empty($userData)) {
            return ResponseAction::build(error: 'Empty data');
        }

        if (auth()->id() !== $id) {
            return ResponseAction::build(error: 'Unauthorized');
        }

        $user = $this->userRepository->update($userData, $id);

        return ResponseAction::build('User updated successfully', [
            'user' => $user,
        ]);
    }

    public function getUser($id): array
    {
        if (auth()->id() !== $id) {
            return ResponseAction::build(error: 'Unauthorized');
        }

        $user = $this->userRepository->find($id);

        return ResponseAction::build('User found', [
            'user' => $user,
        ]);
    }

    public function deleteUser($id): array
    {
        if (auth()->id() !== $id) {
            return ResponseAction::build(error: 'Unauthorized');
        }
        $user = $this->userRepository->find($id);

        $tokens = $user->tokens();

        foreach ($tokens as $token) {
            $token->refresh_token?->revoke();
            $token->revoke();
        }

        $user->delete();

        return ResponseAction::build('User deleted successfully');
    }
}
