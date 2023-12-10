<?php

namespace Sweet1s\MoonshineRBAC\Traits;

trait HasMoonShineRolePermissions
{
    public function isHavePermission(string $resourceClass, string $ability): bool
    {
        if (config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID == $this->id) {
            return true;
        }

        $permission = $resourceClass . '.' . $ability;

        return $this->hasPermissionTo($permission);
    }
}
