<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;
use SplFileInfo;
use Illuminate\Support\Facades\File;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;

class MoonShineRBACInitPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:init-permissions {path? : The path to resources}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating permissions for existing resources';

    /**
     * @var string
     */
    protected string $path;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info($this->description);

        $this->path = $this->argument('path') ?? app_path('MoonShine/Resources');

        $this->generatePermissionsForResources();

        $this->info('Permissions generated successfully');

        return self::SUCCESS;
    }

    protected function generatePermissionsForResources(): void
    {
        foreach ($this->getNameResources() as $name) {
            $this->call('moonshine-rbac:permissions', [
                'resourceName' => $name
            ]);
        };
    }

    protected function getNameResources(): array
    {
        return collect(File::allFiles($this->path))
            ->transform(function (SplFileInfo $file, $key) {
                $base_name = $file->getBasename('.php');
                $namespace = str($file->getPath())->after('/app')->replace('/', "\\")->start('App')->append("\\", $base_name);

                return in_array(WithRolePermissions::class, class_uses($namespace->value)) ? $base_name : null;
            })
            ->filter()
            ->values()
            ->toArray();
    }
}
