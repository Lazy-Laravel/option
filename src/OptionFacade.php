<?php

namespace LazyLaravel\Option;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LazyLaravel\Option\Skeleton\SkeletonClass
 */
class OptionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'option';
    }
}
