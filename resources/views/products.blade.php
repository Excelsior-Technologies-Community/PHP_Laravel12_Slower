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