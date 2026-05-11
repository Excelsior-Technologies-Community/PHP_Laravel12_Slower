<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Start Timer
        |--------------------------------------------------------------------------
        */

        $start = microtime(true);

        /*
        |--------------------------------------------------------------------------
        | Heavy Query
        |--------------------------------------------------------------------------
        */

        $products = Product::where('price', '>', 100)
            ->orderByRaw('RAND()')
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | Force Delay
        |--------------------------------------------------------------------------
        */

        usleep(300000);

        /*
        |--------------------------------------------------------------------------
        | Calculate Execution Time
        |--------------------------------------------------------------------------
        */

        $time = (microtime(true) - $start) * 1000;

        /*
        |--------------------------------------------------------------------------
        | Store Slow Query Log
        |--------------------------------------------------------------------------
        */

        DB::table('slow_logs')->insert([

            'is_analyzed' => false,

            'bindings' => json_encode([]),

            'sql' => 'SELECT * FROM products WHERE price > 100 ORDER BY RAND()',

            'time' => round($time, 2),

            'connection' => 'mysql',

            'connection_name' => 'mysql',

            'raw_sql' => 'SELECT * FROM products WHERE price > 100 ORDER BY RAND()',

            'recommendation' => 'Avoid ORDER BY RAND() for better performance and use indexing.',

            'created_at' => now(),

            'updated_at' => now(),
        ]);

        return view('products', compact('products'));
    }
}