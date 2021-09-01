<?php

namespace InvoiceNinja\Inspector\Concerns;

trait EnabledColumns
{
    public function filterEnabledColumns(array $columns, $tableName): array
    {
        if (is_null(config("inspector.hidden_columns.{$tableName}"))) {
            return $columns;
        }

        foreach ($columns as $columnName => $properties) {
            if (in_array($columnName, config("inspector.hidden_columns.{$tableName}"))) {
                unset($columns[$columnName]);
            }
        }

        return $columns;
    }
}
