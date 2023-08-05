<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Spatie\Permission\Models\Role;

class MoonShineRolesPermissionsPolicyCommand extends MoonShineRolesPermissionsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:policy
                            {model : The name of the model}
                            {--name= : The name of the policy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a policy for the resource';

    /**
     * @var string
     */
    protected string $modelName;

    /**
     * @var string
     */
    protected string $resourceName;

    /**
     * Execute the console command.
     *
     * @return int
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $this->modelName = $this->argument('model');

        if (!class_exists('App\Models\\' . $this->modelName)) {
            $this->error('Model does not exist!');
            return 0;
        }

        $this->resourceName = $this->modelName . "Resource";

        if (!class_exists("App\\MoonShine\\Resources\\$this->resourceName")) {
            $this->error("Resource $this->resourceName does not exist!");
            return 0;
        }

        if (class_exists("App\Policies\\" . $this->modelName . "Policy")) {
            $this->error("Policy for $this->modelName already exists!");
            return 0;
        }

        if (!file_exists(app_path("Policies"))) {
            mkdir(app_path("Policies"));
        }

        $this->call('moonshine-roles-perm:permissions', [
            'resourceName' => $this->modelName . 'Resource'
        ]);

        $modalPath = config('moonshine.auth.providers.moonshine.model');
        $path = "App\Policies\\" . ($this->option('name') ?? $this->modelName . 'Policy') . ".php";

        $this->copyStub("Policy", $path, [
            '{model}' => "App\Models\\$this->modelName" == $modalPath ? "" : "use App\Models\\$this->modelName;",
            '{name}' => $this->modelName,
            '{pathToUserModel}' => $modalPath,
            '{namePolicy}' => $this->option('name') ?? $this->modelName . 'Policy',
        ]);

        if($role = Role::first()?->name ?? false){
            $this->call('moonshine-roles-perm:role', [
                'name' => $role,
            ]);
        }

        $this->info('Policy generated successfully!');

        return 0;
    }

}
