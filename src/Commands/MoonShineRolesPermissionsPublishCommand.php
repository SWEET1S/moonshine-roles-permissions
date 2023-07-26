<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

class MoonShineRolesPermissionsPublishCommand extends MoonShineRolesPermissionsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish policies to App\Policies and models to App\Models';


    /**
     * Execute the console command.
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $this->publishPolicies();
        $this->publishModels();

        $this->info("Publishing model and policies finished!");
        return 0;
    }

    /**
     * @throws FileNotFoundException
     */
    private function publishPolicies(): void
    {
        if(!file_exists(app_path("Policies"))) {
            mkdir(app_path("Policies"));
        }

        $path = "App\Policies\RolePolicy.php";
        $this->copyStub("RolePolicy", $path, [
            '{pathToModel}' => config('moonshine.auth.providers.moonshine.model')
        ]);

        $path = "App\Policies\UserPolicy.php";
        $this->copyStub("UserPolicy", $path, [
            '{pathToModel}' => config('moonshine.auth.providers.moonshine.model')
        ]);
    }

    private function publishModels(): void
    {
        if (!File::exists("App\Models\Role.php")) {
            $this->copyStub("Role", "App\Models\Role.php", []);

            $this->info("Role model published successfully.");

            if (config('permission.models.role') != "App\Models\Role") {
                $this->warn('Replace in config permission.models.role with App\Models\Role::class');
            }

            return;
        }

        $this->warn("Role model already exists.");
        $this->warn('Extend your model with Spatie\Permission\Models\Role');

        if(config('permission.models.role') != "App\Models\Role"){
            $this->warn('Replace in config permission.models.role with App\Models\Role::class');
        }

    }


}
