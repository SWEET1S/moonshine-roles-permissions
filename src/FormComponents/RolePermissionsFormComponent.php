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

final class RolePermissionsFormComponent extends MoonShineComponent
{
    protected string $view = 'moonshine-rbac::form-components.permissions';

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
        $currentUser = MoonShineAuth::guard()->user();

        $elements = [];
        $values = [];
        $all = true;

        foreach (moonshine()->getResources() as $resource) {
            $checkboxes = [];
            $class = class_basename($resource::class);
            $allSections = true;

            foreach ($resource->gateAbilities() as $ability) {

                if (!$currentUser->role->isHavePermission($class, $ability)) {
                    continue;
                }

                $values['permissions'][$class][$ability] = $this->getItem()?->isHavePermission(
                    $class,
                    $ability
                );

                if (!$values['permissions'][$class][$ability]) {
                    $allSections = false;
                    $all = false;
                }

                $checkboxes[] = Switcher::make(
                    $ability,
                    "permissions." . $class . ".$ability"
                )
                    ->customAttributes(['class' => 'permission_switcher ' . $class])
                    ->setName("permissions[" . $class . "][$ability]");
            }

            $elements[] = Column::make([
                Switcher::make($resource->title())->customAttributes([
                    'class' => 'permission_switcher_section',
                    '@change' => "document
                          .querySelectorAll('.$class')
                          .forEach((el) => {el.checked = parseInt(event.target.value); el.dispatchEvent(new Event('change'))})",
                ])->setValue($allSections)->hint('Toggle off/on all'),

                ...$checkboxes,
                Divider::make(),
            ])->columnSpan(6);
        }

        return FormBuilder::make(route('moonshine-rbac.roles.attach-permissions-to-role', $this->getItem()->getKey()))
            ->fields([
                Switcher::make('All')->customAttributes([
                    '@change' => <<<'JS'
                        document
                          .querySelectorAll('.permission_switcher, .permission_switcher_section')
                          .forEach((el) => {el.checked = parseInt(event.target.value); el.dispatchEvent(new Event('change'))})
                    JS,
                ])->setValue($all),
                Divider::make(),
                Grid::make(
                    $elements
                ),
            ])
            ->fill($values)
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
