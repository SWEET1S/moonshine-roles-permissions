<?php

use Illuminate\Support\Facades\Route;
use Sweet1s\MoonshineRolesPermissions\Http\Controllers\MoonShineRolesPermissionsController;

$middlewares = collect(config('moonshine.route.middleware'))
    ->reject(static fn ($middleware): bool => $middleware === 'web')
    ->toArray();

Route::as('moonshine-roles-permissions.')->middleware($middlewares)->group(function () {

    Route::middleware([config('moonshine.auth.middleware'), 'web'])->group(function () {

        Route::post('moonshine-roles-permissions/roles/{role}/permissions', [MoonShineRolesPermissionsController::class, 'attachPermissionsToRole'])
            ->name('roles.attach-permissions-to-role');
    });
});
