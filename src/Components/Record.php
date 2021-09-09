<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;
use Doctrine\DBAL\Schema\Table;

class Record extends BladeComponent
{
    public $record;

    public Table $table;

    public array $columns;

    public ?string $updateRouteName;

    public function __construct($record, Table $table, array $columns, string $updateRouteName = null)
    {
        $this->columns = $columns;

        $this->record = $record;

        $this->table = $table;

        $this->updateRouteName = $updateRouteName;
    }

    public function render(): View
    {
        return view('inspector::components.record');
    }
}
