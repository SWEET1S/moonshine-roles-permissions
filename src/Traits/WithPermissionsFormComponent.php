<?php

namespace Sweet1s\MoonshineRBAC\Traits;

use MoonShine\Enums\Layer;
use MoonShine\Enums\PageType;

trait WithPermissionsFormComponent
{
    protected function bootWithPermissionsFormComponent(): void
    {
        $this->getPages()
            ->findByUri(PageType::FORM->value)
            ->pushToLayer(
                layer: Layer::BOTTOM,
                component: Permissions::make(
                    label: 'Permissions',
                    resource: $this,
                )
            );
    }
}
