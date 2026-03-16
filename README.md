# PHP_Laravel12_Slower


## Project Description

PHP_Laravel12_Slower is a simple Laravel 12 demonstration project that shows how to detect and analyze slow database queries in a Laravel application.

The project uses the Laravel Slower package to monitor database queries and identify queries that take longer than the configured threshold time. This helps developers improve application performance by identifying inefficient database queries.

The application displays a list of products from the database and intentionally runs a slower query so that the Laravel Slower package can detect and log it for analysis.

This project is useful for learning how to monitor database performance and optimize queries in Laravel applications.



## Features

- Detect slow database queries automatically
- Configure slow query threshold time
- Store slow query logs in the database
- Generate sample product data using seeders
- Display product data in a styled Blade view
- Demonstrate performance monitoring in Laravel
- Clean old slow query logs using Artisan command


## Technologies Used

- PHP  
- Laravel 12  
- MySQL  
- Eloquent ORM  
- Blade Template Engine  
- HTML  
- CSS  
- Laravel Slower Package  
- Composer  
- Artisan CLI



---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Slower "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Slower

```

#### Explanation:

This command installs a fresh Laravel 12 application using Composer and creates a new project folder named PHP_Laravel12_Slower.

The cd command moves into the project directory so you can start working on the application.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_Slower
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_Slower

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

This step connects the Laravel application to the MySQL database by updating the .env configuration file.

Running php artisan migrate creates the default Laravel database tables.





## STEP 3: Install Laravel Slower Package

### Install package:

```
composer require halilcosdu/laravel-slower

```

### Publish Config File

```
php artisan vendor:publish --tag=slower-config

```

### Now config file will be created

```
config/slower.php

```

### Example config:

```
<?php

return [

    'enabled' => true,

    'slow_query_threshold' => 200,

    'ai_recommendation' => true,

];

```

#### Explanation:

This command installs the Laravel Slower package, which helps detect slow database queries in Laravel applications.

Publishing the configuration file creates config/slower.php, where you can customize settings such as slow query threshold and AI recommendations.






## STEP 4: Publish Migration

### Run:

```
php artisan vendor:publish --tag=slower-migrations

```

### Then Run:

```
php artisan migrate

```

#### Explanation:

This step publishes the migration file required by the Laravel Slower package.

Running migrations creates the database table used to store slow query logs.




## STEP 5: Create Product Model

### Run:

```
php artisan make:model Product -m

```


### Edit: database/migrations/create_products_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->timestamps();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};


```


### Then Run:

```
php artisan migrate

```


### Model: app/Models/Product.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     */

    protected $table = 'products';


    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'name',
        'price'
    ];


    /**
     * The attributes that should be cast.
     */

    protected $casts = [
        'price' => 'integer',
    ];

}

```

#### Explanation:

This command creates a Product model and migration file.

The Product model represents the products table in the database and allows interaction with product records using Laravel's Eloquent ORM.





## STEP 6: Create Seeder (Generate Data)

### Run:

```
php artisan make:seeder ProductSeeder

```

### File: database/seeders/ProductSeeder.php

```
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        for ($i = 1; $i <= 5000; $i++) {

            Product::create([
                'name' => 'Product ' . $i,
                'price' => rand(50, 500)
            ]);

        }

    }
}

```



### Open: database/seeders/DatabaseSeeder.php

#### Add:

```
$this->call(ProductSeeder::class);

```

### Run:

```
php artisan db:seed

```

#### Explanation:

Seeder files are used to populate the database with sample data.

This step inserts multiple product records into the products table for testing purposes.





## STEP 7: Create Controller

### Run:

```
php artisan make:controller ProductController

```

### File: app/Http/Controllers/ProductController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {

        // intentionally slow query
        $products = Product::where('price', '>', 100)->get();

        return view('products', compact('products'));

    }

}

```

#### Explanation:

The controller handles application logic and retrieves product data from the database.

It sends the data to the view so it can be displayed on the webpage.





## STEP 8: Create View File

### resources/views/products.blade.php

```
<!DOCTYPE html>
<html>

<head>

    <title>Products</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1e1e2f;
            margin: 0;
            padding: 0;
        }

        /* Page Header */

        .header {
            text-align: center;
            padding: 25px;
            background: #2a2a40;
            color: white;
        }

        /* Container */

        .container {
            width: 85%;
            margin: 40px auto;
        }

        /* Table Card */

        .table-card {
            background: #2a2a40;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* Table */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header */

        th {
            background: #4e73df;
            color: white;
            padding: 14px;
            text-align: left;
            font-size: 15px;
        }

        /* Table cells */

        td {
            padding: 12px;
            border-bottom: 1px solid #444;
            color: #ddd;
        }

        /* Alternate row */

        tr:nth-child(even) {
            background: #24243a;
        }

        /* Hover */

        tr:hover {
            background: #34345a;
        }

        /* Price badge */

        .price {
            background: #1cc88a;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 13px;
        }

        /* Footer */

        .footer {
            text-align: center;
            color: #aaa;
            margin-top: 20px;
        }
    </style>

</head>

<body>

    <div class="header">
        <h2>Product Management System</h2>
        <p>Laravel 12 Slower Demo</p>
    </div>

    <div class="container">

        <div class="table-card">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($products as $product)

                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td><span class="price">₹{{ $product->price }}</span></td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="footer">
            © {{ date('Y') }} Laravel Slower Project
        </div>

    </div>

</body>

</html>

```

#### Explanation:

The Blade view displays product data in a styled table format.

It loops through all products and shows them in a user-friendly interface.




## STEP 9: Add Route

### Open: routes/web.php

```
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class,'index']);

```

#### Explanation:

Routes define the URL endpoints of the application.

This route calls the index method of ProductController when the /products URL is accessed.




## STEP 10: Test Slow Query

### Start Laravel dev server:

```
php artisan serve

```

### Open browser:

```
http://127.0.0.1:8000/products

```

#### Explanation:

Running the Laravel development server allows the application to be accessed in a web browser.

When the products page loads, Laravel Slower monitors the database query execution time.




## STEP 11: Clean Old Logs  (Optional)

### Run:

```
php artisan slower:clean

```

### Example:

```
php artisan slower:clean 30

```

#### Explanation:

This command removes old slow query logs from the database to keep the log table clean and optimized.

Deletes logs older than 30 days.



## Expected Output:


<img src="screenshots/Screenshot 2026-03-16 114238.png" width="900">



---

## Project Folder Structure:

```
PHP_Laravel12_Slower
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── ProductController.php
│   │
│   └── Models
│       └── Product.php
│
├── config
│   └── slower.php
│
├── database
│   ├── migrations
│   │   ├── create_products_table.php
│   │   └── create_slow_logs_table.php
│   │
│   └── seeders
│       └── ProductSeeder.php
│
├── resources
│   └── views
│       └── products.blade.php
│
├── routes
│   └── web.php
│
├── .env
├── .env.example
│
├── composer.json
├── artisan
└── README.md

```
