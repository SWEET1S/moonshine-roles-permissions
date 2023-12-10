<?php

namespace Sweet1s\MoonshineRBAC\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\MoonShine;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACAssignPermissionCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACCreatePermissionsResourceCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACInstallCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACResourceCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACRoleCreateCommand;
use Sweet1s\MoonshineRBAC\Commands\MoonShineRBACUserCommand;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;

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

        MoonShine::defineAuthorization(
            static function (ResourceContract $resource, Model $user, string $ability): bool {

                $hasRolePermissions = in_array(
                    WithRolePermissions::class,
                    class_uses_recursive($resource),
                    true
                );

                if (!$hasRolePermissions) {
                    return true;
                }

                return $user?->role->isHavePermission(
                    class_basename($resource::class),
                    $ability
                );
            }
        );
    }
}
