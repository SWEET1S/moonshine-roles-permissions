<?php

namespace Sweet1s\MoonshineRBAC\Commands;

use SplFileInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\{intro, info, text, confirm, search, select};

class MoonShineRBACResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moonshine-rbac:resource {name?} {--m|model=} {--t|title=} {--test} {--pest}';

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

        intro($this->description);

        $arguments = [];

        $arguments['name'] = $this->argument('name') ?? text(
            label: 'Name of the resource',
            placeholder: 'E.g. UserResource',
            required: true,
        );

        if ($this->option('model')) {
            $arguments['--model'] = $this->option('model');
        } else {
            $confirm_model = confirm(
                label: 'Set a model for a resource?',
                default: false,
                hint: 'By default, it will be formed from the name of the resource'
            );

            if ($confirm_model) {
                $arguments['--model'] = (confirm(
                    label: 'Enter manually?',
                    default: true,
                    hint: 'Otherwise, it will be possible to select a model from a folder app/Models',
                )
                ? text(
                    label: 'Eloquent model for the model resource',
                    placeholder: 'E.g. User',
                    required: true,
                )
                : search(
                    label: 'Select a model',
                    options: fn (string $value) => collect(File::allFiles(app_path('Models')))
                        ->transform(fn (SplFileInfo $file, $key) => $file->getBasename('.php'))
                        ->filter()
                        ->filter(fn (string $name) => str_contains(strtolower($name), strtolower($value)))
                        ->values()
                        ->toArray()
                ));
            }
        }

        if ($this->option('title')) {
            $arguments['--title'] = $this->option('title');
        } else {
            $confirm_title = confirm(
                label: 'Set the section title?',
                default: false,
                hint: 'By default, it will be formed from the name of the resource'
            );

            if ($confirm_title) {
                $arguments['--title'] = text(
                    label: 'Section title',
                    placeholder: 'E.g. Users',
                );
            }
        }

        if ($this->option('test')) {
            $arguments['--test'] = $this->option('test');
        } elseif ($this->option('pest')) {
            $arguments['--pest'] = $this->option('pest');
        } else {
            $test  = select(
                label: 'Select the type of test if necessary?',
                options: [
                    'not' => 'Not',
                    '--test' => 'Test',
                    '--pest' => 'Pest'
                ],
                required: true,
            );
            if ($test !== 'not') {
                $arguments[$test] = true;
            }
        }

        info('Launching the MoonShine resource generation command...');

        $this->call('moonshine:resource', $arguments);

        $name = $arguments['name'];

        if (!str_contains($name, 'Resource')) {
            $name .= 'Resource';
        }


        $this->call('moonshine-rbac:permissions', [
            'resourceName' => $name
        ]);

        return self::SUCCESS;
    }
}
