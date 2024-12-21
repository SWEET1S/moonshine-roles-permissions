<?php

declare(strict_types=1);

namespace Sweet1s\MoonshineRBAC\Resource;

use App\Models\Role;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use Sweet1s\MoonshineRBAC\Traits\WithPermissionsFormComponent;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;

class RoleResource extends ModelResource
{
    use WithRolePermissions;
    use WithPermissionsFormComponent;

    protected string $model = Role::class;

    protected string $column = 'name';

    public function getTitle(): string
    {
        return trans('moonshine::ui.resource.role');
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make(trans('moonshine::ui.resource.role_name'), 'name')
                    ->required(),
            ])
        ];
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make(trans('moonshine::ui.resource.role_name'), 'name'),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'name' => 'required|min:5',
        ];
    }

    protected function search(): array
    {
        return ['id', 'name'];
    }

    protected function filters(): array
    {
        return [
            Text::make(trans('moonshine::ui.resource.role_name'), 'name'),
        ];
    }
}
