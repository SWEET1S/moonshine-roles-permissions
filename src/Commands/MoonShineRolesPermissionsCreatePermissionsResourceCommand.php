<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Console\Command;

class MoonShineRolesPermissionsCreatePermissionsResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:permissions {resourceName : The name of the resource like UserResource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate permissions for the resources';

    /**
     * @var array|string[]
     */
    protected array $permissions = [
        'viewAny',
        'view',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
        'massDelete'
    ];

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

        foreach ($this->permissions as $permission) {
            config('permission.models.permission')::updateOrCreate([
                'name' => "$this->resourceName.$permission",
                'guard_name' => config('moonshine.auth.guard')
            ]);
        }

        return 0;
    }


}
