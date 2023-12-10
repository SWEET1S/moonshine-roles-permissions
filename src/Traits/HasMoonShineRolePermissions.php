<?php

namespace Sweet1s\MoonshineRBAC\Traits;

trait HasMoonShineRolePermissions
{
    public function isHavePermission($permission): bool
    {
        return config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID == $this->id || $this->hasPermissionTo($permission);
    }
}
