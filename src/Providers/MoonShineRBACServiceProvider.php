<?php

namespace Sweet1s\MoonshineRBAC\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Sweet1s\MoonshineRBAC\Abilities;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACAssignPermissionCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACCreatePermissionsResourceCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACInstallCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACResourceCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACRoleCreateCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACUserCommand;

final class MoonShineRBACServiceProvider extends ServiceProvider
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
