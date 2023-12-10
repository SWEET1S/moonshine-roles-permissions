<?php

namespace MoonshineRBAC\Commands;

class MoonShineRBACRoleCreateCommand extends MoonShineRBACCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:role {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a role with all permissions';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->argument('name');

        $role = config('permission.models.role')::updateOrCreate([
            'name' => $name,
            'guard_name' => config('moonshine.auth.guard')
        ]);

        $permissions = config('permission.models.permission')::all()->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        return 0;
    }
}
