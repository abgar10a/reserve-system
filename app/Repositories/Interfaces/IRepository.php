<?php

namespace App\Repositories\Interfaces;

interface IRepository
{
    public function find($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
