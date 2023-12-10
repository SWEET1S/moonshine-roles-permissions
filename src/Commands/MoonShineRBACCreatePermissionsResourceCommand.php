<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;
use Sweet1s\MoonshineRBAC\Abilities;

class MoonShineRBACCreatePermissionsResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:permissions {resourceName : The name of the resource like UserResource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions for the resources';

    /**
     * @var string
     */
    protected string $resourceName;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {

        $this->resourceName = $this->argument('resourceName');

        foreach (Abilities::getAbilities() as $ability) {
            config('permission.models.permission')::updateOrCreate([
                'name' => "$this->resourceName.$ability",
                'guard_name' => config('moonshine.auth.guard')
            ]);
        }

        app()['cache']->forget('spatie.permission.cache');

        $this->info("Permissions created successfully for $this->resourceName.");

        return 0;
    }
}
