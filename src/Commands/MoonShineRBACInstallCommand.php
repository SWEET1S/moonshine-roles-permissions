<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;

class MoonShineRBACInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install MoonShine Roles-Permissions package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {

        $this->info('Installing MoonShine Roles-Permissions package...');

        $this->migration();

        $this->call('moonshine-rbac:permissions', [
            'resourceName' => 'UserResource'
        ]);

        $this->call('moonshine-rbac:permissions', [
            'resourceName' => 'RoleResource'
        ]);

        $this->call('moonshine-rbac:init-permissions');

        $this->createRole();

        $this->info("\n");

        $this->info('MoonShine Roles-Permissions package installed successfully.');

        return 0;
    }

    public function migration(): void
    {
        $this->call('migrate', [
            '--path' => 'vendor/sweet1s/moonshine-roles-permissions/database/migrations/create_or_supplement_users_table.php'
        ]);

        $this->call('migrate', [
            '--path' => 'vendor/sweet1s/moonshine-roles-permissions/database/migrations/add_role_priority_to_roles_table.php'
        ]);
    }

    public function createRole(): void
    {
        if (config('permission.models.role')::first() == null) {
            $this->call('moonshine-rbac:role', [
                'name' => 'Super Admin'
            ]);

            $this->info("Super Admin role created successfully.");
        }
    }
}
