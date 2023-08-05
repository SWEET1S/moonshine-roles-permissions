<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $name = $this->ask('Name');
        $name = ucfirst($name);

        $email = $this->ask('Email');

        $password = $this->secret('Password');

        $roles = config('permission.models.role')::all()->pluck('name')->toArray();
        $role = $this->choice('Select role', $roles, 0);

        if ($email && $name && $password && $role) {

             DB::table('users')->insert([
                 'name' => $name,
                 'email' => $email,
                 'password' => bcrypt($password),
                 'role_id' => config('permission.models.role')::where('name', $role)->first()->id,
             ]);

            $this->components->info('User is created');
        } else {
            $this->components->error('All params is required');
        }

        return 0;
    }


}
