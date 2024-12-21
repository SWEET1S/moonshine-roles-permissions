<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use MoonShine\Laravel\Enums\Ability;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

trait HasMoonShineRolePermissions
{
    public function getRolePriorityAttribute($value)
    {
        return $value !== null ? json_decode($value, true) : [];
    }

    /**
     * @param string|null $resourceClass
     * @param string|null $ability
     * @param string|null $permission
     *
     * @return bool
     */
    public function isHavePermission(string $resourceClass = null, string|Ability|null $ability = null, string $permission = null): bool
    {
        if($ability instanceof Ability) {
            $ability = $ability->value;
        }

        $currentPermission = $resourceClass . '.' . $ability;

        if ($permission != null) {
            $currentPermission = $permission;
        }

        if (config('moonshine.auth.model')::SUPER_ADMIN_ROLE_ID == $this->id) {
            return true;
        }

        try {

            return $this->hasPermissionTo($currentPermission);

        } catch (PermissionDoesNotExist $e) {

            $this->createPermissionIfNotExists($currentPermission);

            return false;
        }
    }

    private function createPermissionIfNotExists(string $permission): void
    {
        config('permission.models.permission')::updateOrCreate([
            'name' => $permission,
            'guard_name' => config('moonshine.auth.guard')
        ]);
    }
}
