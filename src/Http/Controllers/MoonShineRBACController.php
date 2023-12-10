<?php

namespace MoonshineRBAC\Http\Controllers;

use Illuminate\Http\Request;
use MoonShine\Http\Controllers\MoonShineController;
use MoonShine\MoonShineUI;
use Spatie\Permission\Models\Role;

class MoonShineRBACController extends MoonShineController
{
    public function attachPermissionsToRole(Request $request, Role $role)
    {

        if ($request->get('permissions') == null) {
            $role->syncPermissions([]);

            MoonShineUI::toast(
                trans('moonshine::ui.saved'),
                'success'
            );
            return back();
        }

        $authUserRole = auth()?->user()?->role;

        if ($authUserRole == null) {
            MoonShineUI::toast(
                trans('moonshine::ui.unauthorized'),
                'error'
            );
            return back();
        }

        $permissions = [];

        foreach ($request->get('permissions') as $resource => $abilities) {

            foreach ($abilities as $ability => $value) {

                if ($value == '1') {
                    $permissions[] = $resource . '.' . $ability;
                }
            }
        }

        foreach ($permissions as $permission) {
            if (!(config('moonshine.auth.providers.moonshine.model')::SUPER_ADMIN_ROLE_ID == $authUserRole->id) && !$authUserRole?->hasPermissionTo($permission)) {
                MoonShineUI::toast(
                    trans('moonshine::ui.unauthorized'),
                    'error'
                );

                return back();
            }
        }

        $role->syncPermissions($permissions);

        MoonShineUI::toast(
            trans('moonshine::ui.saved'),
            'success'
        );

        return back();
    }
}
