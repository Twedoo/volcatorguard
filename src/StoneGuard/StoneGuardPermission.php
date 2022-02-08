<?php namespace Twedoo\StoneGuard;

/**
 * This file is part of StoneGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\stoneGuard
 */

use Twedoo\StoneGuard\Contracts\StoneGuardPermissionInterface;
use Twedoo\StoneGuard\Traits\StoneGuardPermissionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class StoneGuardPermission extends Model implements StoneGuardPermissionInterface
{
    use StoneGuardPermissionTrait;

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
        $this->table = Config::get('stoneGuard.permissions_table');
    }

}
