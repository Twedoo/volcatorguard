<?php namespace Twedoo\VolcatorGuard;

/**
 * This file is part of VolcatorGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\volcatorGuard
 */

use Twedoo\VolcatorGuard\Contracts\VolcatorGuardRoleInterface;
use Twedoo\VolcatorGuard\Traits\VolcatorGuardRoleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class VolcatorGuardRole extends Model implements VolcatorGuardRoleInterface
{
    use VolcatorGuardRoleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('volcator.roles_table');
    }

}
