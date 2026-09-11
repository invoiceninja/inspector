<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;

class Input extends BladeComponent
{
    public array $column;

    public $value;

    protected array $mapping = [
        'integer' => 'number',
        'string' => 'text',
        'datetime' => 'datetime-local',
        'date' => 'date',
        'decimal' => 'decimal',
        'smallint' => 'number',
        'boolean' => 'boolean',
    ];

    public function __construct(array $column, $value)
    {
        $this->column = $column;

        $this->value = $value;
    }

    public function render(): View
    {
        $component = $this->mapping[$this->column['type_name']] ?? 'text';

        return view("inspector::components.{$component}");
    }
}
