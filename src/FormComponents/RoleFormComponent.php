<?php

namespace Sweet1s\MoonshineRBAC\FormComponents;

use Closure;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Core\Traits\HasResource;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\MoonShineComponent;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Select;
use MoonShine\Laravel\MoonShineAuth;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Traits\WithLabel;

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
        parent::__construct();

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

        return FormBuilder::make(route('moonshine.moonshine-rbac.roles.attach-roles-to-user', $this->getItem()))
            ->fields([
                Box::make([
                    Select::make(trans('moonshine::ui.resource.role'))
                        ->options($roles)
                        ->searchable()
                        ->default($this->getItem()->roles->pluck('id')->toArray())
                        ->setNameAttribute('roles[]')
                        ->multiple()
                ])
            ])
            ->fill()
            ->submit(__('moonshine::ui.save'));
    }

    public function getRoles(): array
    {
        $user = MoonShineAuth::getGuard()->user();
        $superAdminRoleID = config('moonshine.auth.model')::SUPER_ADMIN_ROLE_ID;

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
            'label' => $this->getLabel(),
            'form' => $this->getItem()?->exists
                ? $this->getForm()
                : '',
            'item' => $this->getItem(),
            'resource' => $this->getResource(),
        ];
    }
}
