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

### Showing all tables in database

```php
public function index(\InvoiceNinja\Inspector\Inspector $inspector)
{
    return view('tables.index', [
        'tables' => $inspector->getTableNames(),
    ]);
}
```

Now, to show all these tables, you can make your own loop. To speed things up, we've provided some prebuilt components.

```php
<x-inspector-tables :tables="$tables" />
```

This will show nice preview with all tables in your database.

| Tables                 |
|------------------------|
| Failed jobs            |
| Migrations             |
| Password resets        |
| Personal access tokens |
| Users                  |

## Contributing

Please see [CONTRIBUTING](https://github.com/invoiceninja/invoiceninja/blob/master/CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email contact@invoiceninja.com instead of using the issue tracker.

## Credits

-   [Benjamin Beganović](https://github.com/invoiceninja)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
