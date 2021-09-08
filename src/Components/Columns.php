<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;

class Columns extends BladeComponent
{
    public array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function render(): View
    {
        return view("inspector::components.columns");
    }
}
