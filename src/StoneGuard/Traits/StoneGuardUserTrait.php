<?php namespace Twedoo\StoneGuard\Traits;

/**
 * This file is part of StoneGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\stoneGuard
 */

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Twedoo\Stone\Core\StoneApplication;
use Twedoo\StoneGuard\StoneGuardByApplication;

trait StoneGuardUserTrait
{
    /**
     * Big block of caching functionality.
     *
     * @return mixed Roles
     */
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'stoneGuard_roles_for_user_'.$this->$userPrimaryKey;
        if(Cache::getStore() instanceof TaggableStore) {
            return Cache::tags(Config::get('stone.role_user_table'))->remember($cacheKey, Config::get('cache.ttl'), function () {
                return $this->roles()->get();
            });
        }
        else return $this->roles()->get();
    }

    /**
     * {@inheritDoc}
     */
    public function save(array $options = [])
    {   //both inserts and updates
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('stone.role_user_table'))->flush();
        }
        return parent::save($options);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(array $options = [])
    {   //soft or hard
        $result = parent::delete($options);
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('stone.role_user_table'))->flush();
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function restore()
    {   //soft delete undo's
        $result = parent::restore();
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('stone.role_user_table'))->flush();
        }
        return $result;
    }

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::get('stone.role'), Config::get('stone.role_user_table'), Config::get('stone.user_foreign_key'), Config::get('stone.role_foreign_key'), 'application_id');
    }

    /**
     * Boot the user model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the user model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($user) {
            if (!method_exists(Config::get('auth.providers.users.model'), 'bootSoftDeletes')) {
                $user->roles()->sync([]);
            }

            return true;
        });
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param string|array $permission Permission string or array of permissions.
     * @param bool         $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function can($permission, $requireAll = false)
    {
        $allowed_permission_user = StoneGuardByApplication::isCanPermissionByApplication();
        $byPassPermission = [
            Config::get('stone.PERMISSION_SPACE_VIEW'),
            Config::get('stone.PERMISSION_SPACE_FULL'),
            Config::get('stone.PERMISSION_APPLICATION_FULL'),
            Config::get('stone.PERMISSION_APPLICATION_VIEW'),
        ];
        $bypass_without_application = is_array($permission) ? count(array_intersect($permission, $byPassPermission)) !== 0 : in_array($permission, $byPassPermission);
        // If we've permission global not need application like register users or switch between spaces, then by pass it without check application assigned
        if ($bypass_without_application) {
            return StoneGuardByApplication::byPassPermissions((array) $permission);
        }

        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);
                $is_manager_application = StoneGuardByApplication::isAllowedInCurrentApplication($permName, $allowed_permission_user);

                if ($hasPerm && !$requireAll && $is_manager_application) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                // Validate against the Permission table
                foreach ($role->cachedPermissions() as $perm) {
                    $is_manager_application = StoneGuardByApplication::isAllowedInCurrentApplication($perm->name, $allowed_permission_user);
                    if (Str::is( $permission, $perm->name) && $is_manager_application) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Checks role(s) and permission(s).
     *
     * @param string|array $roles       Array of roles or comma separated string
     * @param string|array $permissions Array of permissions or comma separated string.
     * @param array        $options     validate_all (true|false) or return_type (boolean|array|both)
     *
     * @throws \InvalidArgumentException
     *
     * @return array|bool
     */
    public function ability($roles, $permissions, $options = [])
    {
        // Convert string to array if that's what is passed in.
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }
        if (!is_array($permissions)) {
            $permissions = explode(',', $permissions);
        }

        // Set up default values and validate options.
        if (!isset($options['validate_all'])) {
            $options['validate_all'] = false;
        } else {
            if ($options['validate_all'] !== true && $options['validate_all'] !== false) {
                throw new InvalidArgumentException();
            }
        }
        if (!isset($options['return_type'])) {
            $options['return_type'] = 'boolean';
        } else {
            if ($options['return_type'] != 'boolean' &&
                $options['return_type'] != 'array' &&
                $options['return_type'] != 'both') {
                throw new InvalidArgumentException();
            }
        }

        // Loop through roles and permissions and check each.
        $checkedRoles = [];
        $checkedPermissions = [];
        foreach ($roles as $role) {
            $checkedRoles[$role] = $this->hasRole($role);
        }
        foreach ($permissions as $permission) {
            $checkedPermissions[$permission] = $this->can($permission);
        }

        // If validate all and there is a false in either
        // Check that if validate all, then there should not be any false.
        // Check that if not validate all, there must be at least one true.
        if(($options['validate_all'] && !(in_array(false,$checkedRoles) || in_array(false,$checkedPermissions))) ||
            (!$options['validate_all'] && (in_array(true,$checkedRoles) || in_array(true,$checkedPermissions)))) {
            $validateAll = true;
        } else {
            $validateAll = false;
        }

        // Return based on option
        if ($options['return_type'] == 'boolean') {
            return $validateAll;
        } elseif ($options['return_type'] == 'array') {
            return ['roles' => $checkedRoles, 'permissions' => $checkedPermissions];
        } else {
            return [$validateAll, ['roles' => $checkedRoles, 'permissions' => $checkedPermissions]];
        }

    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $role
     */
    public function attachRole($role)
    {
        $currentApplication =  null;
        if(is_object($role)) {
            $role = $role->getKey();
        }

        if(is_array($role)) {
            $role = $role['id'];
        }
        if (StoneApplication::getCurrentApplicationId() != null) {
            $currentApplication = StoneApplication::getCurrentApplicationId();
        }
        $this->roles()->attach($role, ['application_id' => $currentApplication]);
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $role
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
    }

    /**
     * Attach multiple roles to a user
     *
     * @param mixed $roles
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles from a user
     *
     * @param mixed $roles
     */
    public function detachRoles($roles=null)
    {
        if (!$roles) $roles = $this->roles()->get();

        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    /**
     *Filtering users according to their role
     *
     *@param string $role
     *@return users collection
     */
    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', function ($query) use ($role)
        {
            $query->where('name', $role);
        });
    }

}
