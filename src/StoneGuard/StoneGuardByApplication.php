<?php namespace Twedoo\StoneGuard;

use DB;
use League\Flysystem\Config;
use Twedoo\Stone\Core\StoneApplication;
use Twedoo\StoneGuard\Models\Permission;
use Twedoo\StoneGuard\Models\Role;
use Twedoo\StoneGuard\Models\User;
/**
 * This class is the main entry point of StoneGuard. Usually the interaction
 * with this class will be done through the StoneGuard Facade
 *
 * @license MIT
 * @package Twedoo\stoneGuard
 */

class StoneGuardByApplication
{
    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;
    private static $apiContext = '';

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * @param $user
     * @param $permission
     * @return
     */
     public function isCanPermissionByApplication() {
         $user = auth()->user();
         return Permission::join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
             ->join('role_user', 'role_user.role_id', '=', 'permission_role.role_id')
             ->where('role_user.application_id', StoneApplication::getCurrentApplicationId())
             ->where('role_user.user_id', $user->id)->pluck('permissions.name')->toArray();
     }

    /**
     * @param $user
     * @param $permission
     * @return
     */
     public function byPassPermissions($permission) {
         $user = auth()->user();
         return Permission::join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
             ->join('role_user', 'role_user.role_id', '=', 'permission_role.role_id')
             ->whereIn('permissions.name', $permission)
             ->where('role_user.user_id', $user->id)
             ->pluck('permissions.name')->toArray();
     }

    /**
     * @param $permission
     * @param $permissions
     * @param array $options
     * @return bool
     */
    public function isAllowedInCurrentApplication($permission, $permissions) {
         return in_array($permission, $permissions);
    }
}
