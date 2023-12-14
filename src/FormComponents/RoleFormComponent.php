<?php

namespace Sweet1s\MoonshineRBAC\FormComponents;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Switcher;
use MoonShine\MoonShineAuth;
use MoonShine\Resources\ModelResource;
use MoonShine\Traits\HasResource;
use MoonShine\Traits\WithLabel;

final class RoleFormComponent extends MoonShineComponent
{
    protected string $view = 'moonshine-rbac::form-components.role';

    use HasResource;
    use WithLabel;

    protected $except = [
        'getItem',
        'getResource',
        'getForm',
    ];

    public function __construct(
        Closure|string $label,
        ModelResource  $resource
    )
    {
        $this->setResource($resource);
        $this->setLabel($label);
    }

    public function getItem(): Model
    {
        return $this->getResource()->getItemOrInstance();
    }

    public function getForm(): FormBuilder
    {

        return FormBuilder::make()
            ->fields([

            ])
            ->fill()
            ->submit(__('moonshine::ui.save'));
    }

    protected function viewData(): array
    {
        return [
            'label' => $this->label(),
            'form' => $this->getItem()?->exists
                ? $this->getForm()
                : '',
            'item' => $this->getItem(),
            'resource' => $this->getResource(),
        ];
    }
}
