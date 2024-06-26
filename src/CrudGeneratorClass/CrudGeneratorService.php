<?php

namespace Vadimknh\CrudGenerator\CrudGeneratorClass;

// legacy: use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudGeneratorService
{
    /** 
     * Get stub files by name
     * 
     * @param $type
     */
    protected static function GetStubs($type)
    {
        return file_get_contents(resource_path("/views/vendor/vadimknh/stubs/$type.stub"));
    }

    /** 
     * Сreate controller from stub file 
     * 
     * @param $name
     */
    public static function MakeController($name)
    {
        $template = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],[
                $name,
                strtolower( Str::plural($name)),
                strtolower($name)
            ],

           CrudGeneratorService::GetStubs('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $template);

    }

    /** 
     * Сreate model from stub file
     * 
     * @param $name
     */
    public static function MakeModel($name)
    {
        $template = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
            ],[
                $name,
                strtolower( Str::plural($name)),
            ],

            CrudGeneratorService::GetStubs('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"), $template);
    }

    /** 
     * Create StoreRequest from stub file
     * 
     * @param $name
     */
    public static function MakeStoreRequest($name)
    {
        $template = str_replace(
            ['{{modelName}}'],
            [$name],
           CrudGeneratorService::GetStubs('StoreRequest')
        );

        if (! file_exists($path=app_path('/Http/Requests/')))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Http/Requests/{$name}StoreRequest.php"), $template);
    }

    /** 
     * Create UpdateRequest from stub file
     * 
     * @param $name
     */
    public static function MakeUpdateRequest($name)
    {
        $template = str_replace(
            ['{{modelName}}'],
            [$name],
           CrudGeneratorService::GetStubs('UpdateRequest')
        );

        if (! file_exists($path=app_path('/Http/Requests/')))
            mkdir($path, 0777, true);

        file_put_contents(app_path("/Http/Requests/{$name}UpdateRequest.php"), $template);
    }

    /** 
     * Create migration from stub file
     * 
     * @param $name
     */
    public static function MakeMigration($name)
    {
        // legacy: Artisan::call('make:migration create_'. strtolower( Str::plural($name)).'_table --create='. strtolower( Str::plural($name)));

        $template = str_replace(
            ['{{modelNamePluralLowerCase}}'],
            [strtolower( Str::plural($name))],
           CrudGeneratorService::GetStubs('Migration')
        );

        file_put_contents(base_path("database/migrations/" . date('Y_m_d_His') . "_create_" . strtolower( Str::plural($name)) . "_table.php"), $template);
    }

    /** 
     * Create route in web.php file
     * 
     * @param $name
     */
    public static function MakeRoute($name)
    {
        $path_to_file  = base_path('routes/web.php');
        $append_route = 'Route::resource(\'' . Str::plural(strtolower($name)) . "', App\Http\Controllers\\{$name}Controller::class); \n";
        File::append($path_to_file, $append_route);
    }
}
