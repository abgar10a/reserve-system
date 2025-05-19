<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Enums\ResponseStatus;
use App\Repositories\Interfaces\ITableRepository;

readonly class TableService
{
    public function __construct(private ITableRepository $tableRepository)
    {

    }

    public function createTable(array $tableData): array
    {
        $table = $this->tableRepository->create($tableData);

        if (!$table) {
            return ResponseAction::build(error: 'Error creating table');
        }

        return ResponseAction::build('Table created successfully', [
            'table' => $table,
        ], ResponseStatus::CREATED->code());
    }

    public function updateTable(array $tableData, int $id): array
    {
        $table = $this->tableRepository->find($id);

        if (!$table) {
            return ResponseAction::build(status: ResponseStatus::NOT_FOUND->code(), error: 'Table not found');
        }

        $table->update($tableData);

        return ResponseAction::build('Table updated successfully', [
            'table' => $table->refresh(),
        ], ResponseStatus::UPDATED->code());
    }

    public function deleteTable(int $id): array
    {
        $table = $this->tableRepository->find($id);

        if (!$table) {
            return ResponseAction::build(status: ResponseStatus::NOT_FOUND->code(), error: 'Table not found');
        }

        $table->delete();

        return ResponseAction::build('Table deleted successfully', status: ResponseStatus::DELETED->code());
    }

}
