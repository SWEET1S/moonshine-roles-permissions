<?php

namespace Sweet1s\MoonshineRolesPermissions\FormComponents;

use MoonShine\FormComponents\FormComponent;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolePermissionsFormComponent extends FormComponent
{
    protected array $permissions;
    protected static string $view = 'moonshine-roles-permissions::form-components.permissions';

    public function afterMake(): void
    {
        $this->permissions = Permission::all()->pluck('name')->toArray();
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

    public function existHasPermission(Role $item, $permission)
    {
        return in_array($permission, $this->getPermissions()) ? $item->hasPermissionTo($permission) : false;
    }

    public function existPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function hasPermission($permission)
    {
        return $this->existPermission($permission) ? auth()?->user()?->role?->hasPermissionTo($permission) : false;
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

    public function hasAnyPermission(array $permissions){
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
