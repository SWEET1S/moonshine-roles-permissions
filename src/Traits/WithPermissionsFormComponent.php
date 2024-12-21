<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use MoonShine\Support\Enums\Layer;
use Sweet1s\MoonshineRBAC\FormComponents\RolePermissionsFormComponent;

trait WithPermissionsFormComponent
{
    protected function loadWithPermissionsFormComponent(): void
    {
        $this->getFormPage()?->pushToLayer(
            layer: Layer::BOTTOM,
            component: RolePermissionsFormComponent::make(
                label: trans('moonshine-rbac::ui.permissions'),
                resource: $this,
            )
        );
    }
}
