<!DOCTYPE html>
<html>

<head>

    <title>Laravel Slower Products</title>

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
            font-size:15px;
        }

        .container{
            width:90%;
            margin:40px auto;
        }

        .top-buttons{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
            flex-wrap:wrap;
            gap:15px;
        }

        .btn{
            background:linear-gradient(135deg,#2563eb,#0ea5e9);
            color:white;
            padding:12px 22px;
            text-decoration:none;
            border-radius:10px;
            transition:0.3s ease;
            font-weight:600;
            box-shadow:0 5px 15px rgba(37,99,235,0.3);
        }

        .btn:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 18px rgba(14,165,233,0.4);
        }

        .table-card{
            background:#1e293b;
            padding:25px;
            border-radius:16px;
            overflow-x:auto;
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
            font-size:15px;
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

        .price{
            background:#10b981;
            padding:7px 14px;
            border-radius:20px;
            font-size:13px;
            font-weight:600;
            display:inline-block;
        }

        .footer{
            text-align:center;
            padding:25px;
            color:#94a3b8;
            font-size:14px;
        }

        svg{
            width:14px;
        }

        /* =========================
           PREMIUM PAGINATION
        ========================== */

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

        @media(max-width:768px){

            .header h1{
                font-size:26px;
            }

            .top-buttons{
                flex-direction:column;
                align-items:stretch;
            }

            .btn{
                text-align:center;
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

        <h1>Laravel 12 Slower Monitoring</h1>

        <p>Product Performance Testing System</p>

    </div>

    <div class="container">

        <div class="top-buttons">

            <a href="/products" class="btn">
                Product List
            </a>

            <a href="/slow-logs" class="btn">
                View Slow Logs Dashboard
            </a>

        </div>

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

                        <td>

                            <span class="price">
                                ₹{{ $product->price }}
                            </span>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <!-- PREMIUM PAGINATION -->

            <div class="custom-pagination">

                {{-- Previous Button --}}
                @if ($products->onFirstPage())

                    <span class="page-btn disabled">
                        ← Previous
                    </span>

                @else

                    <a href="{{ $products->previousPageUrl() }}" class="page-btn">
                        ← Previous
                    </a>

                @endif


                {{-- First Page --}}
                @if($products->currentPage() > 3)

                    <a href="{{ $products->url(1) }}" class="page-number">
                        1
                    </a>

                    @if($products->currentPage() > 4)

                        <span class="dots">...</span>

                    @endif

                @endif


                {{-- Dynamic Middle Pages --}}
                @foreach(range(
                    max(1, $products->currentPage() - 2),
                    min($products->lastPage(), $products->currentPage() + 2)
                ) as $page)

                    @if($page == $products->currentPage())

                        <span class="page-number active">
                            {{ $page }}
                        </span>

                    @else

                        <a href="{{ $products->url($page) }}" class="page-number">
                            {{ $page }}
                        </a>

                    @endif

                @endforeach


                {{-- Last Page --}}
                @if($products->currentPage() < $products->lastPage() - 2)

                    @if($products->currentPage() < $products->lastPage() - 3)

                        <span class="dots">...</span>

                    @endif

                    <a href="{{ $products->url($products->lastPage()) }}" class="page-number">
                        {{ $products->lastPage() }}
                    </a>

                @endif


                {{-- Next Button --}}
                @if ($products->hasMorePages())

                    <a href="{{ $products->nextPageUrl() }}" class="page-btn">
                        Next →
                    </a>

                @else

                    <span class="page-btn disabled">
                        Next →
                    </span>

                @endif

            </div>

        </div>

    </div>

    <div class="footer">

        © {{ date('Y') }} Laravel Slower Enhanced Project

    </div>

</body>

</html>