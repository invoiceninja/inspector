<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
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

    public function setConnectionName(string $connectionName): self
    {
        $this->connectionName = $connectionName;

        return $this;
    }

    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    public function getSchemaManager(): SchemaBuilder
    {
        return DB::connection($this->connectionName)->getSchemaBuilder();
    }

    public function getTableNames(): array
    {
        $tableNames = $this->getSchemaManager()->getTableListing(schemaQualified: false);

        return $this->filterEnabledTables($tableNames);
    }

    public function getTableSchema(string $table): array
    {
        $this->checkTableAvailablility($table);

        foreach ($this->getSchemaManager()->getTables() as $tableSchema) {
            if ($tableSchema['name'] === $table || $tableSchema['schema_qualified_name'] === $table) {
                return $tableSchema;
            }
        }

        return [
            'name' => $table,
            'schema' => null,
            'schema_qualified_name' => $table,
        ];
    }

    public function getTableColumns(string $table): array
    {
        $this->checkTableAvailablility($table);

        $columns = [];

        foreach ($this->getSchemaManager()->getColumns($table) as $column) {
            $type = $this->normalizeType($column);

            $columns[$column['name']] = [
                'name' => $column['name'],
                'type' => $column['type'],
                'type_name' => $type,
                'nullable' => (bool) $column['nullable'],
                'length' => $type === 'string' ? $this->extractLength($column['type']) : null,
            ];
        }

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

    /**
     * Normalize the database-specific column type into a canonical type.
     */
    protected function normalizeType(array $column): string
    {
        $type = strtolower($column['type_name']);

        if ($type === 'tinyint' && str_contains((string) $column['type'], '(1)')) {
            return 'boolean';
        }

        return match (true) {
            $type === 'smallint' => 'smallint',
            in_array($type, ['int', 'integer', 'bigint', 'mediumint', 'tinyint', 'int2', 'int4', 'int8'], true) => 'integer',
            in_array($type, ['bool', 'boolean'], true) => 'boolean',
            str_contains($type, 'datetime') || str_contains($type, 'timestamp') => 'datetime',
            $type === 'date' => 'date',
            in_array($type, ['decimal', 'numeric', 'money', 'smallmoney', 'float', 'double', 'double precision', 'real'], true) => 'decimal',
            default => 'string',
        };
    }

    /**
     * Extract the declared length from a column type, e.g. varchar(255).
     */
    protected function extractLength(string $type): ?int
    {
        return preg_match('/\((\d+)/', $type, $matches) === 1 ? (int) $matches[1] : null;
    }
}
