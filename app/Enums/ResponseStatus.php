<?php

namespace App\Enums;

enum ResponseStatus
{
    case OK;
    case CREATED;
    case UPDATED;
    case DELETED;

    case BAD_REQUEST;
    case UNAUTHORIZED;
    case FORBIDDEN;
    case NOT_FOUND;
    case CONFLICT;
    case UNPROCESSABLE;

    case INTERNAL_ERROR;

    public function label(): string
    {
        return match ($this) {
            self::OK => 'OK',
            self::CREATED => 'Created',
            self::UPDATED => 'Updated',
            self::DELETED => 'Deleted',
            self::BAD_REQUEST => 'Bad Request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Not Found',
            self::CONFLICT => 'Conflict',
            self::UNPROCESSABLE => 'Unprocessable Entity',
            self::INTERNAL_ERROR => 'Internal Server Error',
        };
    }


    public function code(): int
    {
        return match ($this) {
            self::OK, self::UPDATED, self::DELETED => 200,
            self::CREATED => 201,
            self::BAD_REQUEST => 400,
            self::UNAUTHORIZED => 401,
            self::FORBIDDEN => 403,
            self::NOT_FOUND => 404,
            self::CONFLICT => 409,
            self::UNPROCESSABLE => 422,
            self::INTERNAL_ERROR => 500,
        };
    }
}
