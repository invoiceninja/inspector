<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvoiceNinja\Inspector\Concerns\EnabledColumns;
use InvoiceNinja\Inspector\Concerns\EnabledTables;
use InvoiceNinja\Inspector\Concerns\ValidationRules;

class Inspector
{
    use EnabledTables, EnabledColumns, ValidationRules;

    protected string $connectionName = '';

    protected array $excludedRequestFields = [
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

        $data = $request->except($this->excludedRequestFields);

        $data = $this->transformFields($data);

        return $this->getTable($table)->where($column, '=', $id)->update($data);
    }

    public function validate(Request $request, string $table)
    {
        $this->checkTableAvailablility($table);

        $fields = $this->transformFields(
            $request->except($this->excludedRequestFields)
        );

        $columns = $this->getTableColumns($table);

        foreach ($fields as $field => $value) {
            $fields[$field] = $this->generateValidationFields($field, $columns);
        }

        return Validator::make($request->all(), $fields)->validate();
    }
}
