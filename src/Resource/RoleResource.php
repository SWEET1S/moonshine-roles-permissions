<?php

declare(strict_types=1);

namespace Sweet1s\MoonshineRolesPermissions\Resource;

use App\Models\Role;

use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Filters\TextFilter;
use MoonShine\Resources\Resource;
use Sweet1s\MoonshineRolesPermissions\FormComponents\RolePermissionsFormComponent;

class RoleResource extends Resource
{
    public static string $model = Role::class;

    public string $titleField = 'name';

    public static bool $withPolicy = true;

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
            TextFilter::make(trans('moonshine::ui.resource.role_name'), 'name'),
        ];
    }

    public function actions(): array
    {
        return [];
    }

    public function components(): array
    {
        return [
            RolePermissionsFormComponent::make('Permissions')
                ->canSee(fn($user) => auth()?->user()?->role?->id == config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID || auth()?->user()?->role?->hasPermissionTo('RoleResource.update'))
        ];
    }
}
