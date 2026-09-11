<?php

namespace InvoiceNinja\Inspector\Concerns;

trait ValidationRules
{
    public function generateValidationFields(string $column, array $columns): array
    {
        if (!\in_array($column, \array_keys($columns), true)) {
            return [];
        }

        $column = $columns[$column];
        $rules = [];

        $this->required($column, $rules);
        $this->type($column, $rules);
        $this->length($column, $rules);

        return $rules;
    }

    public function transformFields(array $requestBody): array
    {
        if (\array_key_exists('created_at', $requestBody)) {
            $requestBody['created_at'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $requestBody['created_at'])->format('Y-m-d H:i:s');
        }

        if (\array_key_exists('updated_at', $requestBody)) {
            $requestBody['updated_at'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $requestBody['updated_at'])->format('Y-m-d H:i:s');
        }

        if (\array_key_exists('deleted_at', $requestBody)) {
            $requestBody['deleted_at'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $requestBody['deleted_at'])->format('Y-m-d H:i:s');
        }

        return $requestBody;
    }

    private static function type(array $column, &$rules): void
    {
        switch ($column['type_name']) {
            case 'integer':
            case 'smallint':
                \array_push($rules, 'int');
                \array_push($rules, 'numeric');
                break;

            case 'string':
                \array_push($rules, 'string');
                break;

            case 'decimal':
                \array_push($rules, 'between:0,99.99');
                break;

            case 'date':
                \array_push($rules, 'date');
                break;

            case 'datetime':
                \array_push($rules, 'date_format:Y-m-d\TH:i');
                break;

            default:
                break;
        }
    }

    private static function length(array $column, &$rules): void
    {
        if (\is_null($column['length']) || $column['length'] === 0) {
            return;
        }

        if (\in_array($column['name'], ['created_at', 'updated_at', 'deleted_at'], true)) {
            return;
        }

        \array_push($rules, \sprintf('max:%s', $column['length']));
    }

    private static function required(array $column, &$rules): void
    {
        if ($column['nullable']) {
            \array_push($rules, 'nullable');
        } else {
            \array_push($rules, 'required');
        }
    }
}
