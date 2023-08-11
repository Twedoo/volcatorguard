<?php namespace Twedoo\VolcatorGuard;

/**
 * This file is part of VolcatorGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\volcatorGuard
 */

use Illuminate\Support\Facades\Facade;

class VolcatorGuardByApplicationFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'volcatorGuardByApplication';
    }
}
