<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Visible tables
    |--------------------------------------------------------------------------
    |
    | This will let us control the tables we want to show. To show
    | all the tables, use asterisk symbol.
    |
    */

    'visible_tables' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Hidden tables
    |--------------------------------------------------------------------------
    |
    | Opposite of `show_tables`. This will let you specify which tables,
    | should be hidden. However, this has priority over `show_tables`.
    | If you specify asterisk symbol here, all tables will be hidden.
    |
    */

    'hidden_tables' => [],

    /*
    |--------------------------------------------------------------------------
    | Hidden columns
    |--------------------------------------------------------------------------
    |
    | This will let us hide specific columns. Useful when you want to hide,
    | passwords or unique values.
    |
    */

    'hidden_columns' => [
        'users' => [
            'password',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | If you want to modify single component or logic behind it,
    | feel free to point the component classes here.
    |
    */

    'components' => [
        'input' => InvoiceNinja\Inspector\Components\Input::class,
        'tables' => InvoiceNinja\Inspector\Components\Tables::class,
        'columns' => InvoiceNinja\Inspector\Components\Columns::class,
        'records' => InvoiceNinja\Inspector\Components\Records::class,
        'record' => InvoiceNinja\Inspector\Components\Record::class,
    ],
];
