<?php

namespace InvoiceNinja\Inspector\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvoiceNinja\Inspector\Exceptions\TableNotEnabled;
use InvoiceNinja\Inspector\Inspector;

class InspectorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('create table users (
            id integer primary key not null,
            name varchar(100) null,
            active boolean not null default 0,
            rank smallint null,
            score decimal(8,2) null,
            ratio double precision null,
            location point null,
            duration interval null,
            born date null,
            created_at datetime null,
            updated_at datetime null
        )');
    }

    public function test_it_lists_tables(): void
    {
        $this->assertContains('users', app(Inspector::class)->getTableNames());
    }

    public function test_it_reads_table_columns(): void
    {
        $columns = app(Inspector::class)->getTableColumns('users');

        $this->assertSame('integer', $columns['id']['type_name']);
        $this->assertFalse($columns['id']['nullable']);

        $this->assertSame('string', $columns['name']['type_name']);
        $this->assertSame(100, $columns['name']['length']);
        $this->assertTrue($columns['name']['nullable']);

        $this->assertSame('boolean', $columns['active']['type_name']);
        $this->assertSame('smallint', $columns['rank']['type_name']);
        $this->assertSame('decimal', $columns['score']['type_name']);
        $this->assertSame('decimal', $columns['ratio']['type_name']);
        $this->assertSame('string', $columns['location']['type_name']);
        $this->assertSame('string', $columns['duration']['type_name']);
        $this->assertSame('date', $columns['born']['type_name']);
        $this->assertSame('datetime', $columns['created_at']['type_name']);
    }

    public function test_it_transforms_datetime_fields(): void
    {
        $transformed = app(Inspector::class)->transformFields([
            'created_at' => '2024-01-01T12:00',
            'name' => 'Alice',
        ]);

        $this->assertSame('2024-01-01 12:00:00', $transformed['created_at']);
        $this->assertSame('Alice', $transformed['name']);
    }

    public function test_it_generates_validation_rules(): void
    {
        $inspector = app(Inspector::class);

        $rules = $inspector->generateValidationFields('name', $inspector->getTableColumns('users'));

        $this->assertContains('string', $rules);
        $this->assertContains('nullable', $rules);
        $this->assertContains('max:100', $rules);
    }

    public function test_it_reads_table_schema(): void
    {
        $this->assertSame('users', app(Inspector::class)->getTableSchema('users')['name']);
    }

    public function test_it_switches_connection(): void
    {
        $inspector = app(Inspector::class);

        $this->assertSame('', $inspector->getConnectionName());
        $this->assertSame($inspector, $inspector->setConnectionName('testing'));
        $this->assertContains('users', $inspector->getTableNames());
    }

    public function test_it_filters_tables_and_columns_from_config(): void
    {
        config()->set('inspector.hidden_tables', ['users']);
        $this->assertNotContains('users', app(Inspector::class)->getTableNames());

        config()->set('inspector.hidden_tables', []);
        config()->set('inspector.visible_tables', ['other']);
        $this->assertSame([], app(Inspector::class)->getTableNames());

        config()->set('inspector.visible_tables', ['*']);
        config()->set('inspector.hidden_columns', ['users' => ['name']]);
        $this->assertArrayNotHasKey('name', app(Inspector::class)->getTableColumns('users'));
    }

    public function test_it_throws_for_disabled_table(): void
    {
        config()->set('inspector.visible_tables', ['*']);
        config()->set('inspector.hidden_tables', ['users']);

        $this->expectException(TableNotEnabled::class);

        app(Inspector::class)->getTableColumns('users');
    }

    public function test_it_reads_records(): void
    {
        DB::table('users')->insert(['id' => 1, 'name' => 'Alice', 'active' => 1]);

        $inspector = app(Inspector::class);

        $this->assertCount(1, $inspector->getTableRecords('users'));
        $this->assertSame('Alice', $inspector->getTableRecord('users', '1')->name);
    }

    public function test_it_updates_record(): void
    {
        DB::table('users')->insert(['id' => 1, 'name' => 'Alice', 'active' => 1]);

        $request = Request::create('/users/1', 'PUT', ['name' => 'Bob', 'active' => 1]);

        $this->assertTrue(app(Inspector::class)->updateTableRecord('users', '1', $request));
        $this->assertSame('Bob', DB::table('users')->where('id', 1)->value('name'));
    }

    public function test_it_validates_request(): void
    {
        DB::table('users')->insert(['id' => 1, 'name' => 'Alice', 'active' => 1]);

        $validated = app(Inspector::class)->validate(
            Request::create('/users/1', 'PUT', ['name' => 'Bob', 'active' => 1]),
            'users'
        );

        $this->assertSame('Bob', $validated['name']);
    }

    public function test_validation_fails_for_too_long_value(): void
    {
        DB::table('users')->insert(['id' => 1, 'name' => 'Alice', 'active' => 1]);

        $this->expectException(ValidationException::class);

        app(Inspector::class)->validate(
            Request::create('/users/1', 'PUT', ['name' => str_repeat('a', 101), 'active' => 1]),
            'users'
        );
    }
}
