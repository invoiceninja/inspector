<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Inspector
{
    protected string $connectionName = '';

    protected array $excludedRequestFields = [
        'id',
        '_token',
        '_method',
    ];

    public function __construct()
    {
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    public function setConnectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    public function getSchemaManager()
    {
        return DB::connection($this->connectionName)->getDoctrineSchemaManager();
    }

    public function getTableNames(): array
    {
        return $this->getSchemaManager()->listTableNames();
    }

    public function getTableSchema(string $table): \Doctrine\DBAL\Schema\Table
    {
        return $this->getSchemaManager()->listTableDetails($table);
    }

    public function getTableColumns(string $table): array
    {
        return $this->getTableSchema($table)->getColumns();
    }

    public function getTable(string $table): Builder
    {
        return DB::connection($this->connectionName)->table($table);
    }

    public function getTableRecords(string $table, array $columns = ['*']): Collection
    {
        return $this->getTable($table)->get($columns);
    }

    public function getTableRecord(string $table, string $value, string $column = 'id')
    {
        return $this->getTable($table)->where($column, '=', $value)->first();
    }

    public function updateTableRecord(string $table, string $id, Request $request, string $column = 'id'): bool
    {
        $data = $request->except(['_token', '_method']);

        return $this->getTable($table)->where($column, '=', $id)->update(
            \array_diff($data, $this->excludedRequestFields)
        );
    }
}
