<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slow Logs - Performance Monitor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            color: #1a202c;
        }

        .header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
        }

        .header p {
            color: #718096;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }

        .stat-card .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #718096;
            font-weight: 600;
        }

        .stat-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-top: 0.25rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            cursor: pointer;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            border: none;
        }

        .filter-bar {
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .filter-group label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #4a5568;
        }

        .filter-group input, .filter-group select {
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .table-wrapper {
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 0.875rem 1rem;
            background: #f9fafb;
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid #f3f4f6;
            color: #4b5563;
        }

        tr:hover {
            background: #f9fafb;
        }

        .query-code {
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.75rem;
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 0.25rem;
            max-width: 500px;
            overflow-x: auto;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-size: 0.875rem;
            color: #4b5563;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .pagination .active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .filter-bar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1> Slow Query Analytics</h1>
        <p>Monitor and optimize database performance</p>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
            <a href="{{ route('products') }}" class="btn">← Back to Products</a>
            <form action="{{ route('slow-logs.clear') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('Clear all logs?')">Clear All Logs</button>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Slow Queries</div>
                <div class="value">{{ number_format($totalLogs) }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Highest Time</div>
                <div class="value">{{ number_format($maxTime) }} ms</div>
            </div>
            <div class="stat-card">
                <div class="label">Average Time</div>
                <div class="value">{{ number_format($avgTime) }} ms</div>
            </div>
            <div class="stat-card">
                <div class="label">Today's Logs</div>
                <div class="value">{{ number_format($todayLogs) }}</div>
            </div>
        </div>

        <!-- Index Suggestions -->
        @if($indexSuggestions->count() > 0)
            <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                <strong> Index Suggestions:</strong>
                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                    @foreach($indexSuggestions->take(3) as $suggestion)
                        <li>Consider adding index on <code>{{ $suggestion->column_name }}</code> (appeared {{ $suggestion->frequency }} times)</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Slowest Queries Summary -->
        @if($slowestQueries->count() > 0)
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 1rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 0.875rem; margin-bottom: 0.75rem;"> Top 5 Slowest Queries</h3>
                @foreach($slowestQueries as $query)
                    <div style="font-size: 0.75rem; padding: 0.5rem; border-bottom: 1px solid #f0f0f0;">
                        <code>{{ \Illuminate\Support\Str::limit($query->sql, 100) }}</code>
                        <span style="float: right;">
                            <span class="badge badge-danger">{{ round($query->avg_time) }} ms avg</span>
                            <span class="badge badge-warning">{{ $query->count }}x</span>
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Filter Bar -->
        <form method="GET" class="filter-bar">
            <div class="filter-group">
                <label>Search SQL</label>
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>Min Time (ms)</label>
                <input type="number" name="time" placeholder="Min" value="{{ request('time') }}">
            </div>
            <div class="filter-group">
                <label>Max Time (ms)</label>
                <input type="number" name="time_max" placeholder="Max" value="{{ request('time_max') }}">
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>

        <!-- Logs Table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SQL Query</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>
                            <div class="query-code">
                                {{ \Illuminate\Support\Str::limit($log->sql, 80) }}
                            </div>
                            @if($log->recommendation)
                                <div style="font-size: 0.7rem; color: #718096; margin-top: 0.25rem;">
                                    {{ $log->recommendation }}
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $log->time }} ms</strong></td>
                        <td>
                            @if($log->time > 500)
                                <span class="badge badge-danger">Critical</span>
                            @elseif($log->time > 200)
                                <span class="badge badge-warning">Warning</span>
                            @else
                                <span class="badge badge-success">OK</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('slow-logs.show', $log->id) }}" style="text-decoration: none; color: #3b82f6;">View</a>
                            <form action="{{ route('slow-logs.destroy', $log->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; margin-left: 0.5rem;" onclick="return confirm('Delete this log?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem;">No slow logs recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="pagination">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</body>
</html>