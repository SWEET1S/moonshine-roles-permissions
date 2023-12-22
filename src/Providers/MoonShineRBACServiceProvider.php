<?php

namespace Sweet1s\MoonshineRBAC\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Resources\ResourceContract;
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

        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'moonshine-rbac');

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        $this->publishes([
            __DIR__ . '/../../lang' => resource_path('lang/vendor/moonshine-rbac'),
        ], 'moonshine-rbac-lang');

        moonshine()->defineAuthorization(
            static function (ResourceContract $resource, Model $user, string $ability): bool {

                $hasRolePermissionsTrait = in_array(
                    WithRolePermissions::class,
                    class_uses_recursive($resource),
                    true
                );

                if (!$hasRolePermissionsTrait) {
                    return true;
                }

                $hasPermission = false;

                foreach ($user->roles as $role) {
                    $hasPermission = $role->isHavePermission(
                        class_basename($resource::class),
                        $ability
                    );

                    if ($hasPermission) {
                        break;
                    }
                }

                return $hasPermission;
            }
        );
    }
}
