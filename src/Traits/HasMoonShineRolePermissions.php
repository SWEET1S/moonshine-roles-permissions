<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use Spatie\Permission\Exceptions\PermissionDoesNotExist;

trait HasMoonShineRolePermissions
{
    public function isHavePermission(string $resourceClass, string $ability): bool
    {
        $permission = $resourceClass . '.' . $ability;

        $hasPermission = false;

        try {

            $hasPermission = $this->hasPermissionTo($permission);

        } catch (PermissionDoesNotExist $e) {

            $this->createPermissionIfNotExists($permission);

        }

        if (config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID == $this->id) {
            return true;
        }

        return $hasPermission;
    }

    private function createPermissionIfNotExists(string $permission): void
    {
        config('permission.models.permission')::updateOrCreate([
            'name' => $permission,
            'guard_name' => config('moonshine.auth.guard')
        ]);
    }
}
