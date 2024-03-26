<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\{intro, error, info, text, confirm, select};

class MoonShineRBACAssignPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:assign {permission? : The name of the permission} {guard? : The name of the guard}';

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
        intro($this->description);

        $permission = $this->argument('permission') ?? text(
            label: 'The name of the permission',
            placeholder: 'E.g. TestResource.view',
            required: true,
        );

        $guard = $this->argument('guard') ?? text(
            label: 'The name of the guard',
            default: config('moonshine.auth.guard')
        );

        $config_model_permission = config('permission.models.permission');

        if (!$config_model_permission::where('name', $permission)->exists()) {
            error("Permission {$permission} does not exist");

            $create = confirm(
                label: 'Do you want to create it?',
                default: true
            );

            if ($create) {
                $config_model_permission::create([
                    'name' => $permission,
                    'guard_name' => $guard
                ]);

                info("Permission {$permission} is created");
            } else {
                return self::FAILURE;
            }
        }

        app()['cache']->forget('spatie.permission.cache');

        info("Assign a {$permission} permission to a role:");

        $config_model_role = config('permission.models.role');

        $role_id = select(
            'Select role',
            $config_model_role::pluck('name', 'id'),
        );

        $role = $config_model_role::findOrFail($role_id);

        $role->givePermissionTo($permission);

        info("Permission {$permission} is assigned to role {$role->name}");

        return self::SUCCESS;
    }
}
