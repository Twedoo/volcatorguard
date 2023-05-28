<?php

namespace Twedoo\VolcatorGuard\Models;

use Twedoo\Volcator\Modules\Applications\Models\Spaces;
use Twedoo\VolcatorGuard\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\HasApiTokens;
use Twedoo\VolcatorGuard\Traits\VolcatorGuardUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use VolcatorGuardUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'email', 'password', 'status', 'depend', 'local'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, Config::get('volcator::assigned_roles_table'))->withPivot('application_id');
    }

    /**
     * @return mixed
     */
    public function setCurrentIdRole()
    {
        return $this->roles->pluck('id', 'id')->first();
    }

    public function spaces()
    {
        return $this->hasMany(Spaces::class);
    }

}
