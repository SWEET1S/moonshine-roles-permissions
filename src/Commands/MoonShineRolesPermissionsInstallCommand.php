<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Console\Command;

class MoonShineRolesPermissionsInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:install';

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

        $this->call('moonshine-roles-perm:publish');

        $this->call('moonshine-roles-perm:permissions', [
            'resourceName' => 'UserResource'
        ]);

        $this->call('moonshine-roles-perm:permissions', [
            'resourceName' => 'RoleResource'
        ]);

        $this->createRole();

        $this->info("\n");

        $this->info('MoonShine Roles-Permissions package installed successfully.');

        return 0;
    }

    public function migration(): void
    {
        $response = $this->choice('Do you want create or supplement users table ?', ['yes', 'no'], 0);

        if ($response == 'yes') {
            $this->call('migrate', [
                '--path' => 'vendor/sweet1s/moonshine-roles-permissions/database/migrations/create_or_supplement_users_table.php'
            ]);
        }
    }

    public function createRole(): void
    {
        if(config('permission.models.role')::first() == null){
            $this->call('moonshine-roles-perm:role',[
                'name' => 'Super Admin'
            ]);

            $this->info("Super Admin role created successfully.");
        }
    }

}
