<x-layout>

    <div class="container">
        <h2>Fooyya’insa dhaabbilee	</h2>

        <form method="GET" action="{{ url()->current() }}" class="row mb-4">
            <div class="col-md-3">
                <label>Zone</label>
                <select name="zone_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Zones</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ $zone->id == $zoneId ? 'selected' : '' }}>
                            {{ $zone->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Woreda</label>
                <select name="woreda_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Woredas</option>
                    @foreach($woredas as $woreda)
                        <option value="{{ $woreda->id }}" {{ $woreda->id == $woredaId ? 'selected' : '' }}>
                            {{ $woreda->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Kebele</label>
                <select name="kebele_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Kebeles</option>
                    @foreach($kebeles as $kebele)
                        <option value="{{ $kebele->id }}" {{ $kebele->id == $kebeleId ? 'selected' : '' }}>
                            {{ $kebele->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Year</label>
                <select name="year" class="form-control" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Main Category</th>
                <th>Sub Category</th>
                <th>Service Name</th>
                <th>Year</th>
                <th>Total Cost</th>
            </tr>
            </thead>
            <tbody>
            @forelse($query as $item)
                <tr>
                    <td>{{ $item->main_category }}</td>
                    <td>{{ $item->sub_category }}</td>
                    <td>{{ $item->service_name }}</td>
                    <td>{{ $item->year }}</td>
                    <td>{{ number_format($item->total_cost, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No data found for the selected filters.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{-- Charts --}}
        <div class="row mt-5">
            <div class="col-md-6">
                <h5>Bar Chart: Total Cost per Main Category</h5>
                <canvas id="barChart"></canvas>
            </div>
            <div class="col-md-6">
                <h5>Line Chart: Total Cost over Years</h5>
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const barCtx = document.getElementById('barChart');
        const lineCtx = document.getElementById('lineChart');

        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['bar']->keys()) !!},
                datasets: [{
                    label: 'Total Cost',
                    data: {!! json_encode($chartData['bar']->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['line']->keys()) !!},
                datasets: [{
                    label: 'Total Cost',
                    data: {!! json_encode($chartData['line']->values()) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.4)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    </div>
</x-layout>
