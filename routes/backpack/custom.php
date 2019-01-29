<?php

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    CRUD::resource('setting', 'SettingCrudController');
    CRUD::resource('attribute', 'AttributeCrudController');
    CRUD::resource('attribute-set', 'AttributeSetCrudController');
    CRUD::resource('event', 'EventCrudController');
}); // this should be the absolute last line of this file
