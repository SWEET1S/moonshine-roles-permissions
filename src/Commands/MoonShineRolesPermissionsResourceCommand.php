<?php

namespace Sweet1s\MoonshineRolesPermissions\Commands;

use Illuminate\Console\Command;

class MoonShineRolesPermissionsResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-roles-perm:resource {name?} {--m|model=} {--t|title=} {--s|singleton} {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create resource with policy and permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('moonshine:resource', [
            'name' => $this->argument('name'),
            '--model' => $this->option('model'),
            '--title' => $this->option('title'),
            '--singleton' => $this->option('singleton'),
            '--id' => $this->option('id'),
        ]);

        $this->call('moonshine-roles-perm:policy', [
            'model' => $this->argument('name')
        ]);

        return 0;
    }


}
