<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\Contracts\View\View;

class Record extends BladeComponent
{
    public $record;

    public array $table;

    public array $columns;

    public ?string $updateRouteName;

    public function __construct($record, array $table, array $columns, string $updateRouteName = null)
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
