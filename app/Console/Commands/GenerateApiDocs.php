<?php

namespace Nestor\Console\Commands;

use Artisan;

use Illuminate\Console\Command;

class GenerateApiDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nestorqa:apidocs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nestor-QA: generate API documentation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generates API documentation. Adds header for jekyll page template.
     *
     * @return mixed
     */
    public function handle()
    {
        $exitCode = Artisan::call('api:docs', [
            '--name' => "Nestor-QA RESTful API", '--use-version' => "v1", '--output-file' => "./docs/documentation/api/api.md"
        ]);

        if (0 !== $exitCode) {
            return $exitCode;
        }

        $args = [];
        array_push($args, 'sed');
        array_push($args, '-i');
        array_push($args, '1i"----\nlayout: page\ntitle: API Installation\n----\n\n"');
        array_push($args, "./docs/documentation/api/api.md");

        $command = implode(' ', $args);
        $proc = proc_open($command, [STDIN, STDOUT, STDERR], $pipes);
        if ($proc === false) {
            $this->error("Failed to open process");
            return 2;
        }
        return proc_close($proc);
    }
}
