<?php

namespace InvoiceNinja\Inspector\Concerns;

use InvoiceNinja\Inspector\Exceptions\TableNotEnabled;

trait EnabledTables
{
    public function filterEnabledTables(array $tableNames): array
    {
        $tables = [];

        foreach (config('inspector.show_tables') as $tableName) {
            if (\in_array($tableName, $tableNames)) {
                $tables[] = $tableName;
            }
        }

        if (\in_array('*', config('inspector.show_tables'))) {
            $tables = $tableNames;
        }

        foreach (config('inspector.hide_tables') as $tableName) {
            $tables = \array_diff($tables, [$tableName]);
        }

        if (\in_array('*', config('inspector.hide_tables'))) {
            $tables = [];
        }

        return $tables;
    }

    public function isTableEnabled(string $tableName): bool
    {
        if (\in_array('*', config('inspector.hide_tables'))) {
            return false;
        }

        if (\in_array($tableName, config('inspector.hide_tables'))) {
            return false;
        }

        if (\in_array('*', config('inspector.show_tables'))) {
            return true;
        }

        if (\in_array($tableName, config('inspector.show_tables'))) {
            return true;
        }

        return false;
    }

    public function checkTableAvailablility(string $tableName): void
    {
        if (!$this->isTableEnabled($tableName)) {
            throw new TableNotEnabled("Table [$tableName] is not enabled in the configuration file.");
        }

        // TableNotFound
    }
}
