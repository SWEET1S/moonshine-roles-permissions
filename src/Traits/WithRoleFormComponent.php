<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use MoonShine\Support\Enums\Layer;
use Sweet1s\MoonshineRBAC\FormComponents\RoleFormComponent;

trait WithRoleFormComponent
{
    protected function loadWithRoleFormComponent(): void
    {
        $this->getFormPage()?->pushToLayer(
            layer: Layer::BOTTOM,
            component: RoleFormComponent::make(
                label: trans('moonshine-rbac::ui.roles'),
                resource: $this,
            )
        );
    }
}
