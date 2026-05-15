<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Laravel Performance Monitor</title>
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
            line-height: 1.5;
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .stat-card:hover {
            border-color: #cbd5e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #718096;
            font-weight: 600;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-top: 0.25rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* Filter Bar */
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
            min-width: 120px;
        }

        .filter-group input:focus, .filter-group select:focus {
            outline: none;
            border-color: #3b82f6;
            ring: 2px solid #93c5fd;
        }

        /* Table */
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

        .price-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            background: #dcfce7;
            color: #166534;
            border-radius: 9999px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .slow-badge {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Pagination */
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

        .pagination a:hover {
            background: #f3f4f6;
        }

        .pagination .active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Execution Time */
        .exec-time {
            font-size: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .exec-time.fast {
            background: #dcfce7;
            color: #166534;
        }

        .exec-time.slow {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .filter-bar {
                flex-direction: column;
            }

            .filter-group input, .filter-group select {
                min-width: auto;
            }

            th, td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1> Product Catalog</h1>
        
    </div>

    <div class="container">
       

        <!-- Action Buttons -->
        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
            <a href="{{ route('slow-logs') }}" class="btn btn-secondary"> View Slow Logs</a>
            <a href="?simulate_slow=1&sort=random" class="btn btn-danger"> Simulate Slow Query</a>
        </div>

        <!-- Filter Bar -->
        <form method="GET" class="filter-bar">
            <div class="filter-group">
                <label>Search</label>
                <input type="text" name="search" placeholder="Product name..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label>Min Price</label>
                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
            </div>
            <div class="filter-group">
                <label>Max Price</label>
                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
            </div>
            <div class="filter-group">
                <label>Sort By</label>
                <select name="sort">
                    <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    <option value="random" {{ request('sort') == 'random' ? 'selected' : '' }}>Random (Slow)</option>
                </select>
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>

        <!-- Execution Time Warning -->
        @if(isset($isHeavy) && $isHeavy)
            <div class="exec-time slow" style="display: block; margin-bottom: 1rem;">
                 This query used ORDER BY RAND() which is slow on large datasets!
            </div>
        @elseif($executionTime > 100)
            <div class="exec-time slow" style="display: block; margin-bottom: 1rem;">
                 Query took {{ number_format($executionTime, 2) }} ms - This has been logged for analysis.
            </div>
        @endif

        <!-- Products Table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td><span class="price-badge">₹{{ number_format($product->price, 2) }}</span></td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 3rem;">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pagination">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</body>
</html>