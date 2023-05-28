<?php

namespace Twedoo\VolcatorGuard\Models;

use Twedoo\VolcatorGuard\VolcatorGuardPermission;
use Twedoo\Volcator\Organizer\Models\Volcators;


class Permission extends VolcatorGuardPermission
{

    protected $fillable = [
        'name', 'display_name', 'description', 'id_volcator'
    ];

    protected $table = "permissions";

    public function getModule()
    {
        return $this->hasMany(Volcators::class, 'im_id', 'id_volcator');

    }
}
