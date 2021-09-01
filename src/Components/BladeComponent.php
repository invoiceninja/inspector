<?php

namespace InvoiceNinja\Inspector\Components;

use Illuminate\View\Component as IlluminateComponent;

abstract class BladeComponent extends IlluminateComponent
{
    /** @var array */
    protected static $assets = [];

    public static function assets(): array
    {
        return static::$assets;
    }
}
