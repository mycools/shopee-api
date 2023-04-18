<?php

namespace Mycools\Shopee;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mycools\Shopee\Skeleton\SkeletonClass
 */
class ShopeeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shopee';
    }
}
