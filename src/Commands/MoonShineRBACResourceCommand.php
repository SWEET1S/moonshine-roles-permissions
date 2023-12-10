<?php

namespace MoonshineRBAC\Commands;

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
        $this->call('moonshine:resource', [
            'name' => $this->argument('name'),
            '--model' => $this->option('model'),
            '--title' => $this->option('title')
        ]);

        $name = $this->argument('name');

        if (!str_contains($name, 'Resource')) {
            $name .= 'Resource';
        }

        $this->call('moonshine-rbac:policy', [
            'model' => $name
        ]);

        return 0;
    }
}
