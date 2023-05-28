<?php

namespace Twedoo\VolcatorGuard\Models;

use Twedoo\VolcatorGuard\VolcatorGuardRole;
use Config;

class Role extends VolcatorGuardRole
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
        return $this->belongsToMany(Permission::class, Config::get('volcator::permission_role_table'));
    }
}
