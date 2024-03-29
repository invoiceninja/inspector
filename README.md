<p align="center">
    <img src="https://raw.githubusercontent.com/invoiceninja/inspector/main/resources/static/cover.png" alt="inspector logo">
</p>

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/invoiceninja/inspector.svg?style=flat-square)](https://packagist.org/packages/invoiceninja/inspector)
[![Total Downloads](https://img.shields.io/packagist/dt/invoiceninja/inspector.svg?style=flat-square)](https://packagist.org/packages/invoiceninja/inspector)
![GitHub Actions](https://github.com/invoiceninja/inspector/actions/workflows/main.yml/badge.svg) -->

Simplified database records management. Inspector will let you take care of [CRUD](https://en.wikipedia.org/wiki/Create,_read,_update_and_delete) without taking over your frontend.

## Example
```php
$inspector = new \InvoiceNinja\Inspector\Inspector();

// List all tables in the database
$tables = $inspector->getTableNames();

// Get table columns
$columns = $inspector->getTableColumns('users');
```

- [Example](#example)
- [Installation](#installation)
- [Requirements](#requirements)
- [Philosophy](#philosophy)
- [Usage](#usage)
  - [Showing tables in the database](#showing-tables-in-the-database)
  - [Showing table columns](#showing-table-columns)
  - [Showing table records](#showing-table-records)
  - [Showing & editing row in the table](#showing--editing-row-in-the-table)
  - [Updating table row](#updating-table-row)
- [Configuration](#configuration)
- [Available methods](#available-methods)
- [Contributing](#contributing)
  - [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```bash
composer require invoiceninja/inspector
```
## Requirements
- Laravel 8.x
- PHP 7.4+

## Philosophy
Inspector isn't your regular admin panel. It is meant to be used as part of the admin panel. That said, we wanted something that is lightweight and it doesn't take over your front end. 

It doesn't care about your CSS framework, do you use Livewire or not, because you're in charge of integrating it. Don't worry, it's extremely simple.

## Usage

Like we previously said, **you're in charge** of integrating Inspector, but we will give you the most simple examples here.

Start by creating one controller, we will name it `TableController`.

```bash
php artisan make:controller TableController
```

### Showing tables in the database

```php
public function index(\InvoiceNinja\Inspector\Inspector $inspector)
{
    return view('tables.index', [
        'tables' => $inspector->getTableNames(),
    ]);
}
```

Now, to show all these tables, you can make your own loop. To speed things up, we've provided some prebuilt components.

```html
<x-inspector-tables :tables="$tables" />
```

This will show a nice preview of all tables in your database.

| Tables                 |
|------------------------|
| Failed jobs            |
| Migrations             |
| Password resets        |
| Personal access tokens |
| Users                  |

Awesome, let's make the link to the individual table page. We can do this by passing the `show-route-name` parameter in the component.

```html
<x-inspector-tables :tables="$tables" show-route-name="tables.show" />
```

> Note: Route name is fully **optional**. We're using a resourceful controller, following Laravel conventions.

By doing that, we should get a new "View" action in our table:

| Table                  | Action |
|------------------------|--------|
| Failed jobs            | View   |
| Migrations             | View   |
| Password resets        | View   |
| Personal access tokens | View   |
| Users                  | View   |

### Showing table columns

It might be useful for you to preview table columns & their types. To achieve that we can use the `getTableColumns` method.

```php
public function show(string $table, \InvoiceNinja\Inspector\Inspector $inspector)
{
    return view('tables.show', [
        'columns' => $inspector->getTableColumns($table),
    ]);
}
```

```html
<x-inspector-columns :columns="$columns" />
```

That will produce a nice table with all columns/types.

| Column    | Type    |
|-----------|---------|
| id        | integer |
| migration | string  |
| batch     | integer |

### Showing table records

To show table records, we can make use of the `getTableRecords` method.

```php
public function show(string $table, \InvoiceNinja\Inspector\Inspector $inspector)
{
    return view('tables.show', [
        'table' => $inspector->getTableSchema($table),
        'columns' => $inspector->getTableColumns($table),
        'records' => $inspector->getTableRecords($table),
    ]);
}
```

```html
<x-inspector-records 
    :table="$table" 
    :columns="$columns"
    :records="$records" /> 
```

To generate a link to a specific record, pass `show-route-name`:

```html
<x-inspector-records 
    :table="$table" 
    :columns="$columns"
    :records="$records"
    show-route-name="tables.edit" /> 
```

This will generate URL like this: `/tables/{table}/edit?id=1`.

| #    | id | migration                            | batch |
|------|----|--------------------------------------|-------|
| View | 1  | 2014_10_12_000000_create_users_table | 1     |

### Showing & editing row in the table

Showing a page for the specific row is super simple. We can make use of the `getTableRecord` method.

```php
public function edit(string $table, \Illuminate\Http\Request $request, \InvoiceNinja\Inspector\Inspector $inspector)
{
    return view('tables.edit', [
        'table' => $inspector->getTableSchema($table),
        'columns' => $inspector->getTableColumns($table),
        'record' => $inspector->getTableRecord($table, $request->query('id')),
    ]);
}
```

```html
<x-inspector-record 
    :record="$record" 
    :table="$table"
    :columns="$columns"
    update-route-name="tables.update" />
```

This will generate the form with all columns as input fields & their values as part of input values.

> Note: `update-route-name` is **optional**.

### Updating table row

One thing that is left is updating the table row. As you can probably guess, Inspector provides a helper method - `updateTableRecord`.

```php
public function update(string $table, \Illuminate\Http\Request $request, \InvoiceNinja\Inspector\Inspector $inspector)
{
    $inspector->validate($request, $table);

    $success = $inspector->updateTableRecord($table, $request->query('id'), $request);

    if ($success) {
        return back()->withMessage('Successfully updated the record.');
    }

    return back()->withMessage('Oops, something went wrong.');
}
```

## Configuration
We did our best to make Inspector as configurable as possible. To tinker with a configuration file, make sure to publish it first.

```bash
php artisan vendor:publish --provider="InvoiceNinja\Inspector\InspectorServiceProvider"
```

With configuration published, you can control visible tables, as well as hidden, component classes & modify them as you wish.

## Available methods

- `setConnectionName(string $connectionName): self` - Set the database connection. By default it will pick up your default app connection.

- `getConnectionName(): string` - Retrieve the current connection name.

- `getSchemaManager(): Doctrine\DBAL\Schema\AbstractSchemaManager` - Retrieve current schema manager instance.

- `getTableNames(): array` - Retrieve the list of table names in the database.

- `getTableSchema(string $table): Doctrine\DBAL\Schema\Table` - Retrieve `Table` representation of table.

- `getTableColumns(string $table): array` - Retrieve all columns for specified table.

- `getTable(string $table): Illuminate\Database\Query\Builder` - Table instance of query builder.

- `getTableRecords(string $table, array $columns = ['*']): Illuminate\Support\Collection` - Retrieve all records for the specified table.

- `getTableRecord(string $table, string $value, string $column = 'id'): mixed` - Retrieve single record for specified table.

- `updateTableRecord(string $table, string $id, Request $request, string $column = 'id'): bool` - Update specific table row.

- `validate(Request $request, string $table)` - Validate specific request.
## Contributing

Please see [CONTRIBUTING](https://github.com/invoiceninja/invoiceninja/blob/master/CONTRIBUTING.md) for details.

### Security

If you discover any security-related issues, please email contact@invoiceninja.com instead of using the issue tracker.

## Credits

-   [Benjamin Beganović](https://github.com/invoiceninja)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
