<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Console\Command;

class MoonShineRolesPermissionsAssignPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:assign {permission : The name of the permission} {guard? : The name of the guard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign permission to role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $permission = $this->argument('permission');

        if (!config('permission.models.permission')::where('name', $permission)->exists()) {
            $this->error("Permission {$permission} does not exist");

            $create = $this->confirm('Do you want to create it?');
            if ($create) {

                config('permission.models.permission')::create([
                    'name' => $permission,
                    'guard_name' => $this->argument('guard') ?? config('moonshine.auth.guard')
                ]);

                $this->info("Permission {$permission} is created");
            } else {
                return 1;
            }
        }

        app()['cache']->forget('spatie.permission.cache');

        $roles = config('permission.models.role')::all()->pluck('name')->toArray();
        $role = $this->choice('Select role', $roles, 0);

        if ($role) {
            $role = config('permission.models.role')::where('name', $role)->first();
            $role->givePermissionTo($permission);
            $this->info("Permission {$permission} is assigned to role {$role->name}");
        } else {
            $this->error('Role is required');
        }

        return 0;
    }


}
