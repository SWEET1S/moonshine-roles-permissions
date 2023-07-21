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

        $this->call('migrate');

        $this->call('moonshine-roles-perm:publish');

        $this->call('vendor:publish', [
            '--provider' => "Sweet1s\MoonshineRolesPermissions\MoonshineRolesPermissionsServiceProvider",
            '--tag' => "config"
        ]);

        $this->info("Provider published successfully.");

        $this->call('moonshine-roles-perm:role',[
            'name' => 'Super Admin',
            '--all' => true
        ]);

        $this->info("Super Admin role created successfully.");

        $this->info("\n");

        $this->info('MoonShine Roles-Permissions package installed successfully.');

        return 0;
    }


}
