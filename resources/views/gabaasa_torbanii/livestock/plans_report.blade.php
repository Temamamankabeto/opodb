<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-cow fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Livestock Report') }}</h1>
                        <small class="text-dark">{{ __('Livestock supply and product analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare supply data for charts
                $supplyTypes = array_keys($supplyData['current'] ?? []);
                $supplyAmounts = array_values($supplyData['current'] ?? []);
                $previousSupplyAmounts = [];
                
                foreach ($supplyTypes as $type) {
                    $previousSupplyAmounts[] = $supplyData['previous'][$type] ?? 0;
                }
                
                // Prepare product data for charts
                $productTypes = array_keys($productData['current'] ?? []);
                $productAmounts = array_values($productData['current'] ?? []);
                $previousProductAmounts = [];
                
                foreach ($productTypes as $type) {
                    $previousProductAmounts[] = $productData['previous'][$type] ?? 0;
                }
                
                // Combined data for overall view
                $allTypes = array_unique(array_merge($supplyTypes, $productTypes));
                $combinedCurrentAmounts = [];
                $combinedPreviousAmounts = [];
                
                foreach ($allTypes as $type) {
                    $combinedCurrentAmounts[$type] = 
                        ($supplyData['current'][$type] ?? 0) + 
                        ($productData['current'][$type] ?? 0);
                    $combinedPreviousAmounts[$type] = 
                        ($supplyData['previous'][$type] ?? 0) + 
                        ($productData['previous'][$type] ?? 0);
                }
                
                arsort($combinedCurrentAmounts);
                $combinedTypes = array_keys($combinedCurrentAmounts);
                $combinedCurrentAmounts = array_values($combinedCurrentAmounts);
                $combinedPreviousAmounts = array_values($combinedPreviousAmounts);
            @endphp

            <!-- Year Filter -->
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-1"></i>
                        {{ __('Filter Options') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="row align-items-center">
                        <div class="col-md-3">
                            <label class="mb-1">{{ __('Year') }}</label>
                            <select name="year" class="form-control form-control-sm">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter mr-1"></i> {{ __('Apply Filter') }}
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-light btn-sm border">
                                <i class="fas fa-redo mr-1"></i> {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($totalCurrent) }}</h3>
                            <p>{{ __('Total Items') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ number_format($currentSupplyTotal) }}</h3>
                            <p>{{ __('Supply Items') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentProductTotal) }}</h3>
                            <p>{{ __('Product Items') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($totalChange, 1) }}%</h3>
                            <p>{{ __('Year-over-Year Change') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Supply Items Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Supply Items Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($supplyTypes) > 0)
                                <canvas id="supplyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-truck-loading fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No supply data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Product Items Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Product Items Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($productTypes) > 0)
                                <canvas id="productChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No product data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Supply vs Product Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Supply vs Product Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="comparisonChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Top Items Overall Pie Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Top Items Overall') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($combinedTypes) > 0)
                                <canvas id="topItemsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No data for pie chart') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Report Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('Detailed Livestock Report') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Kebele') }}</th>
                                    <th colspan="2" class="text-center">{{ $selectedYear }} ({{ __('Supply') }})</th>
                                    <th colspan="2" class="text-center">{{ $selectedYear }} ({{ __('Product') }})</th>
                                    <th colspan="2" class="text-center">{{ $previousYear }} ({{ __('Supply') }})</th>
                                    <th colspan="2" class="text-center">{{ $previousYear }} ({{ __('Product') }})</th>
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < 4; $i++)
                                        <th>{{ __('Total Amount') }}</th>
                                        <th>{{ __('Number of Items') }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Current Year Supply -->
                                        <td class="text-center">{{ number_format($data['current']['supply']['amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['supply']['items'] ?? 0) }}</td>

                                        <!-- Current Year Product -->
                                        <td class="text-center">{{ number_format($data['current']['product']['amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['product']['items'] ?? 0) }}</td>

                                        <!-- Previous Year Supply -->
                                        <td class="text-center">{{ number_format($data['previous']['supply']['amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['supply']['items'] ?? 0) }}</td>

                                        <!-- Previous Year Product -->
                                        <td class="text-center">{{ number_format($data['previous']['product']['amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['product']['items'] ?? 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>{{ __('No data found for selected year') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($reportData) > 0)
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="fw-bold">{{ __('TOTAL') }}</td>
                                    
                                    <!-- Current Year Supply Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentSupplyTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'supply_items')) ?? 0) }}</td>
                                    
                                    <!-- Current Year Product Totals -->
                                    <td class="text-center fw-bold">{{ number_format($currentProductTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'current'), 'product_items')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Supply Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousSupplyTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'supply_items')) ?? 0) }}</td>
                                    
                                    <!-- Previous Year Product Totals -->
                                    <td class="text-center fw-bold">{{ number_format($previousProductTotal) }}</td>
                                    <td class="text-center fw-bold">{{ number_format(array_sum(array_column(array_column($reportData, 'previous'), 'product_items')) ?? 0) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Supply Items Details -->
            @if(count($supplyData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-truck-loading mr-1"></i>
                        {{ __('Supply Items Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Supply Item') }}</th>
                                    <th class="text-right">{{ __('Current Year') }} ({{ $selectedYear }})</th>
                                    <th class="text-right">{{ __('Previous Year') }} ({{ $previousYear }})</th>
                                    <th class="text-right">{{ __('Change') }}</th>
                                    <th class="text-right">{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentSupplyTypes = array_keys($supplyData['current']);
                                    $currentSupplyAmounts = array_values($supplyData['current']);
                                    $previousSupplyAmounts = [];
                                    
                                    foreach ($currentSupplyTypes as $type) {
                                        $previousSupplyAmounts[] = $supplyData['previous'][$type] ?? 0;
                                    }
                                    
                                    $totalCurrentSupply = array_sum($currentSupplyAmounts);
                                    $totalPreviousSupply = array_sum($previousSupplyAmounts);
                                @endphp
                                @foreach($currentSupplyTypes as $index => $type)
                                    @php
                                        $currentAmount = $currentSupplyAmounts[$index] ?? 0;
                                        $previousAmount = $previousSupplyAmounts[$index] ?? 0;
                                        $change = $currentAmount - $previousAmount;
                                        $changePercent = $previousAmount > 0 ? ($change / $previousAmount) * 100 : ($currentAmount > 0 ? 100 : 0);
                                        $currentPercent = $totalCurrentSupply > 0 ? ($currentAmount / $totalCurrentSupply) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">
                                            {{ number_format($currentAmount) }}
                                            <small class="text-muted">({{ number_format($currentPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($previousAmount) }}</td>
                                        <td class="text-right {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $change >= 0 ? '+' : '' }}{{ number_format($change) }}
                                        </td>
                                        <td class="text-right {{ $changePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $changePercent >= 0 ? '+' : '' }}{{ number_format($changePercent, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL SUPPLY') }}</td>
                                    <td class="text-right">{{ number_format($totalCurrentSupply) }}</td>
                                    <td class="text-right">{{ number_format($totalPreviousSupply) }}</td>
                                    @php
                                        $totalSupplyChange = $totalCurrentSupply - $totalPreviousSupply;
                                        $totalSupplyChangePercent = $totalPreviousSupply > 0 ? ($totalSupplyChange / $totalPreviousSupply) * 100 : ($totalCurrentSupply > 0 ? 100 : 0);
                                    @endphp
                                    <td class="text-right {{ $totalSupplyChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalSupplyChange >= 0 ? '+' : '' }}{{ number_format($totalSupplyChange) }}
                                    </td>
                                    <td class="text-right {{ $totalSupplyChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalSupplyChangePercent >= 0 ? '+' : '' }}{{ number_format($totalSupplyChangePercent, 1) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Product Items Details -->
            @if(count($productData['current']) > 0)
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-boxes mr-1"></i>
                        {{ __('Product Items Details') }} ({{ $selectedYear }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Product Item') }}</th>
                                    <th class="text-right">{{ __('Current Year') }} ({{ $selectedYear }})</th>
                                    <th class="text-right">{{ __('Previous Year') }} ({{ $previousYear }})</th>
                                    <th class="text-right">{{ __('Change') }}</th>
                                    <th class="text-right">{{ __('Change %') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentProductTypes = array_keys($productData['current']);
                                    $currentProductAmounts = array_values($productData['current']);
                                    $previousProductAmounts = [];
                                    
                                    foreach ($currentProductTypes as $type) {
                                        $previousProductAmounts[] = $productData['previous'][$type] ?? 0;
                                    }
                                    
                                    $totalCurrentProduct = array_sum($currentProductAmounts);
                                    $totalPreviousProduct = array_sum($previousProductAmounts);
                                @endphp
                                @foreach($currentProductTypes as $index => $type)
                                    @php
                                        $currentAmount = $currentProductAmounts[$index] ?? 0;
                                        $previousAmount = $previousProductAmounts[$index] ?? 0;
                                        $change = $currentAmount - $previousAmount;
                                        $changePercent = $previousAmount > 0 ? ($change / $previousAmount) * 100 : ($currentAmount > 0 ? 100 : 0);
                                        $currentPercent = $totalCurrentProduct > 0 ? ($currentAmount / $totalCurrentProduct) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">
                                            {{ number_format($currentAmount) }}
                                            <small class="text-muted">({{ number_format($currentPercent, 1) }}%)</small>
                                        </td>
                                        <td class="text-right">{{ number_format($previousAmount) }}</td>
                                        <td class="text-right {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $change >= 0 ? '+' : '' }}{{ number_format($change) }}
                                        </td>
                                        <td class="text-right {{ $changePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $changePercent >= 0 ? '+' : '' }}{{ number_format($changePercent, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2">{{ __('TOTAL PRODUCT') }}</td>
                                    <td class="text-right">{{ number_format($totalCurrentProduct) }}</td>
                                    <td class="text-right">{{ number_format($totalPreviousProduct) }}</td>
                                    @php
                                        $totalProductChange = $totalCurrentProduct - $totalPreviousProduct;
                                        $totalProductChangePercent = $totalPreviousProduct > 0 ? ($totalProductChange / $totalPreviousProduct) * 100 : ($totalCurrentProduct > 0 ? 100 : 0);
                                    @endphp
                                    <td class="text-right {{ $totalProductChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalProductChange >= 0 ? '+' : '' }}{{ number_format($totalProductChange) }}
                                    </td>
                                    <td class="text-right {{ $totalProductChangePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalProductChangePercent >= 0 ? '+' : '' }}{{ number_format($totalProductChangePercent, 1) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>

<!-- Add Chart.js -->
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Prepare data from PHP variables
    const selectedYear = @json($selectedYear);
    const previousYear = @json($previousYear);
    
    const currentSupplyTotal = @json($currentSupplyTotal);
    const currentProductTotal = @json($currentProductTotal);
    const previousSupplyTotal = @json($previousSupplyTotal);
    const previousProductTotal = @json($previousProductTotal);
    
    // Supply data
    const supplyTypes = @json($supplyTypes);
    const supplyAmounts = @json($supplyAmounts);
    const previousSupplyAmounts = @json($previousSupplyAmounts);
    
    // Product data
    const productTypes = @json($productTypes);
    const productAmounts = @json($productAmounts);
    const previousProductAmounts = @json($previousProductAmounts);
    
    // Combined data
    const combinedTypes = @json($combinedTypes);
    const combinedCurrentAmounts = @json($combinedCurrentAmounts);
    const combinedPreviousAmounts = @json($combinedPreviousAmounts);

    // Color palette
    const itemColors = [
        'rgba(54, 162, 235, 0.7)',   // Blue
        'rgba(75, 192, 192, 0.7)',   // Teal
        'rgba(255, 99, 132, 0.7)',   // Pink
        'rgba(255, 159, 64, 0.7)',   // Orange
        'rgba(255, 205, 86, 0.7)',   // Yellow
        'rgba(153, 102, 255, 0.7)',  // Purple
        'rgba(201, 203, 207, 0.7)',  // Gray
        'rgba(50, 205, 50, 0.7)',    // Lime Green
        'rgba(255, 69, 0, 0.7)',     // Red Orange
        'rgba(138, 43, 226, 0.7)',   // Blue Violet
    ];

    // Chart 1: Supply Items Distribution
    if (supplyTypes.length > 0) {
        const supplyCtx = document.getElementById('supplyChart').getContext('2d');
        new Chart(supplyCtx, {
            type: 'bar',
            data: {
                labels: supplyTypes,
                datasets: [{
                    label: `{{ __('Supply Items') }} (${selectedYear})`,
                    data: supplyAmounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ __('Amount') }}'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = supplyAmounts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Product Items Distribution
    if (productTypes.length > 0) {
        const productCtx = document.getElementById('productChart').getContext('2d');
        new Chart(productCtx, {
            type: 'bar',
            data: {
                labels: productTypes,
                datasets: [{
                    label: `{{ __('Product Items') }} (${selectedYear})`,
                    data: productAmounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '{{ __('Amount') }}'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = productAmounts.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed.y / total) * 100).toFixed(1) : 0;
                                return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Comparison Chart
    const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __('Supply') }}', '{{ __('Product') }}'],
            datasets: [
                {
                    label: `{{ __('Current Year') }} (${selectedYear})`,
                    data: [currentSupplyTotal, currentProductTotal],
                    backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(75, 192, 192, 0.7)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                },
                {
                    label: `{{ __('Previous Year') }} (${previousYear})`,
                    data: [previousSupplyTotal, previousProductTotal],
                    backgroundColor: ['rgba(201, 203, 207, 0.7)', 'rgba(255, 205, 86, 0.7)'],
                    borderColor: ['rgba(201, 203, 207, 1)', 'rgba(255, 205, 86, 1)'],
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '{{ __('Amount') }}'
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Chart 4: Top Items Overall Pie Chart
    if (combinedTypes.length > 0) {
        // Take top items for pie chart (max 7)
        const topCount = Math.min(combinedTypes.length, 7);
        const topLabels = combinedTypes.slice(0, topCount);
        const topData = combinedCurrentAmounts.slice(0, topCount);
        
        // If there are more types, group the rest as "Others"
        if (combinedTypes.length > topCount) {
            const othersSum = combinedCurrentAmounts.slice(topCount).reduce((a, b) => a + b, 0);
            if (othersSum > 0) {
                topLabels.push('{{ __('Others') }}');
                topData.push(othersSum);
            }
        }

        const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
        new Chart(topItemsCtx, {
            type: 'pie',
            data: {
                labels: topLabels,
                datasets: [{
                    data: topData,
                    backgroundColor: itemColors.slice(0, topLabels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
    .small-box {
        border-radius: 10px;
        position: relative;
        display: block;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        color: white !important;
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box h3 {
        font-size: 38px;
        font-weight: bold;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
        color: white;
    }
    .small-box p {
        font-size: 15px;
        color: rgba(255,255,255,0.9);
    }
    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 0;
        font-size: 70px;
        color: rgba(255,255,255,0.15);
    }
    .small-box:hover .icon {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-primary { background-color: #007bff !important; }
    .bg-success { background-color: #28a745 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    
    .card-header.bg-primary .card-tools .btn-tool,
    .card-header.bg-success .card-tools .btn-tool {
        color: rgba(255,255,255,0.8) !important;
    }
    
    .card-header.bg-primary .card-tools .btn-tool:hover,
    .card-header.bg-success .card-tools .btn-tool:hover {
        color: white !important;
    }
    
    table th, table td {
        vertical-align: middle !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>

</x-layout>