<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;
use Doctrine\DBAL\Schema\Column;

class Input extends BladeComponent
{
    public Column $column;

    public $value;

    protected array $mapping = [
        '\Integer' => 'number',
        '\String' => 'text',
        '\DateTime' => 'datetime-local',
        '\Date' => 'date',
        '\Decimal' => 'decimal',
        '\SmallInt' => 'number',
        '\Boolean' => 'boolean',
    ];

    public function __construct(Column $column, $value)
    {
        $this->column = $column;

        $this->value = $value;
    }

    public function render(): View
    {
        $component = $this->mapping[$this->column->getType()->getName()] ?? 'text';

        return view("inspector::components.{$component}");
    }
}
