<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface IRepository
{
    public function find($id = null);

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function query(): Builder;
}
