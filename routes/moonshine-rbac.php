<?php

use Illuminate\Support\Facades\Route;
use Sweet1s\MoonshineRBAC\Http\Controllers\MoonShineRBACController;

Route::moonshine(function () {
    Route::as('moonshine-rbac.')->controller(MoonShineRBACController::class)->group(function () {
        Route::post('moonshine-rbac/role/{role}/permissions/sync', 'attachPermissionsToRole')->name('roles.attach-permissions-to-role');
        Route::post('moonshine-rbac/user/{user}/roles/sync', 'attachRolesToUser')->name('roles.attach-roles-to-user');
    });
});


