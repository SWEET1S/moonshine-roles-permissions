<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use MoonShine\Enums\Layer;
use MoonShine\Enums\PageType;
use Sweet1s\MoonshineRBAC\FormComponents\RoleFormComponent;

trait WithRoleFormComponent
{
    protected function bootWithRoleFormComponent(): void
    {
        $this->getPages()
            ->findByUri(PageType::FORM->value)
            ->pushToLayer(
                layer: Layer::BOTTOM,
                component: RoleFormComponent::make(
                    label: trans('moonshine-rbac::ui.roles'),
                    resource: $this,
                )
            );
    }
}
