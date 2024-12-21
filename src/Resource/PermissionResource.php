<?php

declare(strict_types=1);

namespace Sweet1s\MoonshineRBAC\Resource;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Sweet1s\MoonshineRBAC\Abilities;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;

class PermissionResource extends ModelResource
{
    use WithRolePermissions;

    public function __construct(
        CoreContract $core
    )
    {
        parent::__construct($core);

        $this->model = config('permission.models.permission');
    }

    public function getTitle(): string
    {
        return trans('moonshine-rbac::ui.permissions') ?? 'Permissions';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function formFields(): iterable
    {
        $guards = [];

        collect(array_keys(config('auth.guards')))->map(function ($guard) use (&$guards) {
            $guards[$guard] = $guard;
        });

        $abilities = [];

        foreach (Abilities::getAbilities() as $ability) {
            $abilities[$ability] = $ability;
        }

        $resources = [];

        foreach (moonshine()->getResources() as $resource) {
            $resources['Resources'][class_basename($resource)] = class_basename($resource);
        }

        $resources['Custom Permission']['Custom'] = 'Custom';

        $permissionNameArray = $this->getItem()?->name ? explode('.', $this->getItem()->name) : '';

        return [
            Box::make([
                ID::make(),
                Text::make('Permission Name', 'permission_name')
                    ->customAttributes([
                        'class' => 'permission_name',
                        'x-init' => isset($permissionNameArray[0]) && $permissionNameArray[0] !== 'Custom'
                            ? $this->hideElement('.permission_name')
                            : (isset($permissionNameArray[0]) ? $this->showElement('.permission_name') : $this->hideElement('.permission_name'))
                    ])
                    ->canApply(fn() => false)
                    ->default($permissionNameArray[1] ?? ''),
                Select::make('Resource', 'resource')
                    ->customAttributes([
                        '@change' => "(event) => {
                            event.target.value !== 'Custom'
                            ? {$this->showElement('.ability')}
                            : {$this->hideElement('.ability')};

                            event.target.value === 'Custom'
                            ? {$this->showElement('.permission_name')}
                            : {$this->hideElement('.permission_name')}
                        }",
                    ])
                    ->searchable()
                    ->default($permissionNameArray[0] ?? '')
                    ->canApply(fn() => false)
                    ->options($resources),
                Select::make('Ability', 'ability')
                    ->customAttributes([
                        'class' => 'ability',
                        'x-init' => isset($permissionNameArray[0]) && $permissionNameArray[0] === 'Custom'
                            ? $this->hideElement('.ability')
                            : (isset($permissionNameArray[0]) ? $this->showElement('.ability') : $this->hideElement('.ability'))
                    ])
                    ->searchable()
                    ->default($permissionNameArray[1] ?? '')
                    ->canApply(fn() => false)
                    ->options($abilities),

                Select::make('Guard', 'guard_name')
                    ->searchable()
                    ->options($guards),
            ]),
        ];
    }

    protected function indexFields(): iterable
    {
        $guards = [];

        collect(array_keys(config('auth.guards')))->map(function ($guard) use (&$guards) {
            $guards[$guard] = $guard;
        });

        return [
            ID::make()->sortable(),
            Text::make('Name'),
            Select::make('Guard', 'guard_name')
                ->searchable()
                ->options($guards),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    public function search(): array
    {
        return [
            'name'
        ];
    }

    protected function beforeCreating(mixed $item): mixed
    {
        $item->name = moonshineRequest()->get('resource') . '.' . moonshineRequest()->get('ability');

        if (moonshineRequest()->has('resource') && moonshineRequest()->get('resource') == 'Custom') {
            $item->name = 'Custom.' . moonshineRequest()->get('permission_name');
        }


        return $item;
    }

    protected function beforeUpdating(mixed $item): mixed
    {
        return $this->beforeCreating($item);
    }

    public function rules(mixed $item): array
    {
        return [];
    }

    private function hideElement(string $class): string
    {
        return "document.querySelector('$class').closest('.moonshine-field').setAttribute('style', 'display: none !important;')";
    }

    private function showElement(string $class): string
    {
        return "document.querySelector('$class').closest('.moonshine-field').setAttribute('style', 'display: block !important;')";
    }
}
