<?php

namespace App\Exceptions;

use App\Actions\ResponseAction;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UnauthorizedException extends Exception
{
    public function render(): JsonResponse
    {
        return ResponseAction::error('Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
    }
}
