<?php

namespace InvoiceNinja\Inspector\Concerns;

trait ValidationRules
{
    public function generateValidationFields(string $column, array $columns): array
    {
        if (!\in_array($column, $columns)) {
            // ..
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

    private static function type(\Doctrine\DBAL\Schema\Column $column, &$rules): void
    {
        switch ($column->getType()) {
            case '\Integer':
            case '\SmallInt':
                \array_push($rules, 'int');
                \array_push($rules, 'numeric');
                break;

            case '\String':
                \array_push($rules, 'string');
                break;

            case '\Decimal':
                \array_push($rules, 'between:0,99.99');
                break;

            default:
                break;
        }
    }

    private static function length(\Doctrine\DBAL\Schema\Column $column, &$rules): void
    {
        if (\is_null($column->getLength()) || $column->getLength() === 0) {
            return;
        }

        if ($column->getName() === 'created_at' || $column->getName() === 'updated_at' || $column->getName() === 'deleted_at') {
            return;
        }

        \array_push($rules, \sprintf('max:%s', $column->getLength()));
    }

    private static function required(\Doctrine\DBAL\Schema\Column $column, &$rules): void
    {
        if ($column->getNotNull()) {
            \array_push($rules, 'required');
        } else {
            \array_push($rules, 'nullable');
        }
    }
}
