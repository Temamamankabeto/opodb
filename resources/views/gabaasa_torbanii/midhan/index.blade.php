<x-layout>

<div class="content-header">
    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-white border mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-seedling fa-lg text-dark"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark">{{ __('Kebele Agricultural Report') }}</h1>
                            <small class="text-dark">{{ __('Crop production analysis and comparison report') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text-dark"><i class="fas fa-home text-dark"></i> {{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active text-dark">{{ __('Crop Report') }}</li>
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
                        {{ __('Crop Production Comparison Report') }}
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
                            <form id="filterForm" method="GET" action="{{ route('sector.crop-gabaasa.index') }}">
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
                                                    <option value="{{ $y }}" {{ request('year', $year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Season -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="mb-1 text-dark">
                                                <i class="fas fa-cloud-sun mr-1 text-dark"></i>
                                                {{ __('Season') }}
                                            </label>
                                            <select id="season" name="season" class="form-control form-control-sm border" {{ request('season') ? '' : 'disabled' }}>
                                                <option value="">{{ __('Select Season') }}</option>
                                                @if(request('season'))
                                                    <option value="{{ request('season') }}" selected>{{ request('season') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Crop Type -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="mb-1 text-dark">
                                                <i class="fas fa-tags mr-1 text-dark"></i>
                                                {{ __('Crop Type') }}
                                            </label>
                                            <select id="croptype" name="croptype_id" class="form-control form-control-sm border" {{ request('croptype_id') ? '' : 'disabled' }}>
                                                <option value="">{{ __('Crop Type') }}</option>
                                                @if(request('croptype_id') && $croptypeData = \App\Models\CropType::find(request('croptype_id')))
                                                    <option value="{{ $croptypeData->id }}" selected>{{ $croptypeData->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Crop -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="mb-1 text-dark">
                                                <i class="fas fa-leaf mr-1 text-dark"></i>
                                                {{ __('Crop') }}
                                            </label>
                                            <select id="crop" name="crop_id" class="form-control form-control-sm border" {{ request('crop_id') ? '' : 'disabled' }}>
                                                <option value="">{{ __('Crop') }}</option>
                                                @if(request('crop_id') && $cropData = \App\Models\Crop::find(request('crop_id')))
                                                    <option value="{{ $cropData->id }}" selected>{{ $cropData->name }}</option>
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
                                        <a href="{{ route('sector.crop-gabaasa.index') }}" class="btn btn-light btn-sm border">
                                            <i class="fas fa-redo mr-1 text-dark"></i>
                                            <span class="text-dark">{{ __('Reset') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ number_format($currentYearData->count()) }}</h3>
                                    <p>{{ __('Total Crops') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    {{ __('All Seasons') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format($currentYearData->sum('land'), 1) }}</h3>
                                    <p>{{ __('Total Land (Ha)') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tractor"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    {{ __('Current Year') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ number_format($currentYearData->sum('production'), 1) }}</h3>
                                    <p>{{ __('Total Production (Qt)') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-weight-hanging"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    {{ __('Current Year') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ number_format(collect($previousYearData)->sum('production'), 1) }}</h3>
                                    <p>{{ __('Previous Year Production') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    {{ __('Year ') . $prevYear }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row mb-4">
                        <!-- Bar Chart - Season Comparison -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        {{ __('Production Comparison by Season') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pie Chart - Land Distribution -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        {{ __('Land Distribution by Season') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Line Chart - Trend Analysis -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        {{ __('Production Trend Analysis') }}
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Export and Actions Row -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm" id="exportExcelBtn">
                                        <i class="fas fa-file-excel mr-1"></i> {{ __('Export to Excel') }}
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" onclick="window.print()">
                                        <i class="fas fa-print mr-1"></i> {{ __('Print Report') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table Grouped by Season -->
                    <div class="card bg-white border">
                        <div class="card-header bg-white border-bottom">
                            <h3 class="card-title text-dark">
                                <i class="fas fa-table mr-1 text-dark"></i>
                                {{ __('Production Data Comparison (Grouped by Season)') }}
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
                                            <th class="text-dark border">{{ __('Crop') }}</th>
                                            <th colspan="2" class="text-center text-dark border">{{ __('Current Year') }} ({{ $year }})</th>
                                            <th colspan="2" class="text-center text-dark border">{{ __('Previous Year') }} ({{ $prevYear }})</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center text-dark border"></th>
                                            <th class="text-dark border"></th>
                                            <th class="text-dark border"></th>
                                            <th class="text-center text-dark border">{{ __('Land (Ha)') }}</th>
                                            <th class="text-center text-dark border">{{ __('Production (Qt)') }}</th>
                                            <th class="text-center text-dark border">{{ __('Land (Ha)') }}</th>
                                            <th class="text-center text-dark border">{{ __('Production (Qt)') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php 
                                        // Group data by season
                                        $groupedData = $currentYearData->groupBy('season');
                                        $i = 1;
                                    @endphp
                                    
                                    @forelse($groupedData as $season => $seasonData)
                                        @php
                                            $rowspan = $seasonData->count();
                                            $j = 0;
                                        @endphp
                                        
                                        @foreach($seasonData as $cropId => $row)
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
                                                    <i class="fas fa-seedling text-dark mr-1"></i>
                                                    <span class="text-dark">{{ $row->crop_name }}</span>
                                                </td>
                                                <td class="text-right text-dark border">
                                                    <strong>{{ number_format($row->land,2) }}</strong>
                                                </td>
                                                <td class="text-right text-dark border">
                                                    <strong>{{ number_format($row->production,2) }}</strong>
                                                </td>
                                                <td class="text-right text-dark border">
                                                    @php
                                                        $prevLand = $previousYearData[$cropId]->land ?? 0;
                                                        $landDiff = $row->land - $prevLand;
                                                    @endphp
                                                    <strong>{{ number_format($prevLand,2) }}</strong>
                                                    @if($landDiff != 0)
                                                        <br>
                                                        <small class="{{ $landDiff > 0 ? 'text-success' : 'text-danger' }}">
                                                            <i class="fas fa-arrow-{{ $landDiff > 0 ? 'up' : 'down' }}"></i>
                                                            {{ number_format(abs($landDiff),2) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td class="text-right text-dark border">
                                                    @php
                                                        $prevProduction = $previousYearData[$cropId]->production ?? 0;
                                                        $prodDiff = $row->production - $prevProduction;
                                                    @endphp
                                                    <strong>{{ number_format($prevProduction,2) }}</strong>
                                                    @if($prodDiff != 0)
                                                        <br>
                                                        <small class="{{ $prodDiff > 0 ? 'text-success' : 'text-danger' }}">
                                                            <i class="fas fa-arrow-{{ $prodDiff > 0 ? 'up' : 'down' }}"></i>
                                                            {{ number_format(abs($prodDiff),2) }}
                                                        </small>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $j++; @endphp
                                        @endforeach
                                        
                                        <!-- Season Total Row -->
                                        @php
                                            $seasonTotalCurrentLand = $seasonData->sum('land');
                                            $seasonTotalCurrentProduction = $seasonData->sum('production');
                                            
                                            // Calculate previous year totals for this season
                                            $seasonTotalPrevLand = 0;
                                            $seasonTotalPrevProduction = 0;
                                            foreach($seasonData as $cropId => $row) {
                                                if(isset($previousYearData[$cropId])) {
                                                    $seasonTotalPrevLand += $previousYearData[$cropId]->land ?? 0;
                                                    $seasonTotalPrevProduction += $previousYearData[$cropId]->production ?? 0;
                                                }
                                            }
                                        @endphp
                                        
                                        <tr class="bg-light">
                                            <td colspan="3" class="text-right text-dark border">
                                                <strong>{{ __('Season Total for') }} {{ $season }}:</strong>
                                            </td>
                                            <td class="text-right text-dark border">
                                                <strong>{{ number_format($seasonTotalCurrentLand,2) }}</strong>
                                            </td>
                                            <td class="text-right text-dark border">
                                                <strong>{{ number_format($seasonTotalCurrentProduction,2) }}</strong>
                                            </td>
                                            <td class="text-right text-dark border">
                                                <strong>{{ number_format($seasonTotalPrevLand,2) }}</strong>
                                            </td>
                                            <td class="text-right text-dark border">
                                                <strong>{{ number_format($seasonTotalPrevProduction,2) }}</strong>
                                            </td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 border">
                                                <div class="callout callout-light border mb-0">
                                                    <h5 class="text-dark"><i class="icon fas fa-exclamation-triangle text-dark"></i> {{ __('No Data Found') }}</h5>
                                                    <p class="text-dark">{{ __('Try adjusting your filter criteria') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    
                                    <!-- Grand Total Row -->
                                    @if($currentYearData->isNotEmpty())
                                        @php
                                            $grandTotalCurrentLand = $currentYearData->sum('land');
                                            $grandTotalCurrentProduction = $currentYearData->sum('production');
                                            $grandTotalPrevLand = collect($previousYearData)->sum('land');
                                            $grandTotalPrevProduction = collect($previousYearData)->sum('production');
                                        @endphp
                                        <tr class="bg-dark">
                                            <td colspan="3" class="text-right border">
                                                <strong class="text-white">{{ __('GRAND TOTAL') }}:</strong>
                                            </td>
                                            <td class="text-right border">
                                                <strong class="text-white">{{ number_format($grandTotalCurrentLand,2) }}</strong>
                                            </td>
                                            <td class="text-right border">
                                                <strong class="text-white">{{ number_format($grandTotalCurrentProduction,2) }}</strong>
                                            </td>
                                            <td class="text-right border">
                                                <strong class="text-white">{{ number_format($grandTotalPrevLand,2) }}</strong>
                                            </td>
                                            <td class="text-right border">
                                                <strong class="text-white">{{ number_format($grandTotalPrevProduction,2) }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
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
<style>
/* =========================
   GLOBAL REFINEMENTS
========================= */
.content-wrapper,
.content,
.card,
.table {
    font-size: 14px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

.card-header {
    border-radius: 10px 10px 0 0;
    font-weight: 600;
}

.card-footer {
    border-radius: 0 0 10px 10px;
}

/* =========================
   HEADER AREA
========================= */
.content-header h1 {
    font-weight: 700;
    letter-spacing: 0.3px;
}

.content-header small {
    font-size: 13px;
    opacity: 0.85;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 0;
}

.badge {
    padding: 6px 10px;
    font-size: 12px;
}

/* =========================
   SUMMARY CARDS
========================= */
.small-box {
    border-radius: 10px;
    position: relative;
    color: white !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.small-box:hover {
    transform: translateY(-5px);
}

.small-box .inner h3 {
    font-size: 38px;
    font-weight: bold;
    margin: 0 0 10px 0;
    white-space: nowrap;
    padding: 0;
}

.small-box .inner p {
    font-size: 15px;
}

.small-box .icon {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 0;
    font-size: 70px;
    color: rgba(255,255,255,0.15);
}

.small-box-footer {
    background: rgba(0,0,0,0.1);
    color: rgba(255,255,255,0.8);
    display: block;
    padding: 5px 0;
    position: relative;
    text-align: center;
    text-decoration: none;
    border-radius: 0 0 10px 10px;
}

.small-box-footer:hover {
    background: rgba(0,0,0,0.15);
    color: white;
}

/* =========================
   CHART STYLING
========================= */
.chart {
    position: relative;
    height: 250px;
}

.chart canvas {
    width: 100% !important;
    height: 250px !important;
}

/* =========================
   FILTER CARD
========================= */
.form-group label {
    font-weight: 600;
    font-size: 13px;
}

.form-control {
    border-radius: 6px;
}

.form-control:focus {
    box-shadow: 0 0 0 2px rgba(0,123,255,0.15);
}

/* =========================
   BUTTONS
========================= */
.btn {
    border-radius: 6px;
    font-weight: 600;
}

.btn-group .btn {
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #5dd879);
    border: none;
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #4dd0e1);
    border: none;
}

.btn-dark {
    background: linear-gradient(135deg, #343a40, #000);
    border: none;
}

/* =========================
   TABLE STYLING
========================= */
.table {
    margin-bottom: 0;
}

.table thead th {
    vertical-align: middle;
    font-weight: 700;
    font-size: 13px;
    background: #f8f9fa;
}

.table tbody td {
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.03);
}

.table td strong {
    font-weight: 700;
}

/* Season Icon Alignment */
.table .fa-cloud-sun {
    opacity: 0.85;
}

/* Totals */
.table tr.bg-light td {
    background-color: #f4f6f9 !important;
    font-weight: 700;
}

.table tr.bg-dark td {
    background: linear-gradient(135deg, #343a40, #000) !important;
}

/* =========================
   CALLOUT (NO DATA)
========================= */
.callout {
    border-radius: 8px;
}

/* =========================
   EXPORT TOAST
========================= */
.export-toast {
    border-radius: 10px;
    font-weight: 600;
}

/* =========================
   PRINT STYLES
========================= */
@media print {
    body {
        background: #fff !important;
    }

    .btn,
    .card-tools,
    .breadcrumb,
    .export-toast,
    .small-box,
    .chart {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd;
    }

    table {
        font-size: 12px;
    }
}
</style>

<!-- Add SheetJS for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ asset('vendor/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const year = document.getElementById('year');
    const season = document.getElementById('season');
    const croptype = document.getElementById('croptype');
    const crop = document.getElementById('crop');
    const exportExcelBtn = document.getElementById('exportExcelBtn');

    function reset(select, text) {
        select.innerHTML = `<option value="">${text}</option>`;
        select.disabled = true;
    }

    // Cascading filters
    year.addEventListener('change', function () {
        reset(season, '{{ __('Select Season') }}');
        reset(croptype, '{{ __('Crop Type') }}');
        reset(crop, '{{ __('Crop') }}');

        if (!this.value) return;

        fetch(`/sector/filter/seasons?year=${this.value}`)
            .then(res => res.json())
            .then(data => {
                season.disabled = false;
                data.forEach(s => season.innerHTML += `<option value="${s}">${s}</option>`);
            });
    });

    season.addEventListener('change', function () {
        reset(croptype, '{{ __('Crop Type') }}');
        reset(crop, '{{ __('Crop') }}');

        if (!this.value) return;

        fetch(`/sector/filter/crop-types?year=${year.value}&season=${this.value}`)
            .then(res => res.json())
            .then(data => {
                croptype.disabled = false;
                data.forEach(ct => croptype.innerHTML += `<option value="${ct.id}">${ct.name}</option>`);
            });
    });

    croptype.addEventListener('change', function () {
        reset(crop, '{{ __('Crop') }}');

        if (!this.value) return;

        fetch(`/sector/filter/crops?year=${year.value}&season=${season.value}&croptype_id=${this.value}`)
            .then(res => res.json())
            .then(data => {
                crop.disabled = false;
                data.forEach(c => crop.innerHTML += `<option value="${c.id}">${c.name}</option>`);
            });
    });

    // Excel Export Functionality - Fixed version
    exportExcelBtn.addEventListener('click', function() {
        // Create a workbook
        const wb = XLSX.utils.book_new();
        
        // Prepare data array for Excel
        const data = [];
        
        // Add title row
        data.push(['{{ __('KEBELE AGRICULTURAL REPORT - PRODUCTION COMPARISON') }}']);
        data.push(['{{ __('Report Period') }}:', {{ $year }} + ' vs ' + {{ $prevYear }}]);
        data.push(['{{ __('Generated on') }}:', new Date().toLocaleDateString() + ' ' + new Date().toLocaleTimeString()]);
        data.push([]); // Empty row
        
        // Add headers
        data.push([
            '#',
            '{{ __('Season') }}',
            '{{ __('Crop') }}',
            '{{ __('Current Year Land (Ha)') }}',
            '{{ __('Current Year Production (Qt)') }}',
            '{{ __('Previous Year Land (Ha)') }}',
            '{{ __('Previous Year Production (Qt)') }}'
        ]);
        
        @php 
            $groupedData = $currentYearData->groupBy('season');
            $i = 1;
        @endphp
        
        @foreach($groupedData as $season => $seasonData)
            @foreach($seasonData as $cropId => $row)
                @php
                    $prevLand = $previousYearData[$cropId]->land ?? 0;
                    $prevProduction = $previousYearData[$cropId]->production ?? 0;
                    $landDiff = $row->land - $prevLand;
                    $prodDiff = $row->production - $prevProduction;
                @endphp
                data.push([
                    {{ $i }},
                    '{{ $season }}',
                    '{{ $row->crop_name }}',
                    {{ $row->land }},
                    {{ $row->production }},
                    {{ $prevLand }},
                    {{ $prevProduction }}
                ]);
            @endforeach
            
            @php
                $seasonTotalCurrentLand = $seasonData->sum('land');
                $seasonTotalCurrentProduction = $seasonData->sum('production');
                $seasonTotalPrevLand = 0;
                $seasonTotalPrevProduction = 0;
                foreach($seasonData as $cropId => $row) {
                    if(isset($previousYearData[$cropId])) {
                        $seasonTotalPrevLand += $previousYearData[$cropId]->land ?? 0;
                        $seasonTotalPrevProduction += $previousYearData[$cropId]->production ?? 0;
                    }
                }
            @endphp
            
            // Add season total
            data.push([
                '',
                '{{ __('SEASON TOTAL') }} {{ $season }}',
                '',
                {{ $seasonTotalCurrentLand }},
                {{ $seasonTotalCurrentProduction }},
                {{ $seasonTotalPrevLand }},
                {{ $seasonTotalPrevProduction }}
            ]);
            
            // Add empty row after season
            data.push([]);
            @php $i++; @endphp
        @endforeach
        
        // Add grand totals
        @if($currentYearData->isNotEmpty())
            @php
                $grandTotalCurrentLand = $currentYearData->sum('land');
                $grandTotalCurrentProduction = $currentYearData->sum('production');
                $grandTotalPrevLand = collect($previousYearData)->sum('land');
                $grandTotalPrevProduction = collect($previousYearData)->sum('production');
            @endphp
            data.push([
                '',
                '{{ __('GRAND TOTAL') }}',
                '',
                {{ $grandTotalCurrentLand }},
                {{ $grandTotalCurrentProduction }},
                {{ $grandTotalPrevLand }},
                {{ $grandTotalPrevProduction }}
            ]);
        @endif
        
        // Add summary section
        data.push([]);
        data.push(['{{ __('REPORT SUMMARY') }}']);
        data.push(['{{ __('Total Crops Analyzed') }}:', {{ $currentYearData->count() }}]);
        data.push(['{{ __('Total Seasons') }}:', {{ count($groupedData) }}]);
        data.push(['{{ __('Report Year') }}:', {{ $year }}]);
        data.push(['{{ __('Comparison Year') }}:', {{ $prevYear }}]);
        
        // Convert data to worksheet
        const ws = XLSX.utils.aoa_to_sheet(data);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 5 },    // #
            { wch: 15 },   // Season
            { wch: 25 },   // Crop
            { wch: 20 },   // Current Land
            { wch: 20 },   // Current Production
            { wch: 20 },   // Previous Land
            { wch: 20 }    // Previous Production
        ];
        
        // Add the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, '{{ __('Agricultural Report') }}');
        
        // Generate filename
        const filename = '{{ __('Agricultural Report') }}_{{ $year }}_vs_{{ $prevYear }}_' + 
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

    // Prepare chart data from PHP variables
    @php
        // Prepare data for charts grouped by season
        $groupedCurrentData = $currentYearData->groupBy('season');
        $groupedPrevData = collect($previousYearData)->groupBy('season');
        
        $seasons = $groupedCurrentData->keys()->toArray();
        
        $currentSeasonTotals = [];
        $prevSeasonTotals = [];
        $currentSeasonLandTotals = [];
        
        foreach ($seasons as $season) {
            $currentSeasonTotals[$season] = $groupedCurrentData[$season]->sum('production');
            $currentSeasonLandTotals[$season] = $groupedCurrentData[$season]->sum('land');
            
            if ($groupedPrevData->has($season)) {
                $prevSeasonTotals[$season] = $groupedPrevData[$season]->sum('production');
            } else {
                $prevSeasonTotals[$season] = 0;
            }
        }
    @endphp

    const seasons = @json($seasons);
    const currentSeasonProduction = @json(array_values($currentSeasonTotals));
    const prevSeasonProduction = @json(array_values($prevSeasonTotals));
    const currentSeasonLand = @json(array_values($currentSeasonLandTotals));

    // Vibrant color palette for charts
    const vibrantColors = {
        currentYear: [
            'rgba(54, 162, 235, 0.8)',   // Blue
            'rgba(75, 192, 192, 0.8)',   // Teal
            'rgba(153, 102, 255, 0.8)',  // Purple
            'rgba(255, 159, 64, 0.8)',   // Orange
            'rgba(255, 99, 132, 0.8)',   // Pink
            'rgba(255, 205, 86, 0.8)'    // Yellow
        ],
        previousYear: [
            'rgba(201, 203, 207, 0.8)',  // Gray
            'rgba(150, 150, 150, 0.8)',  // Dark Gray
            'rgba(100, 100, 100, 0.8)',  // Darker Gray
            'rgba(50, 50, 50, 0.8)',     // Almost Black
            'rgba(120, 120, 120, 0.8)',  // Medium Gray
            'rgba(180, 180, 180, 0.8)'   // Light Gray
        ],
        pieColors: [
            '#FF6384', // Red
            '#36A2EB', // Blue
            '#FFCE56', // Yellow
            '#4BC0C0', // Teal
            '#9966FF', // Purple
            '#FF9F40', // Orange
            '#C9CBCF', // Gray
            '#77DD77'  // Green
        ],
        borderColors: [
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(255, 205, 86, 1)'
        ]
    };

    // Bar Chart - Season Production Comparison
    if (document.getElementById('barChart')) {
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: `{{ __('Current Year') }} ({{ $year }})`,
                        data: currentSeasonProduction,
                        backgroundColor: vibrantColors.currentYear.slice(0, seasons.length),
                        borderColor: vibrantColors.borderColors.slice(0, seasons.length),
                        borderWidth: 2,
                        borderRadius: 5,
                        borderSkipped: false,
                    },
                    {
                        label: `{{ __('Previous Year') }} ({{ $prevYear }})`,
                        data: prevSeasonProduction,
                        backgroundColor: vibrantColors.previousYear.slice(0, seasons.length),
                        borderColor: 'rgba(100, 100, 100, 1)',
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
                                label += context.parsed.y.toLocaleString() + ' Qt';
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
                            text: '{{ __('Production (Quintal)') }}',
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

    // Line Chart - Season Production Trend
    if (document.getElementById('lineChart')) {
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: seasons,
                datasets: [
                    {
                        label: `{{ __('Current Year') }} ({{ $year }})`,
                        data: currentSeasonProduction,
                        borderColor: '#FF6384', // Red
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#FF6384',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: `{{ __('Previous Year') }} ({{ $prevYear }})`,
                        data: prevSeasonProduction,
                        borderColor: '#36A2EB', // Blue
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#36A2EB',
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
                                label += context.parsed.y.toLocaleString() + ' Qt';
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
                            text: '{{ __('Production (Quintal)') }}',
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

    // Pie Chart - Land Distribution by Season
    if (document.getElementById('pieChart')) {
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: seasons,
                datasets: [{
                    data: currentSeasonLand,
                    backgroundColor: vibrantColors.pieColors.slice(0, seasons.length),
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
                                label += value.toLocaleString() + ' Ha (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

</x-layout>