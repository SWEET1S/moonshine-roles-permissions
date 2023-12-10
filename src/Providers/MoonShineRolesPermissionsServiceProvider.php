<?php

namespace MoonshineRBAC\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MoonshineRBAC\Abilities;
use MoonshineRBAC\Commands\MoonShineRBACAssignPermissionCommand;
use MoonshineRBAC\Commands\MoonShineRBACCreatePermissionsResourceCommand;
use MoonshineRBAC\Commands\MoonShineRBACInstallCommand;
use MoonshineRBAC\Commands\MoonShineRBACResourceCommand;
use MoonshineRBAC\Commands\MoonShineRBACRoleCreateCommand;
use MoonshineRBAC\Commands\MoonShineRBACUserCommand;

final class MoonShineRolesPermissionsServiceProvider extends ServiceProvider
{

    protected array $commands = [
        MoonShineRBACAssignPermissionCommand::class,
        MoonShineRBACInstallCommand::class,
        MoonShineRBACRoleCreateCommand::class,
        MoonShineRBACUserCommand::class,
        MoonShineRBACResourceCommand::class,
        MoonShineRBACCreatePermissionsResourceCommand::class
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/moonshine-rbac.php');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-rbac');

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
