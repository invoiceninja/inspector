<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Inspector
{
    protected string $connectionName = '';

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

    public function getTableRecords(string $table): Collection
    {
        return DB::connection($this->connectionName)->table($table)->get();
    }
}
