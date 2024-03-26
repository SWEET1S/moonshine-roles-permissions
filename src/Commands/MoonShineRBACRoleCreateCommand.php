<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use function Laravel\Prompts\{intro, info, text, confirm};

class MoonShineRBACRoleCreateCommand extends MoonShineRBACCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:role {name?} {--all-permissions}';

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
        intro($this->description);

        $name = $this->argument('name') ?? text(
            label: 'The name of the role',
            placeholder: 'E.g. Manager',
            required: true,
        );

        $role = config('permission.models.role')::updateOrCreate([
            'name' => $name,
            'guard_name' => config('moonshine.auth.guard')
        ]);

        info("Role `$name` created");

        $add_permissions = !$this->option('all-permissions')
            ? confirm(
                label: 'Add all permissions for a role?',
                default: true,
            )
            : true;

        if ($add_permissions) {
            $permissions = config('permission.models.permission')::all()->pluck('name')->toArray();
            $role->syncPermissions($permissions);
            info('Permissions are linked to a role.');
        }

        return self::SUCCESS;
    }
}
