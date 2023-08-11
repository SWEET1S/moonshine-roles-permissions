<?php

namespace Sweet1s\MoonshineRolesPermissions\FormComponents;

use MoonShine\FormComponents\FormComponent;

final class RolePermissionsFormComponent extends FormComponent
{
    protected array $permissions;
    protected static string $view = 'moonshine-roles-permissions::form-components.permissions';

    public function afterMake(): void
    {
        $this->permissions = config('permission.models.permission')::all()->pluck('name')->toArray();
    }

    public function isSuperAdminRole($role)
    {
        return $role->id == config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function existsPermissions(string $resourceName)
    {
        return strpos(implode(' ', $this->getPermissions()), $resourceName);
    }

    public function getPermissionName(string $resourceName, string $ability): string
    {
        return $resourceName . "." . $ability;
    }

    public function existPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function existHasPermission($item, $permission): bool
    {
        return $this->existPermission($permission) && (($this->isSuperAdminRole($item) || $item?->hasPermissionTo($permission)));
    }

    public function hasPermission($permission): bool
    {
        return $this->existPermission($permission) && (auth()?->user()?->role?->hasPermissionTo($permission) || $this->isSuperAdminRole(auth()?->user()?->role));
    }

    public function hasAnyResourcePermissions($resource): bool
    {
        $notHavePermission = 0;
        $resourceName = class_basename($resource);

        foreach ($resource->gateAbilities() as $ability) {
            if (!$this->hasPermission($this->getPermissionName($resourceName, $ability))) {
                $notHavePermission++;
            }

            if ($notHavePermission == count($resource->gateAbilities())) {
                return false;
            }
        }

        return true;
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach($permissions as $permission){
            if($this->hasPermission($permission)){
                return true;
            }
        }

        return false;
    }

    public function getCustomPermissions($resources): array
    {
        $customPermissions = $this->permissions;

        foreach ($resources as $resource) {
            $resourceName = class_basename($resource);

            foreach ($customPermissions as $custom) {
                if (strpos($custom, $resourceName) !== false) {
                    unset($customPermissions[array_search($custom, $customPermissions)]);
                }
            }
        }

        return $customPermissions;
    }
}
