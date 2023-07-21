<?php

namespace Sweet1s\MoonshineRolesPermissions\Providers;

use Illuminate\Support\ServiceProvider;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsCreateCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsInstallCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsPolicyCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsPublishCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsRoleCreateCommand;
use Sweet1s\MoonshineRolesPermissions\Commands\MoonShineRolesPermissionsUserCommand;

final class MoonShineRolesPermissionsServiceProvider extends ServiceProvider
{

    protected array $commands = [
        MoonShineRolesPermissionsPolicyCommand::class,
        MoonShineRolesPermissionsCreateCommand::class,
        MoonShineRolesPermissionsInstallCommand::class,
        MoonShineRolesPermissionsPublishCommand::class,
        MoonShineRolesPermissionsRoleCreateCommand::class,
        MoonShineRolesPermissionsUserCommand::class
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/moonshine-roles-permissions.php');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'moonshine-roles-permissions');

        if($this->app->runningInConsole()){
            $this->commands($this->commands);
        }
    }
}
