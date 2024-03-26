<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use SplFileInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Sweet1s\MoonshineRBAC\Abilities;

use function Laravel\Prompts\{intro, info, warning, text, confirm, search};

class MoonShineRBACCreatePermissionsResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:permissions {resourceName? : The name of the resource}';

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
        intro($this->description);

        $this->resourceName = $this->argument('resourceName') ?? (confirm(
            label: 'Manual entry or from a list?',
            default: true,
            yes: 'Manual input',
            no: 'Select from the list',
            hint: 'To create the resource name, we use the resources available in the application directory App/MoonShine/Resources',
        )
        ? text(
            label: 'Resource name',
            placeholder: 'E.g. UserResource',
            required: true,
        )
        : search(
            label: 'Select a resource',
            options: fn (string $value) => collect(File::allFiles(app_path('MoonShine/Resources')))
                ->transform(fn (SplFileInfo $file, $key) => $file->getBasename('.php'))
                ->filter()
                ->filter(fn (string $name) => str_contains(strtolower($name), strtolower($value)))
                ->values()
                ->toArray()
        ));

        foreach (Abilities::getAbilities() as $ability) {
            config('permission.models.permission')::updateOrCreate([
                'name' => "$this->resourceName.$ability",
                'guard_name' => config('moonshine.auth.guard')
            ]);
        }

        app()['cache']->forget('spatie.permission.cache');

        warning('Make sure to include the `WithRolePermissions` trait in the resource');

        info("Permissions created successfully for $this->resourceName.");

        return self::SUCCESS;
    }
}
