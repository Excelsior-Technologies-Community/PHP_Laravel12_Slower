<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SlowLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('slow_logs');

        /*
        |--------------------------------------------------------------------------
        | Search SQL Query
        |--------------------------------------------------------------------------
        */

        if ($request->search) {

            $query->where('sql', 'LIKE', '%' . $request->search . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Filter By Query Time
        |--------------------------------------------------------------------------
        */

        if ($request->time) {

            $query->where('time', '>=', $request->time);
        }

        /*
        |--------------------------------------------------------------------------
        | Latest Logs
        |--------------------------------------------------------------------------
        */

        $logs = $query->orderBy('id', 'asc')->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalLogs = DB::table('slow_logs')->count();

        $maxTime = DB::table('slow_logs')->max('time');

        $avgTime = round(DB::table('slow_logs')->avg('time'), 2);

        $todayLogs = DB::table('slow_logs')
            ->whereDate('created_at', today())
            ->count();

        return view('slow-logs', compact(
            'logs',
            'totalLogs',
            'maxTime',
            'avgTime',
            'todayLogs'
        ));
    }
}