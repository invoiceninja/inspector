<?php

namespace InvoiceNinja\Inspector;

use Illuminate\Support\Facades\Facade;

/**
 * @see \InvoiceNinja\Inspector\Skeleton\SkeletonClass
 */
class InspectorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'inspector';
    }
}
