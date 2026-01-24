<x-layout>

<div class="content-header">
    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-white border mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-flask fa-lg text-dark"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark">{{ __('Kebele Fertilizer Report') }}</h1>
                            <small class="text-dark">{{ __('Fertilizer usage analysis and comparison report') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-dark"><i class="fas fa-home text-dark"></i> {{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active text-dark">{{ __('Fertilizer Report') }}</li>
                    </ol>
                    <div class="float-sm-right mt-2">
                        <span class="badge badge-light border">
                            <i class="far fa-clock mr-1 text-dark"></i>
                            <span class="text-dark">{{ now()->format('d M Y, h:i A') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Main Card -->
            <div class="card bg-white">
                <div class="card-header bg-white border-bottom">
                    <h3 class="card-title text-dark">
                        <i class="fas fa-chart-bar mr-1 text-dark"></i>
                        {{ __('Fertilizer Usage Comparison Report') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                            <i class="fas fa-minus text-dark"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <!-- Filter Section -->
                    <div class="card bg-white border mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h3 class="card-title text-dark">
                                <i class="fas fa-filter mr-1 text-dark"></i>
                                {{ __('Filter Options') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                    <i class="fas fa-minus text-dark"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <form method="GET" action="{{ route('sector.fertilizer-gabaasa.index') }}">
                                <div class="row">
                                    <!-- Year -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="mb-1 text-dark">
                                                <i class="fas fa-calendar mr-1 text-dark"></i>
                                                {{ __('Year') }}
                                            </label>
                                            <select id="year" name="year" class="form-control form-control-sm border">
                                                <option value="">{{ __('Select Year') }}</option>
                                                @foreach($years as $y)
                                                    <option value="{{ $y }}" {{ request('year', $year) == $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Season -->
                                   <!-- Season -->
<div class="col-md-3">
    <div class="form-group">
        <label class="mb-1 text-dark">
            <i class="fas fa-cloud-sun mr-1 text-dark"></i>
            {{ __('Season') }}
        </label>
        <select id="season" name="season" class="form-control form-control-sm border">
            <option value="">{{ __('Select Season') }}</option>
            @if(request()->has('season') && request('season'))
                <option value="{{ request('season') }}" selected>{{ request('season') }}</option>
            @endif
        </select>
    </div>
</div>

<!-- Fertilizer Type -->
<div class="col-md-3">
    <div class="form-group">
        <label class="mb-1 text-dark">
            <i class="fas fa-tags mr-1 text-dark"></i>
            {{ __('Fertilizer Type') }}
        </label>
        <select id="fertilizerType" name="fertilizer_type_id" class="form-control form-control-sm border">
            <option value="">{{ __('Fertilizer Type') }}</option>
            @if(request()->has('fertilizer_type_id') && request('fertilizer_type_id'))
                <option value="{{ request('fertilizer_type_id') }}" selected>
                    {{-- You might want to display the fertilizer type name here --}}
                </option>
            @endif
        </select>
    </div>
</div>

<!-- Fertilizer -->
<div class="col-md-3">
    <div class="form-group">
        <label class="mb-1 text-dark">
            <i class="fas fa-flask mr-1 text-dark"></i>
            {{ __('Fertilizer') }}
        </label>
        <select id="fertilizer" name="fertilizer_id" class="form-control form-control-sm border">
            <option value="">{{ __('Fertilizer') }}</option>
            @if(request()->has('fertilizer_id') && request('fertilizer_id'))
                <option value="{{ request('fertilizer_id') }}" selected>
                    {{-- You might want to display the fertilizer name here --}}
                </option>
            @endif
        </select>
    </div>
</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-dark btn-sm">
                                            <i class="fas fa-filter mr-1"></i>
                                            {{ __('Apply Filter') }}
                                        </button>
                                        <a href="{{ route('sector.fertilizer-gabaasa.index') }}" class="btn btn-light btn-sm border">
                                            <i class="fas fa-redo mr-1 text-dark"></i>
                                            <span class="text-dark">{{ __('Reset') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-white border">
                                <div class="inner">
                                    <h3 class="text-dark">{{ count($currentYearData) }}</h3>
                                    <p class="text-dark">{{ __('Fertilizers Analyzed') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-flask text-dark"></i>
                                </div>
                                <div class="small-box-footer text-dark border-top">{{ __('Total in report') }}</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-white border">
                                <div class="inner">
                                    <h3 class="text-dark">{{ $year }}</h3>
                                    <p class="text-dark">{{ __('Current Year') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line text-dark"></i>
                                </div>
                                <div class="small-box-footer text-dark border-top">{{ __('Analysis period') }}</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-white border">
                                <div class="inner">
                                    <h3 class="text-dark">{{ $prevYear }}</h3>
                                    <p class="text-dark">{{ __('Previous Year') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-history text-dark"></i>
                                </div>
                                <div class="small-box-footer text-dark border-top">{{ __('Comparison period') }}</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-white border">
                                <div class="inner">
                                    <h3 class="text-dark">{{ count($years) }}</h3>
                                    <p class="text-dark">{{ __('Years of Data') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-database text-dark"></i>
                                </div>
                                <div class="small-box-footer text-dark border-top">{{ __('Historical data') }}</div>
                            </div>
                        </div>
                    </div>

                     

                    <!-- Data Table Grouped by Season -->
                    @php
                        // Group data by season - handle both array and collection
                        $currentYearData = is_array($currentYearData) ? collect($currentYearData) : $currentYearData;
                        $previousYearData = is_array($previousYearData) ? collect($previousYearData) : $previousYearData;
                        $groupedData = $currentYearData->groupBy('season');
                    @endphp

                    @if($groupedData->count() > 0)
                    <div class="card bg-white border">
                        <div class="card-header bg-white border-bottom">
                            <h3 class="card-title text-dark">
                                <i class="fas fa-table mr-1 text-dark"></i>
                                {{ __('Fertilizer Usage Comparison (Grouped by Season)') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-dark" data-card-widget="maximize">
                                    <i class="fas fa-expand text-dark"></i>
                                </button>
                                <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                    <i class="fas fa-minus text-dark"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 bg-white">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="reportTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center text-dark border">#</th>
                                            <th class="text-dark border">{{ __('Season') }}</th>
                                            <th class="text-dark border">{{ __('Fertilizer') }}</th>
                                            <th colspan="2" class="text-center text-dark border">{{ __('Current Year') }} ({{ $year }})</th>
                                            <th colspan="2" class="text-center text-dark border">{{ __('Previous Year') }} ({{ $prevYear }})</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center text-dark border"></th>
                                            <th class="text-dark border"></th>
                                            <th class="text-dark border"></th>
                                            <th class="text-center text-dark border">{{ __('Amount') }}</th>
                                            <th class="text-center text-dark border">{{ __('Unit') }}</th>
                                            <th class="text-center text-dark border">{{ __('Amount') }}</th>
                                            <th class="text-center text-dark border">{{ __('Unit') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        $i = 1;
                                    @endphp
                                    
                                    @foreach($groupedData as $season => $seasonData)
                                        @php
                                            $rowspan = $seasonData->count();
                                            $j = 0;
                                        @endphp
                                        
                                        @foreach($seasonData as $row)
                                            <tr>
                                                @if($j == 0)
                                                    <td rowspan="{{ $rowspan }}" class="text-center text-dark border align-middle">
                                                        {{ $i++ }}
                                                    </td>
                                                    <td rowspan="{{ $rowspan }}" class="text-dark border align-middle">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="fas fa-cloud-sun fa-2x mb-2 text-dark"></i>
                                                            <strong class="text-dark">{{ $season }}</strong>
                                                        </div>
                                                    </td>
                                                @endif
                                                
                                                <td class="border">
                                                    <i class="fas fa-flask text-dark mr-1"></i>
                                                    <span class="text-dark">{{ $fertilizerNames[$row['fertilizer_id']] ?? 'N/A' }}</span>
                                                </td>
                                                <td class="text-right text-dark border">
                                                    <strong>{{ number_format($row['amount'], 2) }}</strong>
                                                </td>
                                                <td class="text-center text-dark border">
                                                    <span class="badge border">{{ $row['unit'] }}</span>
                                                </td>
                                                <td class="text-right text-dark border">
                                                    @php
                                                        $prevRow = $previousYearData->where('fertilizer_id', $row['fertilizer_id'])
                                                                                  ->where('season', $season)
                                                                                  ->first();
                                                        $prevAmount = $prevRow['amount'] ?? 0;
                                                        $amountDiff = $row['amount'] - $prevAmount;
                                                    @endphp
                                                    <strong>{{ number_format($prevAmount, 2) }}</strong>
                                                    @if($amountDiff != 0)
                                                        <br>
                                                        <small class="{{ $amountDiff > 0 ? 'text-success' : 'text-danger' }}">
                                                            <i class="fas fa-arrow-{{ $amountDiff > 0 ? 'up' : 'down' }}"></i>
                                                            {{ number_format(abs($amountDiff),2) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td class="text-center text-dark border">
                                                    <span class="badge border">{{ $row['unit'] }}</span>
                                                </td>
                                            </tr>
                                            @php $j++; @endphp
                                        @endforeach
                                        
                                        <!-- Season Total Row -->
                                        @php
                                            $seasonTotalCurrentAmount = $seasonData->sum('amount');
                                            $seasonTotalPrevAmount = $previousYearData->where('season', $season)->sum('amount');
                                        @endphp
                                        
                                        <tr class="bg-light">
                                            <td colspan="3" class="text-right text-dark border">
                                                <strong>{{ __('Season Total for') }} {{ $season }}:</strong>
                                            </td>
                                            <td class="text-right text-dark border">
                                                <strong>{{ number_format($seasonTotalCurrentAmount,2) }}</strong>
                                            </td>
                                            <td class="text-center text-dark border">
                                                <span class="badge border">{{ $seasonData->first()['unit'] ?? '' }}</span>
                                            </td>
                                            <td class="text-right text-dark border">
                                                <strong>{{ number_format($seasonTotalPrevAmount,2) }}</strong>
                                            </td>
                                            <td class="text-center text-dark border">
                                                <span class="badge border">{{ $seasonData->first()['unit'] ?? '' }}</span>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                    <!-- Grand Total Row -->
                                    @php
                                        $grandTotalCurrentAmount = $currentYearData->sum('amount');
                                        $grandTotalPrevAmount = $previousYearData->sum('amount');
                                    @endphp
                                    <tr class="bg-dark">
                                        <td colspan="3" class="text-right border">
                                            <strong class="text-white">{{ __('GRAND TOTAL') }}:</strong>
                                        </td>
                                        <td class="text-right border">
                                            <strong class="text-white">{{ number_format($grandTotalCurrentAmount,2) }}</strong>
                                        </td>
                                        <td class="text-center border">
                                            <span class="badge bg-light">{{ $currentYearData->first()['unit'] ?? '' }}</span>
                                        </td>
                                        <td class="text-right border">
                                            <strong class="text-white">{{ number_format($grandTotalPrevAmount,2) }}</strong>
                                        </td>
                                        <td class="text-center border">
                                            <span class="badge bg-light">{{ $currentYearData->first()['unit'] ?? '' }}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card bg-white border">
                        <div class="card-body">
                            <div class="callout callout-light border">
                                <h5 class="text-dark"><i class="icon fas fa-exclamation-triangle text-dark"></i> {{ __('No Fertilizer Data Found') }}</h5>
                                <p class="text-dark">{{ __('Try adjusting your filter criteria') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Charts Section -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-white border">
                                <div class="card-header bg-white border-bottom">
                                    <h3 class="card-title text-dark">
                                        <i class="fas fa-chart-bar mr-1 text-dark"></i>
                                        {{ __('Fertilizer Usage Comparison by Season') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                            <i class="fas fa-minus text-dark"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body bg-white">
                                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <small class="text-dark">
                                        <i class="fas fa-info-circle mr-1 text-dark"></i>
                                        {{ __('Comparing :year vs :prevYear fertilizer usage by season', ['year' => $year, 'prevYear' => $prevYear]) }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-white border">
                                <div class="card-header bg-white border-bottom">
                                    <h3 class="card-title text-dark">
                                        <i class="fas fa-chart-line mr-1 text-dark"></i>
                                        {{ __('Fertilizer Usage Trend by Season') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                            <i class="fas fa-minus text-dark"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body bg-white">
                                    <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <small class="text-dark">
                                        <i class="fas fa-info-circle mr-1 text-dark"></i>
                                        {{ __('Fertilizer usage trends across seasons') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="card bg-white border">
                                <div class="card-header bg-white border-bottom">
                                    <h3 class="card-title text-dark">
                                        <i class="fas fa-chart-pie mr-1 text-dark"></i>
                                        {{ __('Fertilizer Distribution by Type') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                            <i class="fas fa-minus text-dark"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body bg-white">
                                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <small class="text-dark">
                                        <i class="fas fa-info-circle mr-1 text-dark"></i>
                                        {{ __('Fertilizer allocation by type for :year', ['year' => $year]) }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="card bg-white border">
                                <div class="card-header bg-white border-bottom">
                                    <h3 class="card-title text-dark">
                                        <i class="fas fa-chart-area mr-1 text-dark"></i>
                                        {{ __('Key Insights') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                            <i class="fas fa-minus text-dark"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body bg-white">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="info-box bg-white border mb-3">
                                                <span class="info-box-icon bg-light border"><i class="fas fa-flask text-dark"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-dark">{{ __('Most Used Fertilizer') }}</span>
                                                    @if($currentYearData->count() > 0)
                                                        @php
                                                            $fertilizerTotals = [];
                                                            foreach($currentYearData as $row) {
                                                                $fertilizerId = $row['fertilizer_id'];
                                                                if(!isset($fertilizerTotals[$fertilizerId])) {
                                                                    $fertilizerTotals[$fertilizerId] = 0;
                                                                }
                                                                $fertilizerTotals[$fertilizerId] += $row['amount'];
                                                            }
                                                            arsort($fertilizerTotals);
                                                            $topFertilizerId = key($fertilizerTotals);
                                                            $topFertilizerAmount = current($fertilizerTotals);
                                                        @endphp
                                                        <span class="info-box-number text-dark">
                                                            {{ $fertilizerNames[$topFertilizerId] ?? 'N/A' }}
                                                            <small class="text-dark">({{ number_format($topFertilizerAmount,2) }} {{ $currentYearData->first()['unit'] ?? '' }})</small>
                                                        </span>
                                                    @else
                                                        <span class="info-box-number text-dark">{{ __('No data available') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="info-box bg-white border mb-3">
                                                <span class="info-box-icon bg-light border"><i class="fas fa-percentage text-dark"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-dark">{{ __('Year-over-Year Change') }}</span>
                                                    @if($currentYearData->count() > 0 && $previousYearData->count() > 0)
                                                        @php
                                                            $currentTotal = $currentYearData->sum('amount');
                                                            $previousTotal = $previousYearData->sum('amount');
                                                            $change = $previousTotal > 0 ? (($currentTotal - $previousTotal) / $previousTotal) * 100 : 0;
                                                        @endphp
                                                        <span class="info-box-number {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 1) }}%
                                                        </span>
                                                    @else
                                                        <span class="info-box-number text-dark">{{ __('No comparison data') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="info-box bg-white border">
                                                <span class="info-box-icon bg-light border"><i class="fas fa-balance-scale text-dark"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-dark">{{ __('Total Fertilizer Used') }}</span>
                                                    <span class="info-box-number text-dark">
                                                        {{ number_format($currentYearData->sum('amount'),2) }} {{ $currentYearData->first()['unit'] ?? '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top">
                    <div class="row">
                        <div class="col-sm-6">
                            <small class="text-dark">
                                <i class="fas fa-sync-alt mr-1 text-dark"></i>
                                {{ __('Data updates in real-time') }}
                            </small>
                        </div>
                        <div class="col-sm-6 text-right">
                            <small class="text-dark">
                                <i class="far fa-clock mr-1 text-dark"></i>
                                {{ __('Report generated on') }} {{ now()->format('d M Y, h:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Add SheetJS for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const year = document.getElementById('year');
    const season = document.getElementById('season');
    const fertilizerType = document.getElementById('fertilizerType');
    const fertilizer = document.getElementById('fertilizer');
    const exportExcelBtn = document.getElementById('exportExcelBtn');

    function reset(select, text) {
        select.innerHTML = `<option value="">${text}</option>`;
        select.disabled = true;
    }

    // Function to fetch and populate data
    async function populateSelect(url, selectElement, textKey = 'name', valueKey = 'id') {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            
            // Clear existing options except the first one
            selectElement.innerHTML = `<option value="">{{ __('Select') }} ${selectElement.name}</option>`;
            
            if (Array.isArray(data) && data.length > 0) {
                selectElement.disabled = false;
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = typeof item === 'string' ? item : item[valueKey];
                    option.text = typeof item === 'string' ? item : item[textKey];
                    selectElement.appendChild(option);
                });
                
                // Check if this select has a selected value from request
                const paramName = selectElement.name;
                const selectedValue = new URLSearchParams(window.location.search).get(paramName);
                if (selectedValue) {
                    selectElement.value = selectedValue;
                }
            } else {
                selectElement.disabled = true;
            }
            
            return data;
        } catch (error) {
            console.error('Error populating select:', error);
            selectElement.disabled = true;
            return [];
        }
    }

    // Initialize all dropdowns based on current state
    async function initializeDropdowns() {
        const currentYear = year.value;
        const currentSeason = season.value;
        const currentFertilizerType = fertilizerType.value;
        
        // If year is selected, populate seasons
        if (currentYear) {
            await populateSelect(
                `/sector/filter/fertilizer-seasons?year=${currentYear}`,
                season,
                '',
                ''
            );
            
            // If season is selected, populate fertilizer types
            if (currentSeason && season.options.length > 0) {
                await populateSelect(
                    `/sector/filter/fertilizer-types?year=${currentYear}&season=${currentSeason}`,
                    fertilizerType
                );
                
                // If fertilizer type is selected, populate fertilizers
                if (currentFertilizerType && fertilizerType.options.length > 0) {
                    await populateSelect(
                        `/sector/filter/fertilizers?year=${currentYear}&season=${currentSeason}&fertilizer_type_id=${currentFertilizerType}`,
                        fertilizer
                    );
                }
            }
        }
    }

    // Call initialization on page load
    initializeDropdowns();

    // YEAR → SEASON
    year.addEventListener('change', async function () {
        reset(season, '{{ __('Select Season') }}');
        reset(fertilizerType, '{{ __('Fertilizer Type') }}');
        reset(fertilizer, '{{ __('Fertilizer') }}');

        if (!this.value) return;

        await populateSelect(
            `/sector/filter/fertilizer-seasons?year=${this.value}`,
            season,
            '',
            ''
        );
    });

    // SEASON → FERTILIZER TYPE
    season.addEventListener('change', async function () {
        reset(fertilizerType, '{{ __('Fertilizer Type') }}');
        reset(fertilizer, '{{ __('Fertilizer') }}');

        if (!this.value || !year.value) return;

        await populateSelect(
            `/sector/filter/fertilizer-types?year=${year.value}&season=${this.value}`,
            fertilizerType
        );
    });

    // FERTILIZER TYPE → FERTILIZER
    fertilizerType.addEventListener('change', async function () {
        reset(fertilizer, '{{ __('Fertilizer') }}');

        if (!this.value || !year.value || !season.value) return;

        await populateSelect(
            `/sector/filter/fertilizers?year=${year.value}&season=${season.value}&fertilizer_type_id=${this.value}`,
            fertilizer
        );
    });

    // Excel Export Functionality
    exportExcelBtn.addEventListener('click', function() {
        // Create a workbook
        const wb = XLSX.utils.book_new();
        
        // Prepare data array for Excel
        const data = [];
        
        // Add title row
        data.push(['{{ __('KEBELE FERTILIZER REPORT - USAGE COMPARISON') }}']);
        data.push(['{{ __('Report Period') }}:', {{ $year }} + ' vs ' + {{ $prevYear }}]);
        data.push(['{{ __('Generated on') }}:', new Date().toLocaleDateString() + ' ' + new Date().toLocaleTimeString()]);
        data.push([]); // Empty row
        
        // Add headers
        data.push([
            '#',
            '{{ __('Season') }}',
            '{{ __('Fertilizer') }}',
            '{{ __('Current Year Amount') }}',
            '{{ __('Unit') }}',
            '{{ __('Previous Year Amount') }}',
            '{{ __('Unit') }}'
        ]);
        
        @php 
            $groupedData = $currentYearData->groupBy('season');
            $i = 1;
        @endphp
        
        @if($groupedData->count() > 0)
            @foreach($groupedData as $season => $seasonData)
                @foreach($seasonData as $row)
                    @php
                        $prevRow = $previousYearData->where('fertilizer_id', $row['fertilizer_id'])
                                                  ->where('season', $season)
                                                  ->first();
                        $prevAmount = $prevRow['amount'] ?? 0;
                    @endphp
                    data.push([
                        {{ $i }},
                        '{{ $season }}',
                        '{{ $fertilizerNames[$row["fertilizer_id"]] ?? "N/A" }}',
                        {{ $row['amount'] }},
                        '{{ $row["unit"] }}',
                        {{ $prevAmount }},
                        '{{ $row["unit"] }}'
                    ]);
                @endforeach
                
                @php
                    $seasonTotalCurrentAmount = $seasonData->sum('amount');
                    $seasonTotalPrevAmount = $previousYearData->where('season', $season)->sum('amount');
                @endphp
                
                // Add season total
                data.push([
                    '',
                    '{{ __('SEASON TOTAL') }} {{ $season }}',
                    '',
                    {{ $seasonTotalCurrentAmount }},
                    '{{ $seasonData->first()["unit"] ?? "" }}',
                    {{ $seasonTotalPrevAmount }},
                    '{{ $seasonData->first()["unit"] ?? "" }}'
                ]);
                
                // Add empty row after season
                data.push([]);
                @php $i++; @endphp
            @endforeach
            
            // Add grand totals
            @php
                $grandTotalCurrentAmount = $currentYearData->sum('amount');
                $grandTotalPrevAmount = $previousYearData->sum('amount');
            @endphp
            data.push([
                '',
                '{{ __('GRAND TOTAL') }}',
                '',
                {{ $grandTotalCurrentAmount }},
                '{{ $currentYearData->first()["unit"] ?? "" }}',
                {{ $grandTotalPrevAmount }},
                '{{ $currentYearData->first()["unit"] ?? "" }}'
            ]);
        @endif
        
        // Add summary section
        data.push([]);
        data.push(['{{ __('REPORT SUMMARY') }}']);
        data.push(['{{ __('Total Fertilizers Analyzed') }}:', {{ $currentYearData->count() }}]);
        data.push(['{{ __('Total Seasons') }}:', {{ $groupedData->count() }}]);
        data.push(['{{ __('Report Year') }}:', {{ $year }}]);
        data.push(['{{ __('Comparison Year') }}:', {{ $prevYear }}]);
        
        // Convert data to worksheet
        const ws = XLSX.utils.aoa_to_sheet(data);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 5 },    // #
            { wch: 15 },   // Season
            { wch: 25 },   // Fertilizer
            { wch: 20 },   // Current Amount
            { wch: 10 },   // Unit
            { wch: 20 },   // Previous Amount
            { wch: 10 }    // Unit
        ];
        
        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, '{{ __('Fertilizer Report') }}');
        
        // Generate filename
        const filename = '{{ __('Fertilizer Report') }}_{{ $year }}_vs_{{ $prevYear }}_' + 
                        new Date().toISOString().slice(0,10).replace(/-/g, '') + '.xlsx';
        
        // Export the workbook
        XLSX.writeFile(wb, filename);
        
        // Show success message
        showToast('{{ __('Export successful! File downloaded as') }} ' + filename, 'success');
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove any existing toast
        const existingToast = document.querySelector('.export-toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `export-toast alert alert-${type} alert-dismissible fade show`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        toast.innerHTML = `
            <strong>${type === 'success' ? '{{ __('Success!') }}' : '{{ __('Info') }}'}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }

    // Chart.js for Fertilizer Data
    @php
        // Prepare data for charts grouped by season
        $groupedData = $currentYearData->groupBy('season');
        $seasons = $groupedData->keys()->toArray();
        
        $currentSeasonTotals = [];
        $prevSeasonTotals = [];
        $fertilizerTypeTotals = [];
        
        foreach ($seasons as $season) {
            $currentSeasonTotals[$season] = $groupedData[$season]->sum('amount');
            $prevSeasonTotals[$season] = $previousYearData->where('season', $season)->sum('amount');
        }
        
        // Group by fertilizer type for pie chart
        foreach($currentYearData as $row) {
            $type = $row['fertilizer_type'] ?? 'Unknown';
            if(!isset($fertilizerTypeTotals[$type])) {
                $fertilizerTypeTotals[$type] = 0;
            }
            $fertilizerTypeTotals[$type] += $row['amount'];
        }
    @endphp

    const seasons = @json($seasons);
    const currentSeasonAmount = @json(array_values($currentSeasonTotals));
    const prevSeasonAmount = @json(array_values($prevSeasonTotals));
    
    const fertilizerTypes = @json(array_keys($fertilizerTypeTotals));
    const fertilizerTypeAmounts = @json(array_values($fertilizerTypeTotals));

    // Vibrant color palette for fertilizer charts
    const fertilizerColors = {
        currentYear: [
            'rgba(46, 204, 113, 0.8)',   // Green
            'rgba(52, 152, 219, 0.8)',   // Blue
            'rgba(155, 89, 182, 0.8)',   // Purple
            'rgba(241, 196, 15, 0.8)',   // Yellow
            'rgba(230, 126, 34, 0.8)',   // Orange
            'rgba(231, 76, 60, 0.8)'     // Red
        ],
        previousYear: [
            'rgba(46, 204, 113, 0.4)',   // Light Green
            'rgba(52, 152, 219, 0.4)',   // Light Blue
            'rgba(155, 89, 182, 0.4)',   // Light Purple
            'rgba(241, 196, 15, 0.4)',   // Light Yellow
            'rgba(230, 126, 34, 0.4)',   // Light Orange
            'rgba(231, 76, 60, 0.4)'     // Light Red
        ],
        fertilizerTypeColors: [
            '#2ecc71', // Green
            '#3498db', // Blue
            '#9b59b6', // Purple
            '#f1c40f', // Yellow
            '#e67e22', // Orange
            '#e74c3c', // Red
            '#1abc9c', // Teal
            '#34495e'  // Dark Blue
        ]
    };

    // Bar Chart - Fertilizer Usage Comparison by Season
    if(seasons.length > 0) {
        new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: `{{ __('Current Year') }} (${@json($year)})`,
                        data: currentSeasonAmount,
                        backgroundColor: fertilizerColors.currentYear.slice(0, seasons.length),
                        borderColor: fertilizerColors.currentYear.slice(0, seasons.length).map(color => color.replace('0.8', '1')),
                        borderWidth: 2,
                        borderRadius: 5,
                        borderSkipped: false,
                    },
                    {
                        label: `{{ __('Previous Year') }} (${@json($prevYear)})`,
                        data: prevSeasonAmount,
                        backgroundColor: fertilizerColors.previousYear.slice(0, seasons.length),
                        borderColor: fertilizerColors.previousYear.slice(0, seasons.length).map(color => color.replace('0.4', '1')),
                        borderWidth: 1,
                        borderRadius: 5,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toLocaleString() + ' ' + (@json($currentYearData->first()['unit'] ?? ''));
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        title: {
                            display: true,
                            text: '{{ __('Amount') }} (' + (@json($currentYearData->first()['unit'] ?? '')) + ')',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {top: 10, bottom: 20}
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        title: {
                            display: true,
                            text: '{{ __('Agricultural Seasons') }}',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {top: 20, bottom: 10}
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // Line Chart - Fertilizer Usage Trend by Season
        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: `{{ __('Current Year') }} (${@json($year)})`,
                        data: currentSeasonAmount,
                        borderColor: '#2ecc71', // Green
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#2ecc71',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: `{{ __('Previous Year') }} (${@json($prevYear)})`,
                        data: prevSeasonAmount,
                        borderColor: '#3498db', // Blue
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#3498db',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y.toLocaleString() + ' ' + (@json($currentYearData->first()['unit'] ?? ''));
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        title: {
                            display: true,
                            text: '{{ __('Amount') }} (' + (@json($currentYearData->first()['unit'] ?? '')) + ')',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {top: 10, bottom: 20}
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        title: {
                            display: true,
                            text: '{{ __('Agricultural Seasons') }}',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {top: 20, bottom: 10}
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }

    // Pie Chart - Fertilizer Distribution by Type
    if(fertilizerTypes.length > 0) {
        new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: fertilizerTypes,
                datasets: [{
                    data: fertilizerTypeAmounts,
                    backgroundColor: fertilizerColors.fertilizerTypeColors.slice(0, fertilizerTypes.length),
                    borderColor: '#fff',
                    borderWidth: 2,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                label += value.toLocaleString() + ' ' + (@json($currentYearData->first()['unit'] ?? '')) + ' (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else {
        // If no data for pie chart, show message
        document.getElementById('pieChart').parentElement.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No fertilizer type data available') }}</h5>
                <p class="text-muted">{{ __('Try adjusting your filter criteria') }}</p>
            </div>
        `;
    }
});
</script>

</x-layout>