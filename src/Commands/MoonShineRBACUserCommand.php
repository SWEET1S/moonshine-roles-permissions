<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoonShineRBACUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:user';

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
        $name = $this->ask('Name');
        $name = ucfirst($name);

        $email = $this->ask('Email');

        $password = $this->secret('Password');

        $roles = config('permission.models.role')::all()->pluck('name')->toArray();
        $role = $this->choice('Select role', $roles, 0);

        if ($email && $name && $password && $role) {

            $user = config('moonshine.auth.providers.moonshine.model')::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            $user->assignRole($role);

            $this->components->info('User is created');
        } else {
            $this->components->error('All params is required');
        }

        return 0;
    }
}
