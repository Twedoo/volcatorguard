<?php namespace Twedoo\StoneGuard;

/**
 * This file is part of StoneGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\stoneGuard
 */

use Twedoo\StoneGuard\Contracts\StoneGuardRoleInterface;
use Twedoo\StoneGuard\Traits\StoneGuardRoleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class StoneGuardRole extends Model implements StoneGuardRoleInterface
{
    use StoneGuardRoleTrait;

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
        $this->table = Config::get('stone.roles_table');
    }

}
