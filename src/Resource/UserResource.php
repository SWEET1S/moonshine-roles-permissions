<?php

namespace Sweet1s\MoonshineRBAC\Resource;

use App\Models\User as User;
use Illuminate\Validation\Rule;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;
use Sweet1s\MoonshineRBAC\Traits\WithRoleFormComponent;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;

class UserResource extends ModelResource
{
    use WithRolePermissions;
    use WithRoleFormComponent;

    protected string $model = User::class;

    protected string $column = 'name';

    public function getTitle(): string
    {
        return trans('moonshine::ui.resource.admins_title');
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make(),

            Text::make(
                trans('moonshine::ui.resource.name'),
                'name'
            ),

            Image::make(
                trans('moonshine::ui.resource.avatar'),
                'avatar'
            )
                ->removable()
                ->disk(config('filesystems.default'))
                ->dir('moonshine_users')
                ->allowedExtensions(
                    ['jpg', 'png', 'jpeg', 'gif']
                ),

            Date::make(
                trans('moonshine::ui.resource.created_at'),
                'created_at'
            )
                ->format("d.m.Y")
                ->default(now()->toDateTimeString()),

            Email::make(
                trans('moonshine::ui.resource.email'),
                'email'
            ),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->indexFields();
    }

    protected function formFields(): iterable
    {
        return [
            Grid::make([
                Column::make([
                    Box::make(
                        trans('moonshine::ui.resource.main_information'),
                        [
                            ID::make(),

                            Text::make(
                                trans('moonshine::ui.resource.name'),
                                'name'
                            )
                                ->required(),

                            Image::make(
                                trans('moonshine::ui.resource.avatar'),
                                'avatar'
                            )
                                ->removable()
                                ->disk(config('filesystems.default'))
                                ->dir('moonshine_users')
                                ->allowedExtensions(
                                    ['jpg', 'png', 'jpeg', 'gif']
                                ),

                            Date::make(
                                trans('moonshine::ui.resource.created_at'),
                                'created_at'
                            )
                                ->format("d.m.Y")
                                ->default(now()->toDateTimeString()),

                            Email::make(
                                trans('moonshine::ui.resource.email'),
                                'email'
                            )
                                ->required(),
                        ]
                    ),

                    Box::make(
                        trans('moonshine::ui.resource.change_password'),
                        [
                            Password::make(
                                trans('moonshine::ui.resource.password'),
                                'password'
                            )
                                ->customAttributes(
                                    ['autocomplete' => 'new-password']
                                )
                                ->eye(),

                            PasswordRepeat::make(
                                trans('moonshine::ui.resource.repeat_password'),
                                'password_repeat'
                            )
                                ->customAttributes(
                                    ['autocomplete' => 'confirm-password']
                                )
                                ->eye(),
                        ]
                    ),
                ]),
            ]),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'name' => 'required',
            'email' => [
                'sometimes',
                'bail',
                'required',
                'email',
                Rule::unique('users')->ignoreModel($item),
            ],
            'password' => $item->exists
                ? 'sometimes|nullable|min:6|required_with:password_repeat|same:password_repeat'
                : 'required|min:6|required_with:password_repeat|same:password_repeat',
        ];
    }

    public function search(): array
    {
        return ['id', 'name'];
    }

    public function filters(): array
    {
        return [
            Text::make(trans('moonshine::ui.resource.name'), 'name'),
            Date::make(
                trans('moonshine::ui.resource.created_at'),
                'created_at'
            ),
        ];
    }
}
