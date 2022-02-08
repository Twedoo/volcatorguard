<?php

/**
 * This file is part of StoneGuard,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Twedoo\StoneGuard
 */

return [

    /*
    |--------------------------------------------------------------------------
    | StoneGuard Role Model
    |--------------------------------------------------------------------------
    |
    | This is the Role model used by StoneGuard to create correct relations.  Update
    | the role if it is in a different namespace.
    |
    */
    'role' => 'Twedoo\Stone\Models\Role',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard Roles Table
    |--------------------------------------------------------------------------
    |
    | This is the roles table used by StoneGuard to save roles to the database.
    |
    */
    'roles_table' => 'roles',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard role foreign key
    |--------------------------------------------------------------------------
    |
    | This is the role foreign key used by StoneGuard to make a proper
    | relation between permissions and roles & roles and users
    |
    */
    'role_foreign_key' => 'role_id',

    /*
    |--------------------------------------------------------------------------
    | Application User Model
    |--------------------------------------------------------------------------
    |
    | This is the User model used by StoneGuard to create correct relations.
    | Update the User if it is in a different namespace.
    |
    */
    'user' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Application Users Table
    |--------------------------------------------------------------------------
    |
    | This is the users table used by the application to save users to the
    | database.
    |
    */
    'users_table' => 'users',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard role_user Table
    |--------------------------------------------------------------------------
    |
    | This is the role_user table used by StoneGuard to save assigned roles to the
    | database.
    |
    */
    'role_user_table' => 'role_user',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard user foreign key
    |--------------------------------------------------------------------------
    |
    | This is the user foreign key used by StoneGuard to make a proper
    | relation between roles and users
    |
    */
    'user_foreign_key' => 'user_id',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard Permission Model
    |--------------------------------------------------------------------------
    |
    | This is the Permission model used by StoneGuard to create correct relations.
    | Update the permission if it is in a different namespace.
    |
    */
    'permission' => 'Twedoo\Stone\Models\Permission',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard Permissions Table
    |--------------------------------------------------------------------------
    |
    | This is the permissions table used by StoneGuard to save permissions to the
    | database.
    |
    */
    'permissions_table' => 'permissions',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by StoneGuard to save relationship
    | between permissions and roles to the database.
    |
    */
    'permission_role_table' => 'permission_role',

    /*
    |--------------------------------------------------------------------------
    | StoneGuard role_user Table
    |--------------------------------------------------------------------------
    |
    | This is the role_user table used by StoneGuard to save assigned roles to the
    | database.
    |
    */
    'role_user_table' => 'role_user',

    /*
    |--------------------------------------------------------------------------
    | User Foreign key on StoneGuard's role_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'user_foreign_key' => 'user_id',

    /*
    |--------------------------------------------------------------------------
    | Role Foreign key on StoneGuard's role_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'role_foreign_key' => 'role_id',

];
