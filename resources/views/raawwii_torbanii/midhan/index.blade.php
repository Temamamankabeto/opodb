<x-layout>
    <script src="{{ asset('assets/css/customside.js') }}"></script>

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h6><i class="fas fa-seedling text-success mr-2"></i>{{ __('Raawwii Torbanii') }}</h6>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Success Alert -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> {{ __('Qabxiiwwan') }}!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters Card -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-filter mr-2"></i>{{ __('Mormii Itti Fufu') }}
                    </h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('sector.crop-rawwii.index') }}" id="filterForm">
                        <div class="row">
                            <!-- Year Filter -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yearFilter">{{ __('Waggaa Filadhu') }} <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="yearFilter" name="year" required>
                                        <option value="">-- {{ __('Waggaa Filadhu') }} --</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">{{ __('Waggaa jalqabaa filadhu') }}</small>
                                </div>
                            </div>

                            <!-- Season Filter -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="seasonFilter">{{ __('Akaakayyii Filadhu') }}</label>
                                    <select class="form-control select2" id="seasonFilter" name="season" {{ !$selectedYear ? 'disabled' : '' }}>
                                        <option value="">-- {{ __('Waggaa jalqabaa Filadhu') }} --</option>
                                        @if($selectedYear)
                                            @foreach($seasons as $season)
                                                <option value="{{ $season }}" {{ $selectedSeason == $season ? 'selected' : '' }}>
                                                    {{ ucfirst($season) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div id="seasonLoading" class="text-muted" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i> {{ __('Akaakayyiiwwaniiyyuu Barbaaduu') }}...
                                    </div>
                                </div>
                            </div>

                            <!-- Crop Filter -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cropFilter">{{ __('Alaayyii Filadhu') }}</label>
                                    <select class="form-control select2" id="cropFilter" name="crop" {{ !$selectedYear || !$selectedSeason ? 'disabled' : '' }}>
                                        <option value="">-- {{ __('Akaakayyii jalqabaa Filadhu') }} --</option>
                                        @if($selectedYear && $selectedSeason)
                                            @foreach($crops as $cropId => $cropName)
                                                <option value="{{ $cropId }}" {{ $selectedCrop == $cropId ? 'selected' : '' }}>
                                                    {{ $cropName }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div id="cropLoading" class="text-muted" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i> {{ __('Alaayyiiwwaniiyyuu Barbaaduu') }}...
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mt-4">
                                    <!-- Search Button -->
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-search mr-2"></i> {{ __('Barbaadi') }}
                                    </button>

                                    <!-- Reset Button -->
                                    <a href="{{ route('sector.crop-karora.index') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-redo mr-2"></i> {{ __('Oofsi') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (!$selectedYear)
                <!-- Initial state - no filters selected -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-filter fa-3x text-primary mb-3"></i>
                        <h4 class="text-primary">{{ __('Mormiiwwan Filadhu') }}</h4>
                        <p class="text-muted">{{ __('Dhimmi keessan ilaalchisee beekumsa argachuuf waggaa jalqabaa filadhu') }}</p>
                        <div class="mt-4">
                            <i class="fas fa-arrow-down fa-2x text-muted"></i>
                        </div>
                    </div>
                </div>
            @elseif ($cropPlans->isEmpty())
                <!-- Filter applied but no data found -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-warning mb-3"></i>
                        <h4 class="text-warning">{{ __('Mormiiwwan Filatamaniiyyuu Qorannoon Hin Argamne') }}</h4>
                        <p class="text-muted mb-4">
                            @if($selectedYear)
                                {{ __('Waggaa') }}: <strong>{{ $selectedYear }}</strong>
                                @if($selectedSeason)
                                    | {{ __('Akaakayyii') }}: <strong>{{ ucfirst($selectedSeason) }}</strong>
                                    @if($selectedCrop && isset($crops[$selectedCrop]))
                                        | {{ __('Alaayyii') }}: <strong>{{ $crops[$selectedCrop] }}</strong>
                                    @endif
                                @endif
                            @endif
                        </p>
                        <a href="{{ route('sector.crop-karora.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo mr-2"></i>{{ __('Mormiiwwanii Cabsisi') }}
                        </a>
                    </div>
                </div>
            @else
                @php
                    $currentYear = max($cropPlans->pluck('year')->toArray());
                    $previousYear = $currentYear - 1;
                    
                    // Prepare productivity comparison data
                    $currentYearData = $cropPlans->where('year', $currentYear)->groupBy('crop_id');
                    $previousYearData = $cropPlans->where('year', $previousYear)->groupBy('crop_id');
                    
                    $productivityData = [];
                    foreach($currentYearData as $cropId => $plans) {
                        $crop = $plans->first()->crop;
                        if ($crop) {
                            $productivityData[$cropId] = [
                                'current_avg' => $plans->avg('productivity'),
                                'previous_avg' => 0
                            ];
                        }
                    }
                    foreach($previousYearData as $cropId => $plans) {
                        $crop = $plans->first()->crop;
                        if ($crop) {
                            if (!isset($productivityData[$cropId])) {
                                $productivityData[$cropId] = [
                                    'current_avg' => 0,
                                    'previous_avg' => $plans->avg('productivity')
                                ];
                            } else {
                                $productivityData[$cropId]['previous_avg'] = $plans->avg('productivity');
                            }
                        }
                    }

                    // Group data properly for hierarchical display
                    $groupedData = [];
                    foreach ($cropPlans->groupBy('year') as $year => $plansByYear) {
                        foreach ($plansByYear->groupBy('croptype_id') as $typeId => $plansByType) {
                            $cropType = $plansByType->first()->cropType;
                            foreach ($plansByType->groupBy('season') as $season => $plansBySeason) {
                                foreach ($plansBySeason as $plan) {
                                    $groupedData[] = [
                                        'year' => $year,
                                        'type_id' => $typeId,
                                        'crop_type' => $cropType,
                                        'season' => $season,
                                        'plan' => $plan
                                    ];
                                }
                            }
                        }
                    }
                @endphp

                <!-- Main Data Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-table mr-2"></i>{{ __('Qorannoo Raawwii Alaayyii') }}
                        </h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a class="dropdown-item" href="#" onclick="exportToExcel()">
                                        <i class="fas fa-file-excel mr-2 text-success"></i>{{ __('Excelitti Maxxansisi') }}
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                        <i class="fas fa-file-pdf mr-2 text-danger"></i>{{ __('PDFtti Maxxansisi') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" onclick="printTable()">
                                        <i class="fas fa-print mr-2"></i>{{ __('Maxxansi') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered mb-0" id="cropPlansTable">
                                <thead>
                                    <tr class="bg-gradient-dark">
                                        <th style="width: 8%">{{ __('Waggaa') }}</th>
                                        <th style="width: 8%">{{ __('Akaakayyii') }}</th>
                                        <th style="width: 15%">{{ __('Alaayyii') }}</th>
                                        <th style="width: 8%">{{ __('Haala (ha)') }}</th>
                                        <th style="width: 12%">{{ __('Qarshii Eegamaa (qt)') }}</th>
                                        <th style="width: 4%">{{ __('Raawwii Torbanii') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $currentYearIndex = null;
                                        $currentTypeIndex = null;
                                        $currentSeasonIndex = null;
                                        $yearRowspan = 0;
                                        $typeRowspan = 0;
                                        $seasonRowspan = 0;
                                        $yearCounter = 0;
                                        $typeCounter = 0;
                                        $seasonCounter = 0;
                                    @endphp

                                    @foreach($groupedData as $index => $data)
                                        @php
                                            $year = $data['year'];
                                            $typeId = $data['type_id'];
                                            $cropType = $data['crop_type'];
                                            $season = $data['season'];
                                            $plan = $data['plan'];
                                            
                                            $cropId = $plan->crop_id;
                                            $currentProductivity = $plan->productivity ?? 0;
                                            $previousProductivity = $productivityData[$cropId]['previous_avg'] ?? 0;
                                            $hasPreviousYear = $previousProductivity > 0;
                                            $change = $hasPreviousYear ? (($currentProductivity - $previousProductivity) / $previousProductivity) * 100 : 0;

                                            // Calculate rowspans dynamically
                                            if ($currentYearIndex !== $year) {
                                                $currentYearIndex = $year;
                                                $yearRowspan = array_reduce($groupedData, function($count, $item) use ($year) {
                                                    return $item['year'] == $year ? $count + 1 : $count;
                                                }, 0);
                                                $yearCounter = 0;
                                            }

                                            if ($currentTypeIndex !== $year . '_' . $typeId) {
                                                $currentTypeIndex = $year . '_' . $typeId;
                                                $typeRowspan = array_reduce($groupedData, function($count, $item) use ($year, $typeId) {
                                                    return $item['year'] == $year && $item['type_id'] == $typeId ? $count + 1 : $count;
                                                }, 0);
                                                $typeCounter = 0;
                                            }

                                            if ($currentSeasonIndex !== $year . '_' . $typeId . '_' . $season) {
                                                $currentSeasonIndex = $year . '_' . $typeId . '_' . $season;
                                                $seasonRowspan = array_reduce($groupedData, function($count, $item) use ($year, $typeId, $season) {
                                                    return $item['year'] == $year && $item['type_id'] == $typeId && $item['season'] == $season ? $count + 1 : $count;
                                                }, 0);
                                                $seasonCounter = 0;
                                            }

                                            $yearCounter++;
                                            $typeCounter++;
                                            $seasonCounter++;
                                        @endphp
                                        
                                        <tr>
                                            <!-- Year Column -->
                                            @if ($yearCounter === 1)
                                                <td class="year-group text-center align-middle" rowspan="{{ $yearRowspan }}">
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-primary mb-1">{{ $year }}</strong>
                                                        @if($year == date('Y'))
                                                            <span class="badge badge-success">{{ __('Ammaa') }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif

                                            <!-- Season Column -->
                                            @if ($seasonCounter === 1)
                                                <td class="season-group text-center align-middle" rowspan="{{ $seasonRowspan }}">
                                                    <span class="badge bg-warning text-dark">
                                                        {{ ucfirst($season) }}
                                                    </span>
                                                </td>
                                            @endif

                                            <!-- Crop Details -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="crop-icon mr-2">
                                                        <i class="fas fa-seedling text-success"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $plan->crop->name ?? '-' }}</strong>
                                                        @if($year == $currentYear && $hasPreviousYear)
                                                            <div>
                                                                <small class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                                                    {{ $change >= 0 ? '↗' : '↘' }}
                                                                    {{ number_format(abs($change), 1) }}%
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="text-right">
                                                <strong class="text-primary">{{ number_format($plan->area_to_plant, 2) }}</strong>
                                            </td> 
                                            <td class="text-right">
                                                <strong class="text-success">{{ number_format($plan->expected_yield, 2) }}</strong>
                                            </td>
                                            <!-- Actions -->
                                            <td class="action-group">
                                                <div class="btn-group btn-group-sm">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" 
                                                                data-toggle="dropdown" title="{{ __('Dabalataa Raawwii') }}">
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{ route('sector.crop-plans.achivement', $plan->id ) }}">
                                                                <i class="fas fa-flask mr-2"></i>{{ __('Dachii') }}
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('sector.crop-plans.productionachivement', $plan->id ) }}">
                                                                <i class="fas fa-industry mr-2"></i>{{ __('Oomisha') }}
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('sector.fertilizer-achivement.edit', $plan->id ) }}">
                                                                <i class="fas fa-flask mr-2"></i>{{ __('Calalguddiftuu') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="2" class="text-right"><strong>{{ __('Waliigalaa') }}:</strong></td>
                                        <td></td>
                                        <td> 
                                            <strong class="text-right">
                                                {{ number_format($cropPlans->sum('area_to_plant'), 2) }} {{ __('ha') }}
                                            </strong>
                                        </td>
                                        <td class="text-right">
                                            <strong class="text-success">
                                                {{ number_format($cropPlans->sum('expected_yield'), 2) }} {{ __('qt') }}
                                            </strong>
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="float-left">
                            <small class="text-muted">{{ __('Raawwiiwwan') }} {{ count($groupedData) }} {{ __('agarsiisa') }}</small>
                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-default btn-sm" onclick="scrollToTop()">
                                <i class="fas fa-arrow-up mr-1"></i>{{ __('Irra Deebi\'i') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // YEAR → SEASON
        $("#yearFilter").on("change", function () {
            let year = $(this).val();

            // Reset season & crop
            $("#seasonFilter").empty().append('<option value="">-- {{ __('Akaakayyiiwwaniiyyuu Barbaaduu') }}... --</option>');
            $("#cropFilter").empty().append('<option value="">-- {{ __('Akaakayyii jalqabaa Filadhu') }} --</option>').prop("disabled", true);

            if (!year) {
                $("#seasonFilter").empty().append('<option value="">-- {{ __('Waggaa jalqabaa Filadhu') }} --</option>').prop("disabled", true);
                return;
            }

            // Show loading
            $("#seasonLoading").show();
            $("#seasonFilter").prop("disabled", true);

            $.ajax({
                url: "{{ route('sector.crop-karora.get-seasons') }}",
                type: "GET",
                data: { year: year },
                success: function (seasons) {
                    $("#seasonFilter").prop("disabled", false).empty().append('<option value="">-- {{ __('Akaakayyii Filadhu') }} --</option>');
                    $.each(seasons, function (i, season) {
                        $("#seasonFilter").append('<option value="' + season + '">' + season + '</option>');
                    });
                    $("#seasonLoading").hide();
                },
                error: function () {
                    $("#seasonFilter").empty().append('<option value="">-- {{ __('Dogoggora ta\'e') }} --</option>');
                    $("#seasonLoading").hide();
                }
            });
        });

        // SEASON → CROP
        $("#seasonFilter").on("change", function () {
            let season = $(this).val();
            let year = $("#yearFilter").val();

            $("#cropFilter").empty().append('<option value="">-- {{ __('Alaayyiiwwaniiyyuu Barbaaduu') }}... --</option>');

            if (!season) {
                $("#cropFilter").empty().append('<option value="">-- {{ __('Akaakayyii jalqabaa Filadhu') }} --</option>').prop("disabled", true);
                return;
            }

            // Show loading
            $("#cropLoading").show();
            $("#cropFilter").prop("disabled", true);

            $.ajax({
                url: "{{ route('sector.crop-karora.get-crops') }}",
                type: "GET",
                data: { year: year, season: season },
                success: function (crops) {
                    $("#cropFilter").prop("disabled", false).empty().append('<option value="">-- {{ __('Alaayyii Filadhu') }} --</option>');
                    $.each(crops, function (id, name) {
                        $("#cropFilter").append('<option value="' + id + '">' + name + '</option>');
                    });
                    $("#cropLoading").hide();
                },
                error: function () {
                    $("#cropFilter").empty().append('<option value="">-- {{ __('Dogoggora ta\'e') }} --</option>');
                    $("#cropLoading").hide();
                }
            });
        });

        // Export functions
        function exportToExcel() {
            alert("{{ __('Excelitti Maxxansisi Raawwiiwwan') }}");
            // Add actual export logic here
        }

        function exportToPDF() {
            alert("{{ __('PDFtti Maxxansisi Raawwiiwwan') }}");
            // Add actual export logic here
        }

        function printTable() {
            window.print();
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    </script>

    <style>
        .year-group {
            background-color: #f8f9fa;
            border-right: 2px solid #dee2e6;
        }
        .season-group {
            background-color: #fff3cd;
        }
        .action-group {
            white-space: nowrap;
        }
        .crop-icon {
            width: 24px;
            text-align: center;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        .card-title {
            color: #495057;
        }
        .btn-tool {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
    </style>

</x-layout>