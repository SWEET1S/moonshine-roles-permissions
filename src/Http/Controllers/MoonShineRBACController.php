<?php

namespace Sweet1s\MoonshineRBAC\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MoonShine\Crud\Contracts\Notifications\MoonShineNotificationContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use MoonShine\Laravel\MoonShineAuth;
use MoonShine\Support\Enums\ToastType;
use Spatie\Permission\Models\Role;

class MoonShineRBACController extends MoonShineController
{
    private int $superAdminRoleId;

    public function __construct(
        protected MoonShineNotificationContract $notification,
    )
    {
        parent::__construct($notification);

        $this->superAdminRoleId = config('moonshine.auth.model')::SUPER_ADMIN_ROLE_ID;
    }

    // TODO: Consider proper type-hinting for $role parameter
    public function attachPermissionsToRole(Request $request, $role)
    {
        $role = config('permission.models.role')::findOrFail($role);
        if ($request->get('permissions') == null) {
            $role->syncPermissions([]);

            toast(
                trans('moonshine::ui.saved'),
                ToastType::SUCCESS
            );
            return back();
        }

        $authUserRoles = MoonShineAuth::getGuard()->user()?->roles;

        if ($authUserRoles->isEmpty()) {

            toast(
                trans('moonshine-rbac::ui.unauthorized'),
                ToastType::ERROR
            );

            Log::error('[MoonShineRBACController] attachPermissionsToRole: Your account has no role');

            return back();
        }

        if (!(in_array($this->superAdminRoleId, $authUserRoles->pluck('id')->toArray()))) {
            toast(
                trans('moonshine-rbac::ui.unauthorized'),
                ToastType::ERROR
            );

            Log::error('[MoonShineRBACController] attachPermissionsToRole: You cannot edit permissions of Super Admin role');

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

            foreach ($authUserRoles as $userRole) {

                if ($userRole->isHavePermission(permission: $permission)) {
                    continue;
                }

                toast(
                    trans('moonshine-rbac::ui.unauthorized'),
                    ToastType::ERROR
                );

                Log::error('[MoonShineRBACController] attachPermissionsToRole: User has no permission to ' . $permission);

                return back();
            }

        }

        $role->syncPermissions($permissions);

        if ($request->has('role_priority')) {
            $role->role_priority = json_encode($request->get('role_priority'), JSON_UNESCAPED_UNICODE);
            $role->save();
        } else {
            $role->role_priority = null;
            $role->save();
        }

        toast(
            trans('moonshine::ui.saved'),
            ToastType::SUCCESS
        );

        return back();
    }

    public function attachRolesToUser(Request $request, $user)
    {
        $user = config('moonshine.auth.model')::findOrFail($user);

        if (in_array($this->superAdminRoleId, $user?->roles->pluck('id')->toArray())) {
            toast(
                trans('moonshine-rbac::ui.unauthorized'),
                ToastType::ERROR
            );

            return back();
        }

        $authenticatedUser = MoonShineAuth::getGuard()->user();

        if (!$this->hasPermissionsToSyncRoles($authenticatedUser, $user, $request)) {

            toast(
                trans('moonshine-rbac::ui.unauthorized'),
                ToastType::ERROR
            );

            return back();
        }

        $roles = config('permission.models.role')::whereIn('id', (array)$request->get('roles'))->get()->pluck('name')->toArray();

        $user->syncRoles($roles);

        toast(
            trans('moonshine::ui.saved'),
            ToastType::SUCCESS
        );

        return back();
    }

    private function hasPermissionsToSyncRoles($authenticatedUser, $user, $request): bool
    {
        if (in_array($this->superAdminRoleId, $authenticatedUser?->roles->pluck('id')->toArray())) {
            return true;
        }

        if (!$request->has('roles')) {
            foreach ($user?->roles->pluck('role_priority')->toArray() as $rolePriority) {
                if (count(array_intersect((array)$rolePriority, $authenticatedUser?->roles->pluck('id')->toArray() ?? [])) > 0) {
                    return true;
                }
            }

            return false;
        }

        foreach ($authenticatedUser?->roles->pluck('role_priority')->toArray() as $rolePriority) {
            if (count(array_intersect((array)$rolePriority, $request->get('roles') ?? [])) > 0) {
                return true;
            }
        }

        return false;
    }
}
