<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MoonShineRolesPermissionsRoleCreateCommand extends MoonShineRolesPermissionsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:role {name} {--all : Assign all existing permissions to the role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a role';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->argument('name');

        $role = Role::updateOrCreate([
            'name' => $name,
            'guard_name' => config('moonshine.auth.guard')
        ]);

        if($this->option('all')) {
            $permissions = Permission::all()->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        }

        $this->info("Role $name created successfully");

        return 0;
    }


}
