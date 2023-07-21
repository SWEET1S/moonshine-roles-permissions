<?php

namespace Sweet1s\MoonshineRolesPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MoonShine\MoonShineUI;
use Spatie\Permission\Models\Role;

class MoonShineRolesPermissionsController extends Controller
{
    public function attachPermissionsToRole(Request $request, $roleID){
        $role = Role::where('id', $roleID)->first();

        $permissions = array_keys($request->get('permissions'));
        $role->syncPermissions($permissions);

        MoonShineUI::toast(
            __('moonshine::ui.saved'),
            'success'
        );

        return back();
    }
}
