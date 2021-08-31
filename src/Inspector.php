<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Support\Facades\DB;

class Inspector
{
    protected string $connectionName = '';

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
}
