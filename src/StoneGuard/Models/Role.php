<?php

namespace Twedoo\StoneGuard\Models;

use Twedoo\StoneGuard\StoneGuardRole;
use Config;

class Role extends StoneGuardRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name', 'description', 'type'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, Config::get('stone::permission_role_table'));
    }
}
