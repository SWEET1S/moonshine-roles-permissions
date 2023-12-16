<?php

use Illuminate\Support\Facades\Route;
use Sweet1s\MoonshineRBAC\Http\Controllers\MoonShineRBACController;

$middlewares = collect(config('moonshine.route.middleware'))
    ->reject(static fn($middleware): bool => $middleware === 'web')
    ->toArray();

Route::as('moonshine-rbac.')->middleware($middlewares)->group(function () {

    Route::middleware([config('moonshine.auth.middleware'), 'web'])->controller(MoonShineRBACController::class)->group(function () {

        Route::post('moonshine-rbac/role/{role}/permissions/sync', 'attachPermissionsToRole')
            ->name('roles.attach-permissions-to-role');

        Route::post('moonshine-rbac/user/{user}/roles/sync', 'attachRolesToUser')
            ->name('roles.attach-roles-to-user');
    });
});
