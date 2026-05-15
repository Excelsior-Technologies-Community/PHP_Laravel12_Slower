<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $start = microtime(true);

        // Get filter and sort parameters
        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $search = $request->get('search');

        // Build query with filters
        $query = Product::query();

        if ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Simulate heavy query based on sort
        if ($sortBy === 'random') {
            $query->orderByRaw('RAND()');
            $isHeavy = true;
        } elseif ($sortBy === 'price_desc') {
            $query->orderBy('price', 'desc');
            $isHeavy = false;
        } elseif ($sortBy === 'price_asc') {
            $query->orderBy('price', 'asc');
            $isHeavy = false;
        } else {
            $query->orderBy($sortBy, $sortOrder);
            $isHeavy = false;
        }

        $products = $query->paginate(15);

        // Add delay for slow query simulation
        if ($isHeavy || $request->get('simulate_slow')) {
            usleep(rand(200000, 500000));
        }

        $executionTime = (microtime(true) - $start) * 1000;

        // Store slow query log if execution time > 100ms
        if ($executionTime > 100) {
            $sql = $query->toSql();
            $bindings = $query->getBindings();

            foreach ($bindings as &$binding) {
                if (is_string($binding)) {
                    $binding = "'{$binding}'";
                }
            }

            $fullSql = vsprintf(str_replace('?', '%s', $sql), $bindings);

            DB::table('slow_logs')->insert([
                'is_analyzed' => false,
                'bindings' => json_encode($bindings),
                'sql' => $sql,
                'time' => round($executionTime, 2),
                'connection' => 'mysql',
                'connection_name' => 'mysql',
                'raw_sql' => $fullSql,
                'recommendation' => $this->getRecommendation($sortBy, $executionTime),
                'route' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'affected_rows' => $products->total(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Check for index suggestions
            $this->checkIndexSuggestion($sql);
        }

        $stats = [
            'total_products' => Product::count(),
            'avg_price' => round(Product::avg('price'), 2),
            'max_price' => Product::max('price'),
            'min_price' => Product::min('price'),
        ];

        return view('products', compact('products', 'stats', 'executionTime', 'isHeavy' ?? false));
    }

    private function getRecommendation($sortBy, $time)
    {
        if ($sortBy === 'random') {
            return '⚠️ Avoid ORDER BY RAND() for large datasets. Consider using a different random selection method.';
        }

        if ($time > 300) {
            return '🔴 This query is very slow. Consider adding indexes on filtered columns.';
        }

        if ($time > 150) {
            return '🟡 Query performance needs improvement. Review the query structure.';
        }

        return '🟢 Query performance is acceptable.';
    }

    private function checkIndexSuggestion($sql)
    {
        $hash = md5($sql);

        $existing = DB::table('index_suggestions')
            ->where('query_hash', $hash)
            ->first();

        if ($existing) {
            DB::table('index_suggestions')
                ->where('query_hash', $hash)
                ->increment('frequency');
        } else {
            // Detect columns that might need indexes
            preg_match_all('/WHERE\s+(\w+)\s*[=<>!]+/i', $sql, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $column) {
                    DB::table('index_suggestions')->insert([
                        'table_name' => 'products',
                        'column_name' => $column,
                        'query_hash' => $hash,
                        'frequency' => 1,
                        'applied' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}