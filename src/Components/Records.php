<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;

class Records extends BladeComponent
{
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public $records;

    public array $columns;

    public \Doctrine\DBAL\Schema\Table $table;

    public ?string $showRouteName;

    public function __construct($records, array $columns, \Doctrine\DBAL\Schema\Table $table, string $showRouteName = null)
    {
        $this->records = $records;

        $this->columns = $columns;

        $this->table = $table;

        $this->showRouteName = $showRouteName;
    }

    public function render(): View
    {
        return view('inspector::components.records');
    }
}
