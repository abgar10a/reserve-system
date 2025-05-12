<?php

namespace App\Services;

use App\Actions\ResponseAction;
use App\Repositories\Interfaces\ITableRepository;

readonly class TableService
{
    public function __construct(private ITableRepository $tableRepository)
    {

    }

    public function createTable($tableData): array
    {
        $table = $this->tableRepository->create($tableData);

        if (!$table) {
            return ResponseAction::build(error: 'Error creating table');
        }

        return ResponseAction::build('Table created successfully', [
            'table' => $table,
        ]);
    }

    public function updateTable($tableData, $id)
    {
        $table = $this->tableRepository->find($id);

        if (!$table) {
            return ResponseAction::build(error: 'Table not found');
        }

        $table->update($tableData);

        return ResponseAction::build('Table updated successfully', [
            'table' => $table->refresh(),
        ]);
    }

    public function deleteTable($id): array
    {
        $table = $this->tableRepository->find($id);

        if (!$table) {
            return ResponseAction::build(error: 'Table not found');
        }

        $table->delete();

        return ResponseAction::build('Table deleted successfully');
    }

}
