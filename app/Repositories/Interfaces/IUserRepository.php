<?php

namespace App\Repositories\Interfaces;

interface IUserRepository extends IRepository
{
    public function findByEmail(string $email);
}
