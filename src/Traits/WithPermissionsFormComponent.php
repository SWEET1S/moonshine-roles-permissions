<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use MoonShine\Enums\Layer;
use MoonShine\Enums\PageType;
use Sweet1s\MoonshineRBAC\FormComponents\RolePermissionsFormComponent;

trait WithPermissionsFormComponent
{
    protected function bootWithPermissionsFormComponent(): void
    {
        $this->getPages()
            ->findByUri(PageType::FORM->value)
            ->pushToLayer(
                layer: Layer::BOTTOM,
                component: RolePermissionsFormComponent::make(
                    label: trans('moonshine-rbac::ui.permissions'),
                    resource: $this,
                )
            );
    }
}
