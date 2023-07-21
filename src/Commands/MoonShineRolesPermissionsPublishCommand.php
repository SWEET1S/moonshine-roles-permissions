<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

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
    protected $description = 'Publish resources UserResource and RoleResource to App\MoonShine\Resources and policies to App\Policies';


    /**
     * Execute the console command.
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $this->publishResources();
        $this->publishPolicies();
        $this->info("Publishing resources and policies finished!");
        return 0;
    }

    /**
     * @throws FileNotFoundException
     */
    private function publishResources(): void
    {
        $path = "App\MoonShine\Resources\RoleResource.php";
        $this->copyStub("RoleResource", $path, []);

        $this->call('moonshine-roles-perm:permissions', [
            'resourceName' => 'RoleResource'
        ]);

        $path = "App\MoonShine\Resources\UserResource.php";
        $this->copyStub("UserResource", $path, [
            '{model-namespace}' => config('moonshine.auth.providers.moonshine.model'),
            '{model}' => "User"
        ]);

        $this->call('moonshine-roles-perm:permissions', [
            'resourceName' => 'UserResource'
        ]);

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
        $this->copyStub("RolePolicy", $path, [
            '{pathToModel}' => config('moonshine.auth.providers.moonshine.model')
        ]);
    }


}
