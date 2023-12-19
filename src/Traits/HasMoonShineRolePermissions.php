<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use Spatie\Permission\Exceptions\PermissionDoesNotExist;

trait HasMoonShineRolePermissions
{
    public function getRolePriorityAttribute($value)
    {
        return $value !== null ? json_decode($value, true) : [];
    }

    public function isHavePermission(string $resourceClass, string $ability): bool
    {
        $permission = $resourceClass . '.' . $ability;

        if (config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID == $this->id) {
            return true;
        }

        try {

            return $this->hasPermissionTo($permission);

        } catch (PermissionDoesNotExist $e) {

            $this->createPermissionIfNotExists($permission);

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
