# Laravel CRUD Generator

A simple Laravel library that allows you to create crud operations with a single command. 
Supports CRUD operation for api's and ordinary CRUD.

## Features

* Ordinary CRUD Controller (with all the code already written)
* Api CRUD Controller
* Model
* Api Resource (if chosen)
* Migration
* Requests (StoreRequest and UpdateRequest)
* Routes (web.php and api.php. The command only will add a new record in the file, not overwrite whole file!!!)

## Installation locally

* Clone project into your laravel project in ```YourLaravelProject/packages/vadimknh/```. Create required folders.
* Add to composer.json autoload
```
"autoload": {
        "psr-4": {

            "Vadimknh\\CrudGenerator\\": "packages/vadimknh/laravel-crud-generator/src/"
        }
    },
```
* Add to config/app.php into providers 
```
'providers' => ServiceProvider::defaultProviders()->merge([

        Vadimknh\CrudGenerator\CrudGeneratorServiceProvider::class

    ])->toArray(),
```
* Regenerate autoload ```composer dump-autoload ```
* Publish the configuration files ```php artisan vendor:publish --provider="Vadimknh\CrudGenerator\CrudGeneratorServiceProvider"```
* Now you can use!

## Installation via composer
```
composer require vadimknh/laravel-crud-generator --dev
```

And publish the configuration files

```
php artisan vendor:publish --provider="Vadimknh\CrudGenerator\CrudGeneratorServiceProvider"
```

## Enable the package (Optional)
This package implements Laravel auto-discovery feature. After you install it the package provider and facade are added automatically for laravel.

## Usage

After publishing the configuration file just run the below command

```
// For ordinary 
php artisan crud:generate ModelName

// For api
php artisan crud:generate ModelName --api
```

Just it, Now all of your `Model Controller, Migration, routes, Requests, Resource (for api)`, will be created automatically with all the code required for basic crud operations. Check files.

## Example for ordinary

```angular2
// For ordinary 
php artisan crud:generate Car
```
### Will create next:
#### CarController.php
```angular2
<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\CarStoreRequest;
use App\Http\Requests\CarUpdateRequest;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        return view('admin.car.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.car.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarStoreRequest $request)
    {
        $data = $request->validated();
        Car::firstOrCreate($data);
        return redirect()->route('admin.car.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        return view('admin.car.show', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarUpdateRequest $request, Car $car)
    {
        $data = $request->validated();  
        $car->update($data);
        return redirect()->route('admin.car.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('admin.car.index');
    }
}
```

#### Car.php (Model)
```angular2
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Explicitly indicate which table this model is associated with.
     */
    protected $table = 'cars';

    /**
     * Explicitly indicate which columns in the table cannot be changed.
     */
    protected $guarded = [];
}

```

#### CarStoreRequest.php
```angular2
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
{   
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            // example: "title" => "required",
        ];
    }

    /**
     * Specify error messages during validation.
     */
    public function messages()
    {
        return [
            // example: "title.required" => "This field should not be empty. Please, fill up!"
        ];
    }
}
```

#### CarUpdateRequest.php
```angular2
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarUpdateRequest extends FormRequest
{   
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            // example: "title" => "required",
        ];
    }

    /**
     * Specify error messages during validation.
     */
    public function messages()
    {
        return [
            // example: "title.required" => "This field should not be empty. Please, fill up!"
        ];
    }
}
```

#### cars table migration
```angular2
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            // example: $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
``` 

#### Routes/web.php
```angular2
Route::resource('cars', App\Http\Controllers\CarController::class); 
```

## Example for api

```angular2
// For api
php artisan crud:generate Plane --api
```
### Will create next:
#### PlaneController.php
```angular2
<?php

namespace App\Http\Controllers;

use App\Models\Plane;
use App\Http\Resources\PlaneResource;
use App\Http\Requests\PlaneStoreRequest;
use App\Http\Requests\PlaneUpdateRequest;

class PlaneController extends Controller
{   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planes = Plane::all();
        return response(['data' => PlaneResource::collection($planes)], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(PlaneStoreRequest $request)
    {
        $data = $request->validated();
        $plane = Plane::firstOrCreate($data);
        return response(['data' => new PlaneResource($plane)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plane $plane)
    {
        return response(['data' => new PlaneResource($plane)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlaneUpdateRequest $request, Plane $plane)
    {
        $data = $request->validated();
        $plane->update($data);
        return response(['data' => new PlaneResource($plane)], 200);
    }

    /**
     * Delete the specified resource from storage.
     */
    public function destroy(Plane $plane)
    {
        $plane->delete();
        return response(['data' => null], 204);
    }
}
```
#### Plane.php (Model), StorePlaneRequest.php, UpdatePlaneRequest.php, routes/api.php, planes migration -> all the same as shown above

#### PlaneResource.php
```angular2
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // example: 'id' => $this->id,
            // example: 'title' => $this->title,
        ];
    }
}
```

### Tested on php 8.1 and laravel 10
