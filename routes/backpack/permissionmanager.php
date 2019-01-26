<?php

Route::group([
    'namespace'  => 'Backpack\PermissionManager\app\Http\Controllers',
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    CRUD::resource('permission', 'PermissionCrudController');
    CRUD::resource('role', 'RoleCrudController');
});

Route::group([
    'namespace'  => 'App\Http\Controllers\Admin',
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    CRUD::resource('user', 'UserCrudController');
});

