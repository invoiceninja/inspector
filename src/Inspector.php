<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvoiceNinja\Inspector\Concerns\EnabledColumns;
use InvoiceNinja\Inspector\Concerns\EnabledTables;

class Inspector
{
    use EnabledTables, EnabledColumns;

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
        $tableNames = $this->getSchemaManager()->listTableNames();

        return $this->filterEnabledTables($tableNames);
    }

    public function getTableSchema(string $table): \Doctrine\DBAL\Schema\Table
    {
        $this->checkTableAvailablility($table);

        return $this->getSchemaManager()->listTableDetails($table);
    }

    public function getTableColumns(string $table): array
    {
        $this->checkTableAvailablility($table);

        $columns = $this->getTableSchema($table)->getColumns();

        return $this->filterEnabledColumns($columns, $table);
    }

    public function getTable(string $table): Builder
    {
        $this->checkTableAvailablility($table);

        return DB::connection($this->connectionName)->table($table);
    }

    public function getTableRecords(string $table, array $columns = ['*']): Collection
    {
        $this->checkTableAvailablility($table);

        return $this->getTable($table)->get($columns);
    }

    public function getTableRecord(string $table, string $value, string $column = 'id')
    {
        $this->checkTableAvailablility($table);

        return $this->getTable($table)->where($column, '=', $value)->first();
    }

    public function updateTableRecord(string $table, string $id, Request $request, string $column = 'id'): bool
    {
        $this->checkTableAvailablility($table);

        $data = $request->except(['_token', '_method']);

        return $this->getTable($table)->where($column, '=', $id)->update(
            \array_diff($data, $this->excludedRequestFields)
        );
    }
}
