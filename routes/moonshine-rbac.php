<?php

use Illuminate\Support\Facades\Route;
use MoonshineRBAC\Http\Controllers\MoonShineRBACController;

$middlewares = collect(config('moonshine.route.middleware'))
    ->reject(static fn ($middleware): bool => $middleware === 'web')
    ->toArray();

Route::as('moonshine-rbac.')->middleware($middlewares)->group(function () {

    Route::middleware([config('moonshine.auth.middleware'), 'web'])->group(function () {

        Route::post('moonshine-rbac/roles/{role}/permissions', [MoonShineRBACController::class, 'attachPermissionsToRole'])
            ->name('roles.attach-permissions-to-role');
    });
});
