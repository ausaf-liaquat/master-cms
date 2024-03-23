<?php

namespace App\Classes;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Crud
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    public function model($name)
    {
        $baseName = Str::replace('-', ' ', $name);
        $baseName = Str::replace(' ', '', $baseName);
        $name = Str::singular($baseName);
        $modelTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate, 777);
    }

    public function controller($name)
    {
        $baseName = Str::replace('-', ' ', $name);
        $controller = ucwords($baseName);
        $controller = Str::replace(' ', '', $controller);
        $modelName = Str::singular($controller);
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{ucwords}}',
                '{{Name}}'
            ],
            [
                $name,
                strtolower(Str::plural($name)),
                strtolower($name),
                $controller,
                $modelName
            ],
            $this->getStub('Controller')
        );
        $new = ucfirst($name);
     
        $pathController = app_path("Http/Controllers/{$new}");
        mkdir($pathController, 0777, true);
   
        file_put_contents(app_path("Http/Controllers/{$new}/{$controller}Controller.php"), $controllerTemplate, 777);
    }

    public function index($name)
    {
        $baseName = Str::replace('-', ' ', $name);
        $indexTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralCase}}',
                '{{modelNameUpperCase}}',
                '{{modelNameUpperCaseFirst}}',
                '{{modelNameSingularLowerCase}}',
                '{{ucwords}}',
            ],
            [
                $name,
                Str::plural($name),
                strtoupper($name),
                ucfirst($name),
                strtolower($name),
                ucwords($baseName)
            ],
            $this->getStub('Index')
        );
        $new = strtolower($name);
        $path = resource_path("/views/{$new}");
        mkdir($path, 0777, true);
        file_put_contents(resource_path("/views/{$new}/index.blade.php"), $indexTemplate);
    }

    public function create($name)
    {
        $baseName = Str::replace('-', ' ', $name);
        $base = Str::replace(' ', '_', $baseName);
        $createTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralCase}}',
                '{{modelNameUpperCase}}',
                '{{modelNameUpperCaseFirst}}',
                '{{modelNameSingularLowerCase}}',
                '{{VariableName}}',
                '{{ucwords}}',

            ],
            [
                $name,
                Str::plural($name),
                strtoupper($name),
                ucfirst($name),
                strtolower($name),
                Str::singular(strtolower($base)),
                ucwords($baseName)
            ],
            $this->getStub('Add')
        );
        $new = strtolower($name);
        file_put_contents(resource_path("/views/{$new}/add.blade.php"), $createTemplate);
    }
}
