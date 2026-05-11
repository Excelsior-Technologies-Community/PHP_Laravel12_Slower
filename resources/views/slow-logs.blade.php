<!DOCTYPE html>
<html>

<head>

    <title>Slow Query Dashboard</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            background:#0f172a;
            color:white;
        }

        .header{
            background:#1e293b;
            padding:25px;
            text-align:center;
            box-shadow:0 4px 15px rgba(0,0,0,0.3);
        }

        .header h1{
            margin-bottom:10px;
            font-size:34px;
        }

        .header p{
            color:#cbd5e1;
        }

        .container{
            width:90%;
            margin:40px auto;
        }

        .top-buttons{
            margin-bottom:25px;
        }

        .btn{
            background:linear-gradient(135deg,#2563eb,#0ea5e9);
            color:white;
            padding:12px 22px;
            text-decoration:none;
            border-radius:10px;
            display:inline-block;
            transition:0.3s ease;
            font-weight:600;
            box-shadow:0 5px 15px rgba(37,99,235,0.3);
        }

        .btn:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 18px rgba(14,165,233,0.4);
        }

        /* =======================
           STATS CARDS
        ======================= */

        .cards{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:#1e293b;
            padding:25px;
            border-radius:16px;
            text-align:center;
            box-shadow:0 6px 15px rgba(0,0,0,0.3);
            transition:0.3s;
        }

        .card:hover{
            transform:translateY(-5px);
        }

        .card p{
            color:#cbd5e1;
            margin-bottom:10px;
        }

        .card h2{
            color:#38bdf8;
            font-size:32px;
        }

        /* =======================
           SEARCH SECTION
        ======================= */

        .search-box{
            background:#1e293b;
            padding:20px;
            border-radius:16px;
            margin-bottom:25px;
            display:flex;
            gap:15px;
            align-items:center;
            flex-wrap:wrap;
            box-shadow:0 6px 20px rgba(0,0,0,0.3);
        }

        input{
            padding:13px;
            border:none;
            border-radius:10px;
            width:260px;
            background:#334155;
            color:white;
            outline:none;
        }

        input::placeholder{
            color:#cbd5e1;
        }

        button{
            padding:13px 20px;
            border:none;
            background:linear-gradient(135deg,#0ea5e9,#2563eb);
            color:white;
            border-radius:10px;
            cursor:pointer;
            font-weight:600;
            transition:0.3s;
        }

        button:hover{
            transform:translateY(-2px);
        }

        /* =======================
           TABLE
        ======================= */

        .table-wrapper{
            background:#1e293b;
            border-radius:16px;
            overflow:hidden;
            box-shadow:0 10px 25px rgba(0,0,0,0.4);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#334155;
            padding:16px;
            text-align:left;
            color:#f8fafc;
        }

        td{
            padding:16px;
            border-bottom:1px solid #334155;
            color:#e2e8f0;
        }

        tr{
            transition:0.3s;
        }

        tr:hover{
            background:#273549;
        }

        .query-box{
            max-width:500px;
            word-wrap:break-word;
            color:#cbd5e1;
            line-height:1.6;
        }

        /* =======================
           BADGES
        ======================= */

        .badge{
            background:#ef4444;
            color:white;
            padding:7px 14px;
            border-radius:20px;
            font-size:12px;
            font-weight:600;
        }

        .moderate{
            background:#f59e0b;
        }

        /* =======================
           PAGINATION
        ======================= */

        .custom-pagination{
            margin-top:35px;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-wrap:wrap;
            gap:10px;
        }

        .page-btn,
        .page-number{
            padding:12px 18px;
            text-decoration:none;
            border-radius:10px;
            background:#0f172a;
            color:white;
            font-weight:600;
            transition:0.3s ease;
            border:1px solid #334155;
            min-width:45px;
            text-align:center;
        }

        .page-btn:hover,
        .page-number:hover{
            background:#2563eb;
            transform:translateY(-2px);
            box-shadow:0 5px 12px rgba(37,99,235,0.4);
        }

        .page-number.active{
            background:linear-gradient(135deg,#2563eb,#0ea5e9);
            border:none;
            box-shadow:0 6px 15px rgba(14,165,233,0.4);
        }

        .page-btn.disabled{
            opacity:0.4;
            pointer-events:none;
        }

        .dots{
            color:#94a3b8;
            font-size:18px;
            padding:0 5px;
        }

        .empty{
            text-align:center;
            padding:30px;
            color:#cbd5e1;
        }

        /* =======================
           RESPONSIVE
        ======================= */

        @media(max-width:992px){

            .cards{
                grid-template-columns:repeat(2,1fr);
            }
        }

        @media(max-width:768px){

            .header h1{
                font-size:26px;
            }

            .cards{
                grid-template-columns:1fr;
            }

            .search-box{
                flex-direction:column;
                align-items:stretch;
            }

            input{
                width:100%;
            }

            .custom-pagination{
                gap:6px;
            }

            .page-btn,
            .page-number{
                padding:10px 14px;
                font-size:14px;
            }
        }

    </style>

</head>

<body>

    <div class="header">

        <h1>Slow Query Analytics Dashboard</h1>

        <p>Laravel Slower Performance Monitoring System</p>

    </div>

    <div class="container">

        <div class="top-buttons">

            <a href="/products" class="btn">
                ← Back To Products
            </a>

        </div>

        <!-- STATISTICS -->

        <div class="cards">

            <div class="card">

                <p>Total Slow Queries</p>

                <h2>{{ $totalLogs }}</h2>

            </div>

            <div class="card">

                <p>Highest Query Time</p>

                <h2>{{ $maxTime }} ms</h2>

            </div>

            <div class="card">

                <p>Average Query Time</p>

                <h2>{{ $avgTime }} ms</h2>

            </div>

            <div class="card">

                <p>Today's Logs</p>

                <h2>{{ $todayLogs }}</h2>

            </div>

        </div>

        <!-- SEARCH -->

        <form method="GET">

            <div class="search-box">

                <input
                    type="text"
                    name="search"
                    placeholder="Search SQL Query"
                    value="{{ request('search') }}"
                >

                <input
                    type="number"
                    name="time"
                    placeholder="Minimum Query Time"
                    value="{{ request('time') }}"
                >

                <button type="submit">
                    Filter Logs
                </button>

            </div>

        </form>

        <!-- TABLE -->

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>SQL Query</th>

                        <th>Query Time</th>

                        <th>Status</th>

                        <th>Created At</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($logs as $log)

                    <tr>

                        <td>{{ $log->id }}</td>

                        <td class="query-box">

                            {{ Str::limit($log->sql, 120) }}

                        </td>

                        <td>

                            {{ $log->time }} ms

                        </td>

                        <td>

                            @if($log->time > 500)

                                <span class="badge">
                                    Very Slow
                                </span>

                            @else

                                <span class="badge moderate">
                                    Moderate
                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $log->created_at }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="empty">

                            No slow logs found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PREMIUM PAGINATION -->

        <div class="custom-pagination">

            {{-- Previous Button --}}
            @if ($logs->onFirstPage())

                <span class="page-btn disabled">
                    ← Previous
                </span>

            @else

                <a href="{{ $logs->previousPageUrl() }}" class="page-btn">
                    ← Previous
                </a>

            @endif


            {{-- First Page --}}
            @if($logs->currentPage() > 3)

                <a href="{{ $logs->url(1) }}" class="page-number">
                    1
                </a>

                @if($logs->currentPage() > 4)

                    <span class="dots">...</span>

                @endif

            @endif


            {{-- Middle Pages --}}
            @foreach(range(
                max(1, $logs->currentPage() - 2),
                min($logs->lastPage(), $logs->currentPage() + 2)
            ) as $page)

                @if($page == $logs->currentPage())

                    <span class="page-number active">
                        {{ $page }}
                    </span>

                @else

                    <a href="{{ $logs->url($page) }}" class="page-number">
                        {{ $page }}
                    </a>

                @endif

            @endforeach


            {{-- Last Page --}}
            @if($logs->currentPage() < $logs->lastPage() - 2)

                @if($logs->currentPage() < $logs->lastPage() - 3)

                    <span class="dots">...</span>

                @endif

                <a href="{{ $logs->url($logs->lastPage()) }}" class="page-number">
                    {{ $logs->lastPage() }}
                </a>

            @endif


            {{-- Next Button --}}
            @if ($logs->hasMorePages())

                <a href="{{ $logs->nextPageUrl() }}" class="page-btn">
                    Next →
                </a>

            @else

                <span class="page-btn disabled">
                    Next →
                </span>

            @endif

        </div>

    </div>

</body>

</html>