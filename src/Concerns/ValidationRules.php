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

        $this->type($column, $rules);
        $this->length($column, $rules);

        return $rules;
    }

    private static function type(\Doctrine\DBAL\Schema\Column $column, &$rules): void
    {
        switch ($column->getType()) {
            case '\Integer':
                \array_push($rules, 'int');
                break;

            default:
                break;
        }
    }

    private static function length(\Doctrine\DBAL\Schema\Column $column, &$rules): void
    {
        if (! \is_null($column->getLength())) {
            return;
        }

        \array_push($rules, \sprintf('max:%s', $column->getLength()));
    }
}
