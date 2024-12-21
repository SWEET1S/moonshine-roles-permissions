<?php

namespace Sweet1s\MoonshineRBAC\Components;

use MoonShine\MenuManager\MenuElement;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use MoonShine\Laravel\MoonShineAuth;
use MoonShine\Laravel\Resources\ModelResource;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;

class MenuRBAC
{
    /**
     * This method is used to create menu items with permissions, if the user does not have permission to view the menu item, it will not be displayed
     *
     * @param MenuElement ...$items
     * @return MenuElement[]
     */
    public static function menu(MenuElement...$items): array
    {
        $self = new self();

        foreach ($items as $item) {
            $self->checkPermission($item);

            if ($item instanceof MenuGroup) {
                $self->checkChildren($item);
            }
        }

        return $items;
    }

    /**
     * @param MenuElement $item
     * @return void
     */
    private function checkPermission(MenuElement $item): void
    {
        if (method_exists($item, 'items')) {
            foreach ($item->items() as $item) {
                $this->checkPermission($item);
            }
        }

        if (!$item instanceof MenuItem || !$item?->getFiller() instanceof ModelResource) {
            return;
        }

        $resource = $item->getFiller();
        $hasRolePermissionsTrait = in_array(
            WithRolePermissions::class,
            class_uses_recursive($resource),
            true
        );

        if ($hasRolePermissionsTrait) {
            $item->canSee(function () use ($resource) {
                return $this->userHasViewAnyPermission($resource);
            });
        }

    }

    /**
     * @param ModelResource $resource
     * @return bool
     */
    private function userHasViewAnyPermission(ModelResource $resource): bool
    {
        $user = MoonShineAuth::getGuard()->user();

        foreach ($user->roles as $role) {
            if ($role->isHavePermission(class_basename($resource::class), 'viewAny')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param MenuElement $item
     * @return void
     */
    private function checkChildren(MenuElement $item): void
    {
        if (!$item instanceof MenuGroup || !method_exists($item, 'items')) {
            return;
        }

        $item->canSee(function () use ($item) {
            if (
                $item->items()->count() == 0 || ($item->items()->count() == 1 && !$item->items()?->first() instanceof MenuItem)
            ) {
                return false;
            }

            return true;
        });
    }
}
