<x-layout>
    <div class="container">
        <h2 class="mb-4">Woreda Livestock Supply Analysis Report</h2>

        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Year</label>
                <select name="year" class="form-select">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <!-- Totals Table -->
        <div class="table-responsive mb-5">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                <tr>
                    <th>Activity</th>
                    <th>{{ $selectedYear }}</th>
                    <th>{{ $selectedYear - 1 }}</th>
                </tr>
                </thead>
                <tbody>
                <tr><td>AI Cows Baseline</td><td>{{ $totals['ai_cows_baseline'] }}</td><td>{{ $previousTotals['ai_cows_baseline'] }}</td></tr>
                <tr><td>AI Cows Plan</td><td>{{ $totals['ai_cows_plan'] }}</td><td>{{ $previousTotals['ai_cows_plan'] }}</td></tr>
                <tr><td>One Day Chick Plan</td><td>{{ $totals['one_day_chick_plan'] }}</td><td>{{ $previousTotals['one_day_chick_plan'] }}</td></tr>
                <tr><td>21-Day Chicks Baseline</td><td>{{ $totals['twentyone_days_chicks_baseline'] }}</td><td>{{ $previousTotals['twentyone_days_chicks_baseline'] }}</td></tr>
                <tr><td>21-Day Chicks Plan</td><td>{{ $totals['twentyone_days_chicks_plan'] }}</td><td>{{ $previousTotals['twentyone_days_chicks_plan'] }}</td></tr>
                <tr><td>Fry Fish Baseline</td><td>{{ $totals['fry_fish_baseline'] }}</td><td>{{ $previousTotals['fry_fish_baseline'] }}</td></tr>
                <tr><td>Fry Fish Plan</td><td>{{ $totals['fry_fish_plan'] }}</td><td>{{ $previousTotals['fry_fish_plan'] }}</td></tr>
                <tr><td>Beehives Baseline</td><td>{{ $totals['beehives_baseline'] }}</td><td>{{ $previousTotals['beehives_baseline'] }}</td></tr>
                <tr><td>Beehives Plan</td><td>{{ $totals['beehives_plan'] }}</td><td>{{ $previousTotals['beehives_plan'] }}</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Chart -->
        <canvas id="livestockChart" height="130"></canvas>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('livestockChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    'AI Cows Baseline',
                    'AI Cows Plan',
                    'One Day Chick Plan',
                    '21-Day Chicks Baseline',
                    '21-Day Chicks Plan',
                    'Fry Fish Baseline',
                    'Fry Fish Plan',
                    'Beehives Baseline',
                    'Beehives Plan'
                ],
                datasets: [
                    {
                        label: '{{ $selectedYear }}',
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        data: [
                            {{ $totals['ai_cows_baseline'] }},
                            {{ $totals['ai_cows_plan'] }},
                            {{ $totals['one_day_chick_plan'] }},
                            {{ $totals['twentyone_days_chicks_baseline'] }},
                            {{ $totals['twentyone_days_chicks_plan'] }},
                            {{ $totals['fry_fish_baseline'] }},
                            {{ $totals['fry_fish_plan'] }},
                            {{ $totals['beehives_baseline'] }},
                            {{ $totals['beehives_plan'] }}
                        ]
                    },
                    {
                        label: '{{ $selectedYear - 1 }}',
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        data: [
                            {{ $previousTotals['ai_cows_baseline'] }},
                            {{ $previousTotals['ai_cows_plan'] }},
                            {{ $previousTotals['one_day_chick_plan'] }},
                            {{ $previousTotals['twentyone_days_chicks_baseline'] }},
                            {{ $previousTotals['twentyone_days_chicks_plan'] }},
                            {{ $previousTotals['fry_fish_baseline'] }},
                            {{ $previousTotals['fry_fish_plan'] }},
                            {{ $previousTotals['beehives_baseline'] }},
                            {{ $previousTotals['beehives_plan'] }}
                        ]
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Livestock Supply: {{ $selectedYear }} vs {{ $selectedYear - 1 }}'
                    },
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-layout>
