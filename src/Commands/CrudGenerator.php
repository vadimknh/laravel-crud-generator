<?php

namespace Vadimknh\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Vadimknh\CrudGenerator\CrudGeneratorClass\CrudGeneratorService;
use Vadimknh\CrudGenerator\CrudGeneratorClass\ApiCrudGeneratorService;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate {name : Class (Singular), e.g User, Place, Car} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all Crud operations with a single command';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name =  $this->argument('name');
        $api = $this->option('api');

        if ($api) {
            ApiCrudGeneratorService::MakeController($name);
            ApiCrudGeneratorService::MakeModel($name);
            ApiCrudGeneratorService::MakeStoreRequest($name);
            ApiCrudGeneratorService::MakeUpdateRequest($name);
            ApiCrudGeneratorService::MakeMigration($name);
            ApiCrudGeneratorService::MakeRoute($name);
            ApiCrudGeneratorService::MakeResource($name);

            $this->info('Api Crud for '. $name. ' generated successfully. Check files');
        }

        if (! $api) {
            CrudGeneratorService::MakeController($name);
            CrudGeneratorService::MakeModel($name);
            CrudGeneratorService::MakeStoreRequest($name);
            CrudGeneratorService::MakeUpdateRequest($name);
            CrudGeneratorService::MakeMigration($name);
            CrudGeneratorService::MakeRoute($name);

            $this->info('Crud for '. $name. ' generated successfully. Check files');
        }
    }
}
