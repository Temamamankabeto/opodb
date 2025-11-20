<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Plan Display - Excel Style</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        header {
            background-color: #1e6f5c;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .controls {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            background-color: #f0f0f0;
            border-bottom: 1px solid #ddd;
        }
        
        .search-box {
            display: flex;
            align-items: center;
        }
        
        .search-box input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
            width: 250px;
        }
        
        .buttons button {
            padding: 8px 15px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: #1e6f5c;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .table-container {
            overflow-x: auto;
            padding: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: center;
            font-size: 14px;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .header-row-1 {
            background-color: #e8f4f1;
        }
        
        .header-row-2 {
            background-color: #d4eae4;
        }
        
        .row-header {
            background-color: #f9f9f9;
            font-weight: 500;
        }
        
        .data-cell {
            background-color: white;
        }
        
        .data-cell:hover {
            background-color: #f0f8ff;
        }
        
        .highlight {
            background-color: #fffacd;
        }
        
        .quarter-header {
            background-color: #d4eae4;
            font-weight: 600;
        }
        
        .month-header {
            background-color: #e8f4f1;
            font-weight: 500;
        }
        
        .footer {
            padding: 15px 20px;
            background-color: #f0f0f0;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        
        .btn-edit {
            background-color: #ffc107;
            color: black;
            padding: 4px 8px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 4px 8px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .buttons {
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Crop Plan Display - Excel Style</h1>
            <div class="subtitle">Oromia Region - Agricultural Development Plan</div>
        </header>
        
        <div class="controls">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search in plan...">
                <button id="searchBtn" class="btn-primary">Search</button>
            </div>
            <div class="buttons">
                <a href="{{ route('sector.crop-plans.create') }}" class="btn-primary" style="text-decoration: none; display: inline-block;">Add New Plan</a>
                <a href="{{ route('sector.crop-plans.export') }}" class="btn-primary" style="text-decoration: none; display: inline-block;">Export to Excel</a>
                <button class="btn-secondary" id="printBtn">Print</button>
            </div>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr class="header-row-1">
                        <th rowspan="3">ID</th>
                        <th rowspan="3">Crop Type</th>
                        <th rowspan="3">Crop Name</th>
                        <th rowspan="3">Unit</th>
                        <th rowspan="3">Previous Year</th>
                        <th colspan="12">Planning Division - {{ now()->year }}</th>
                        <th rowspan="3">Actions</th>
                    </tr>
                    <tr class="header-row-2">
                        <th colspan="3">Quarter 1</th>
                        <th colspan="3">Quarter 2</th>
                        <th colspan="3">Quarter 3</th>
                        <th colspan="3">Quarter 4</th>
                    </tr>
                    <tr class="month-header">
                        <!-- Quarter 1 -->
                        <th>Month 1</th>
                        <th>Month 2</th>
                        <th>Month 3</th>
                        <!-- Quarter 2 -->
                        <th>Month 4</th>
                        <th>Month 5</th>
                        <th>Month 6</th>
                        <!-- Quarter 3 -->
                        <th>Month 7</th>
                        <th>Month 8</th>
                        <th>Month 9</th>
                        <!-- Quarter 4 -->
                        <th>Month 10</th>
                        <th>Month 11</th>
                        <th>Month 12</th>
                    </tr>
                </thead>
                <tbody id="planData">
                    @foreach($transformedPlans as $plan)
                    <tr>
                        <td class="row-header">{{ $plan['id'] }}</td>
                        <td class="row-header">{{ $plan['crop_type'] }}</td>
                        <td class="row-header">{{ $plan['crop_name'] }}</td>
                        <td class="row-header">{{ $plan['unit'] }}</td>
                        <td class="data-cell">{{ number_format($plan['year']) }}</td>
                        
                        <!-- Quarter 1 Data -->
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_1']['month_1'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_1']['month_2'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_1']['month_3'] ?? 0) }}</td>
                        
                        <!-- Quarter 2 Data -->
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_2']['month_4'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_2']['month_5'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_2']['month_6'] ?? 0) }}</td>
                        
                        <!-- Quarter 3 Data -->
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_3']['month_7'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_3']['month_8'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_3']['month_9'] ?? 0) }}</td>
                        
                        <!-- Quarter 4 Data -->
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_4']['month_10'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_4']['month_11'] ?? 0) }}</td>
                        <td class="data-cell">{{ number_format($plan['plandivision_details']['quarter_4']['month_12'] ?? 0) }}</td>
                        
                        <td class="action-buttons">
                            <a href="{{ route('sector.crop-plans.activityplan', $plan['id']) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('sector.crop-plans.destroy', $plan['id']) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>Plan Data Last Updated: {{ now()->format('F j, Y') }} | Displaying {{ count($transformedPlans) }} records</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.toLowerCase();
                const dataCells = document.querySelectorAll('.data-cell, .row-header');
                
                // Remove previous highlights
                dataCells.forEach(cell => {
                    cell.classList.remove('highlight');
                });
                
                // Search and highlight
                if (searchTerm) {
                    dataCells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            cell.classList.add('highlight');
                        }
                    });
                }
            });
            
            // Print functionality
            const printBtn = document.getElementById('printBtn');
            printBtn.addEventListener('click', function() {
                window.print();
            });
            
            // Enter key for search
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchBtn.click();
                }
            });
        });
    </script>
</body>
</html>