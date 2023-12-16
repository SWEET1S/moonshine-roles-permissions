<?php

namespace Sweet1s\MoonshineRBAC\FormComponents;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Select;
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
        $roles = $this->getRoles();

        return FormBuilder::make(route('moonshine-rbac.roles.attach-roles-to-user', $this->getItem()))
            ->fields([
                Block::make([
                    Select::make(trans('moonshine::ui.resource.role'))
                        ->options($roles)
                        ->searchable()
                        ->default($this->getItem()->roles->pluck('id')->toArray())
                        ->setName('roles[]')
                        ->multiple()
                ])
            ])
            ->fill()
            ->submit(__('moonshine::ui.save'));
    }

    public function getRoles(): array
    {
        $user = MoonShineAuth::guard()->user();
        $superAdminRoleID = config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID;

        if (in_array($superAdminRoleID, $user?->roles->pluck('id')->toArray())) {
            return config('permission.models.role')::where('id', '!=', $superAdminRoleID)
                ->get()
                ->pluck('name', 'id')
                ->toArray();
        }

        if ($user?->roles->pluck('role_priority')->count() > 0) {

            $rolesPriority = $user?->roles->pluck('role_priority')->toArray();

            return config('permission.models.role')::whereIn('id', array_unique(array_merge(...$rolesPriority)) ?? [])->get()->pluck('name', 'id')->toArray();
        }

        return [];
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
