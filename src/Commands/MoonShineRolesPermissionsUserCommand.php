<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MoonShineRolesPermissionsUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->ask('Name', 'User');
        $name = ucfirst($name);

        $email = $this->ask('Email', 'admin@admin.com');

        $password = $this->secret('Password');

        $roles = Role::all()->pluck('name')->toArray();
        $role = $this->choice('Select role', $roles, 0);

        if ($email && $name && $password) {
            config('moonshine.auth.providers.moonshine.model')::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'role_id' => Role::where('name', $role)->first()->id,
            ]);

            $this->components->info('User is created');
        } else {
            $this->components->error('All params is required');
        }

        return 0;
    }


}
