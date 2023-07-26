<?php

namespace Sweet1s\MoonshineRolesPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MoonShine\MoonShineUI;
use Spatie\Permission\Models\Role;

class MoonShineRolesPermissionsController extends Controller
{
    public function attachPermissionsToRole(Request $request, Role $role){
        if($request->get('permissions') == null){
            $role->syncPermissions([]);
            MoonShineUI::toast(
                __('moonshine::ui.saved'),
                'success'
            );
            return back();
        }

        $permissions = array_keys($request->get('permissions'));
        $authUserRole = auth()?->user()?->role;

        if($authUserRole == null){
            MoonShineUI::toast(
                __('moonshine::ui.unauthorized'),
                'error'
            );
            return back();
        }

        foreach ($permissions as $permission) {
            if(!$authUserRole?->hasPermissionTo($permission)){
                MoonShineUI::toast(
                    __('moonshine::ui.unauthorized'),
                    'error'
                );
                return back();
            }
        }

        $role->syncPermissions($permissions);

        MoonShineUI::toast(
            __('moonshine::ui.saved'),
            'success'
        );

        return back();
    }
}
