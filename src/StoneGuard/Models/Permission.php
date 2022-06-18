<?php

namespace Twedoo\StoneGuard\Models;

use Twedoo\StoneGuard\StoneGuardPermission;
use Twedoo\Stone\Organizer\Models\Stones;


class Permission extends StoneGuardPermission
{

    protected $fillable = [
        'name', 'display_name', 'description', 'id_stone'
    ];

    protected $table = "permissions";

    public function getModule()
    {
        return $this->hasMany(Stones::class, 'im_id', 'id_stone');

    }
}
