<?php namespace Twedoo\VolcatorGuard\Contracts;

/**
 * This file is part of VolcatorGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\volcatorGuard
 */

interface VolcatorGuardPermissionInterface
{

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();
}
