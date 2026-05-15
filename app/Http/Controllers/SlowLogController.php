<?php
// app/Http/Controllers/SlowLogController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SlowLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('slow_logs');

        // Search filters
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('sql', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('raw_sql', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->time) {
            $query->where('time', '>=', $request->time);
        }

        if ($request->time_max) {
            $query->where('time', '<=', $request->time_max);
        }

        if ($request->route_filter) {
            $query->where('route', 'LIKE', '%' . $request->route_filter . '%');
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('time', 'desc')->paginate(15);

        // Statistics
        $totalLogs = DB::table('slow_logs')->count();
        $maxTime = DB::table('slow_logs')->max('time');
        $avgTime = round(DB::table('slow_logs')->avg('time'), 2);
        $todayLogs = DB::table('slow_logs')->whereDate('created_at', today())->count();

        // Advanced statistics
        $slowestQueries = DB::table('slow_logs')
            ->select('sql', DB::raw('COUNT(*) as count'), DB::raw('AVG(time) as avg_time'))
            ->groupBy('sql')
            ->orderBy('avg_time', 'desc')
            ->limit(5)
            ->get();

        $logsByHour = DB::table('slow_logs')
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->get();

        $indexSuggestions = DB::table('index_suggestions')
            ->where('applied', false)
            ->orderBy('frequency', 'desc')
            ->get();

        return view('slow-logs', compact(
            'logs',
            'totalLogs',
            'maxTime',
            'avgTime',
            'todayLogs',
            'slowestQueries',
            'logsByHour',
            'indexSuggestions'
        ));
    }

    public function show($id)
    {
        $log = DB::table('slow_logs')->find($id);

        if (!$log) {
            abort(404);
        }

        return view('slow-log-detail', compact('log'));
    }

    public function destroy($id)
    {
        DB::table('slow_logs')->delete($id);
        return redirect()->route('slow-logs')->with('success', 'Log deleted successfully!');
    }

    public function clearAll()
    {
        DB::table('slow_logs')->truncate();
        return redirect()->route('slow-logs')->with('success', 'All logs cleared successfully!');
    }

    public function analyze($id)
    {
        $log = DB::table('slow_logs')->find($id);

        // Advanced analysis
        $analysis = [
            'has_order_by_random' => str_contains($log->sql, 'RAND()'),
            'has_like_wildcard' => str_contains($log->sql, 'LIKE \'%%'),
            'has_subquery' => preg_match('/\(SELECT/i', $log->sql),
            'suggested_indexes' => [],
        ];

        // Suggest indexes based on query
        preg_match_all('/WHERE\s+(\w+)\s*=/i', $log->sql, $whereColumns);

        foreach ($whereColumns[1] as $column) {
            if (!in_array($column, $analysis['suggested_indexes'])) {
                $analysis['suggested_indexes'][] = $column;
            }
        }

        preg_match_all('/ORDER BY\s+(\w+)/i', $log->sql, $orderColumns);

        foreach ($orderColumns[1] as $column) {
            if (!in_array($column, $analysis['suggested_indexes'])) {
                $analysis['suggested_indexes'][] = $column;
            }
        }

        DB::table('slow_logs')->where('id', $id)->update([
            'is_analyzed' => true,
            'recommendation' => $this->generateDetailedRecommendation($analysis, $log),
            'updated_at' => now(),
        ]);

        return redirect()->route('slow-logs')->with('analysis', $analysis);
    }

    private function generateDetailedRecommendation($analysis, $log)
    {
        $recommendations = [];

        if ($analysis['has_order_by_random']) {
            $recommendations[] = '❌ Avoid ORDER BY RAND() for large datasets. Use indexed pagination or cache random results.';
        }

        if ($analysis['has_like_wildcard']) {
            $recommendations[] = '⚠️ Leading wildcard in LIKE prevents index usage. Consider using full-text search.';
        }

        if ($analysis['has_subquery']) {
            $recommendations[] = '💡 Consider rewriting subquery as JOIN for better performance.';
        }

        if (!empty($analysis['suggested_indexes'])) {
            $recommendations[] = '📊 Suggested indexes: ' . implode(', ', $analysis['suggested_indexes']);
        }

        if (empty($recommendations)) {
            $recommendations[] = '✅ Query looks decent. Review database configuration and server resources.';
        }

        return implode(' | ', $recommendations);
    }
}