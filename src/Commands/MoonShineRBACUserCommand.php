<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\{intro, info, text, password, select};

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
        intro($this->description);

        $name = ucfirst(text(
            label: 'Name',
            placeholder: 'E.g. Andrey',
            required: 'Your name is required.',
        ));

        $email = text(
            label: 'Email',
            placeholder: 'E.g. info@site.com',
            required: 'Your email is required.',
            validate: fn ($value) => match (true) {
                !filter_var($value, FILTER_VALIDATE_EMAIL) => 'Email is not valid',
                default => null
            }
        );

        $password = password(
            label: 'Password',
            required: 'The password is required.',
            hint: 'The password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit and a special character.',
            validate: fn (string $value) => match (true) {
                strlen($value) < 8 => 'The password must be at least 8 characters.',
                !preg_match('/[A-Z]/', $value) => 'The password must contain at least one uppercase letter.',
                !preg_match('/[a-z]/', $value) => 'The password must contain at least one lowercase letter.',
                !preg_match('/[0-9]/', $value) => 'The password must contain at least one number.',
                !preg_match('/[^A-Za-z0-9]/', $value) => 'The password must contain at least one special character.',

                default => null
            }
        );

        $role_id = select(
            'Select role',
            config('permission.models.role')::pluck('name', 'id'),
        );

        $user = config('moonshine.auth.model')::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $user->assignRole($role_id);

        info('The user has been created.');

        return self::SUCCESS;
    }
}
