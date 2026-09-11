<?php

namespace InvoiceNinja\Inspector\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use InvoiceNinja\Inspector\Components\Input;

class ComponentTest extends TestCase
{
    public function test_input_component_maps_column_types_to_views(): void
    {
        $this->assertSame('inspector::components.number', $this->viewFor('integer'));
        $this->assertSame('inspector::components.number', $this->viewFor('smallint'));
        $this->assertSame('inspector::components.text', $this->viewFor('string'));
        $this->assertSame('inspector::components.datetime-local', $this->viewFor('datetime'));
        $this->assertSame('inspector::components.date', $this->viewFor('date'));
        $this->assertSame('inspector::components.decimal', $this->viewFor('decimal'));
        $this->assertSame('inspector::components.boolean', $this->viewFor('boolean'));
        $this->assertSame('inspector::components.text', $this->viewFor('unknown'));
    }

    public function test_components_render_without_errors(): void
    {
        view()->share('errors', new ViewErrorBag);

        $columns = [
            'id' => ['name' => 'id', 'type' => 'integer', 'type_name' => 'integer', 'nullable' => false, 'length' => null],
            'name' => ['name' => 'name', 'type' => 'varchar(100)', 'type_name' => 'string', 'nullable' => true, 'length' => 100],
        ];

        $table = ['name' => 'users', 'schema' => null, 'schema_qualified_name' => 'users'];

        $this->assertStringContainsString('Users', Blade::render(
            '<x-inspector-tables :tables="$tables" />',
            ['tables' => ['users']]
        ));

        $this->assertStringContainsString('string', Blade::render(
            '<x-inspector-columns :columns="$columns" />',
            ['columns' => $columns]
        ));

        $this->assertStringContainsString('Alice', Blade::render(
            '<x-inspector-records :records="$records" :columns="$columns" :table="$table" />',
            [
                'records' => new Collection([(object) ['id' => 1, 'name' => 'Alice']]),
                'columns' => $columns,
                'table' => $table,
            ]
        ));

        $this->assertStringContainsString('Alice', Blade::render(
            '<x-inspector-record :record="$record" :table="$table" :columns="$columns" />',
            [
                'record' => (object) ['id' => 1, 'name' => 'Alice'],
                'columns' => $columns,
                'table' => $table,
            ]
        ));
    }

    private function viewFor(string $type): string
    {
        return (new Input(['name' => 'foo', 'type_name' => $type], null))->render()->name();
    }
}
