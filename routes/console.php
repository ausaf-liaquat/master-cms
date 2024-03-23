<?php

use App\Classes\Crud;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('crud:generator  {name : Class (singular) for example User}', function () {

    $name = $this->argument('name');
    $baseName = Str::replace('-', ' ', $name);
    $controller = ucwords($baseName);
    $controller = Str::replace(' ', '', $controller);
    $lower = strtolower($name);
    
    $crud = new Crud(); 
    $crud->model($name);
    $crud->controller($name);
    $crud->index($name);
    $crud->create($name);
    
    $new = ucfirst($name);
    $search = "/*-- Import --*/";
    $replace = $search . "\n" . 'use App\\Http\\Controllers\\'.$new.'\\' . $controller . 'Controller;' . "\n";
    file_put_contents(base_path('routes/web.php'), str_replace($search, $replace, file_get_contents(base_path('routes/web.php'))));

    $search = "/*-- Datatable --*/";
    $replace = $search . "\n" . 'Route::get(\'' . '/' . $lower . "/datatable',[{$controller}Controller::class ,'datatable'])->name('{$lower}-datatable');" . "\n";
    file_put_contents(base_path('routes/web.php'), str_replace($search, $replace, file_get_contents(base_path('routes/web.php'))));

    $search = "/*-- Routes --*/";
    $replace = $search . "\n" . '/*------------------- Crud Operation For ' . $controller . ' -------------------*/' . "\n\n" .
        'Route::get(\'' . '/' . $lower . "',[{$controller}Controller::class, 'index'])->name('{$lower}-index');" . "\n" .
        'Route::get(\'' . '/' . $lower . "/create',[{$controller}Controller::class, 'create'])->name('{$lower}-create');" . "\n" .
        'Route::post(\'' . '/' . $lower . "/store',[{$controller}Controller::class, 'store'])->name('{$lower}-store');" . "\n" .
        'Route::get(\'' . '/' . $lower . "/edit/{id}',[{$controller}Controller::class, 'edit'])->name('{$lower}-edit');" . "\n" .
        'Route::patch(\'' . '/' . $lower . "/status',[{$controller}Controller::class, 'status'])->name('{$lower}-status');" . "\n" .
        'Route::put(\'' . '/' . $lower . "/update/{id}',[{$controller}Controller::class, 'update'])->name('{$lower}-update');" . "\n" .
        'Route::delete(\'' . '/' . $lower . "/delete/{id}',[{$controller}Controller::class, 'destroy'])->name('{$lower}-delete');" . "\n";
    file_put_contents(base_path('routes/web.php'), str_replace($search, $replace, file_get_contents(base_path('routes/web.php'))));
});
