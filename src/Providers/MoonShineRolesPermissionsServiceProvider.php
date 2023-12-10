<?php

namespace Sweet1s\MoonshineRolesPermissions\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Sweet1s\MoonshineRolesPermissions\Abilities;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsAssignPermissionCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsCreatePermissionsResourceCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsInstallCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsResourceCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsRoleCreateCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsUserCommand;

final class MoonShineRolesPermissionsServiceProvider extends ServiceProvider
{

    protected array $commands = [
        MoonShineRolesPermissionsAssignPermissionCommand::class,
        MoonShineRolesPermissionsInstallCommand::class,
        MoonShineRolesPermissionsRoleCreateCommand::class,
        MoonShineRolesPermissionsUserCommand::class,
        MoonShineRolesPermissionsAssignPermissionCommand::class,
        MoonShineRolesPermissionsResourceCommand::class,
        MoonShineRolesPermissionsCreatePermissionsResourceCommand::class
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/moonshine-roles-permissions.php');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-roles-permissions');

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        foreach (Abilities::getAbilities() as $ability) {
            Gate::define($ability, function ($user, $model) use ($ability) {
                $className = class_basename($model) . 'Resource';
                $permission = $className . '.' . $ability;

                return $user?->role?->hasPermissionTo($permission);
            });
        }
    }
}
