<x-layout>
    <title>Construction Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
        }

        .filter-form {
            display: flex;
            gap: 20px;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .filter-form select,
        .filter-form button {
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .chart-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            text-align: center;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
        }

        .badge-performance {
            background-color: #27ae60;
        }

        .badge-plan {
            background-color: #e67e22;
        }
    </style>

    <div class="container">
        <h2>Construction Report</h2>

        <form method="GET" class="filter-form">
            <select name="baseline_year" required>
                @for($y = 2015; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ $baselineYear == $y ? 'selected' : '' }}>{{ $y }} EFY</option>
                @endfor
            </select>

            <select name="plan_year" required>
                @for($y = 2015; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $planYear == $y ? 'selected' : '' }}>{{ $y }} EFY</option>
                @endfor
            </select>

            <button type="submit">Filter</button>
        </form>
        <table>
            <thead>
            <tr>
                <th>Service</th>
                <th>Type</th>
                <th>Year</th>
                <th>Count</th>
                <th>Cost (ETB)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $service => $entries)
                @foreach($entries as $entry)
                    <tr>
                        <td>{{ $service }}</td>
                        <td>
                                <span class="badge badge-{{ $entry->type == 'performance' ? 'performance' : 'plan' }}">
                                    {{ ucfirst($entry->type) }}
                                </span>
                        </td>
                        <td>{{ $entry->year }}</td>
                        <td>{{ $entry->count }}</td>
                        <td>{{ number_format($entry->cost, 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
        <div class="chart-box">
            <canvas id="constructionChart"></canvas>
        </div>


    </div>

    <script>
        const ctx = document.getElementById('constructionChart').getContext('2d');

        const chartData = {
            labels: {!! json_encode($data->keys()) !!},
            datasets: [
                {
                    label: 'Performance (Count)',
                    data: [
                        @foreach($data as $entries)
                            {{ $entries->where('type', 'performance')->sum('count') }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(39, 174, 96, 0.7)',
                    borderColor: '#27ae60',
                    borderWidth: 1
                },
                {
                    label: 'Plan (Count)',
                    data: [
                        @foreach($data as $entries)
                            {{ $entries->where('type', 'plan')->sum('count') }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(230, 126, 34, 0.7)',
                    borderColor: '#e67e22',
                    borderWidth: 1
                }
            ]
        };

        new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Service-wise Construction Participation and Plan'
                    }
                }
            }
        });
    </script>
</x-layout>
