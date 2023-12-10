<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use Illuminate\Console\Command;

class MoonShineRBACResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:resource {name?} {--m|model=} {--t|title=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create resource with permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {

        $arguments = [];

        if ($this->argument('name')) {
            $arguments['name'] = $this->argument('name');
        }

        if ($this->option('model')) {
            $arguments['--model'] = $this->option('model');
        }

        if ($this->option('title')) {
            $arguments['--title'] = $this->option('title');
        }

        $this->call('moonshine:resource', $arguments);

        $name = $this->argument('name');

        if (!str_contains($name, 'Resource')) {
            $name .= 'Resource';
        }

        $this->call('moonshine-rbac:permissions', [
            'resourceName' => $name
        ]);

        return 0;
    }
}
