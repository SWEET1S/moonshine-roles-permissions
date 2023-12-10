<?php

declare(strict_types=1);

namespace Sweet1s\MoonshineRBAC\Resource;

use App\Models\Role;
use MoonShine\Decorations\Block;
use MoonShine\Enums\Layer;
use MoonShine\Enums\PageType;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Resources\ModelResource;
use Sweet1s\MoonshineRBAC\FormComponents\RolePermissionsFormComponent;

class RoleResource extends ModelResource
{
    public string $model = Role::class;

    public string $titleField = 'name';

    public bool $withPolicy = true;

    public function title(): string
    {
        return trans('moonshine::ui.resource.role');
    }

    public function fields(): array
    {
        return [
            Block::make('', [
                ID::make()->sortable()->showOnExport(),
                Text::make(trans('moonshine::ui.resource.role_name'), 'name')
                    ->required()->showOnExport(),
            ])
        ];
    }

    public function rules($item): array
    {
        return [
            'name' => 'required|min:5',
        ];
    }

    public function search(): array
    {
        return ['id', 'name'];
    }

    public function filters(): array
    {
        return [
            Text::make(trans('moonshine::ui.resource.role_name'), 'name'),
        ];
    }

    protected function onBoot(): void
    {
        parent::onBoot();

        $this->getPages()
            ->findByUri(PageType::FORM->value)
            ->pushToLayer(
                layer: Layer::BOTTOM,
                component: RolePermissionsFormComponent::make(
                    'Permissions',
                    $this,
                )
            );
    }
}
