<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;
use Doctrine\DBAL\Schema\Column;

class Tables extends BladeComponent
{
    public array $tables;

    public string $showRouteName;

    public function __construct(array $tables, string $showRouteName)
    {
        $this->tables = $tables;

        $this->showRouteName = $showRouteName;
    }

    public function render(): View
    {
        return view("inspector::components.tables");
    }
}
