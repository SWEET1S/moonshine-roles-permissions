<?php

use Illuminate\Support\Facades\Route;
use Sweet1s\MoonshineRolesPermissions\Http\Controllers\MoonShineRolesPermissionsController;


Route::post('moonshine-roles-permissions/roles/{role}/permissions', [MoonShineRolesPermissionsController::class, 'attachPermissionsToRole'])
    ->name('moonshine-roles-permissions.roles.attach-permissions-to-role');
