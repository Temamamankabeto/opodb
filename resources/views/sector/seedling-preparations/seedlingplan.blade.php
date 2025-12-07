<x-layout>
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('Edit Seedling Planting Schedule') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('sector.seedling-preparations.index') }}">{{ __('Seedling Preparations') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Edit Planting Schedule') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-ban"></i> {{ __('Validation Error!') }}</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-tree mr-2"></i>{{ __('Seedling Planting Schedule') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('sector.seedling-plans.update', $seedlingPreparation->id) }}" method="POST" id="seedlingPlanForm">
                                @csrf
                                @method('PUT')

                                <!-- Hidden field to store structured planting data -->
                                <input type="hidden" name="plantingdata_detail" id="plantingData"
                                       value="{{ json_encode($seedlingPreparation->plantingdata_detail ?? []) }}">

                                <div class="card-body">
                                    <!-- Basic Information Display -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ __('Seedling Information') }}
                                            </h5>
                                            <hr class="mt-0">
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-success"><i class="fas fa-tree"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Seedling Type') }}</span>
                                                    <span class="info-box-number">{{ $seedlingPreparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Year') }}</span>
                                                    <span class="info-box-number">{{ $seedlingPreparation->year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 col-sm-4">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Planting Year') }}</span>
                                                    <span class="info-box-number">{{ $seedlingPreparation->year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-seedling"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Status') }}</span>
                                                    <span class="info-box-number">{{ $seedlingPreparation->status ?? __('Active') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Information Display -->
                                    <div class="row mb-4">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-success"><i class="fas fa-box"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Total Prepared') }}</span>
                                                    <span class="info-box-number" id="totalPreparedQuantity">{{ number_format($transformedSeedlingPlans[0]['total_prepared_quantity'] ?? 0, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-tree"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Already Planted') }}</span>
                                                    <span class="info-box-number" id="totalPlantedQuantity">{{ number_format($transformedSeedlingPlans[0]['total_planted_quantity'] ?? 0, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-info"><i class="fas fa-calculator"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Remaining to Plant') }}</span>
                                                    <span class="info-box-number" id="remainingQuantity">{{ number_format($transformedSeedlingPlans[0]['remaining_quantity'] ?? 0, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon bg-secondary"><i class="fas fa-tasks"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{ __('Planting Progress') }}</span>
                                                    <span class="info-box-number" id="plantingProgress">0%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seedlings List -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-seedling mr-1"></i>
                                                {{ __('Available Seedlings for Planting') }}
                                            </h5>
                                            <hr class="mt-0">
                                            <div class="row">
                                                @php
                                                    $totalPrepared = 0;
                                                    $totalPlanted = 0;
                                                    $totalHangaDhaabbii = 0;
                                                    $totalHangaQophii = 0;
                                                @endphp
                                                
                                                @foreach($seedlingPreparation->preparation_data['selected_seedlings'] ?? [] as $seedling)
                                                    @php
                                                        // Calculate prepared quantity for this seedling
                                                        $preparedQty = $seedling['hanga_qophii'] ?? 0;
                                                        $totalPrepared += $preparedQty;
                                                        $totalHangaDhaabbii += $seedling['nama_hirmaate_qophii'] ?? 0;
                                                        $totalHangaQophii += $seedling['baayina_guyyaa_qophii'] ?? 0;
                                                        
                                                        // Calculate already planted quantity from planting data
                                                        $plantedQty = 0;
                                                        $plantedHangaDhaabbii = 0;
                                                        $plantedHangaQophii = 0;
                                                        
                                                        if(isset($seedlingPreparation->plantingdata_detail['selected_seedlings'])) {
                                                            foreach($seedlingPreparation->plantingdata_detail['selected_seedlings'] as $plantedSeedling) {
                                                                if($plantedSeedling['name'] == $seedling['name']) {
                                                                    if(isset($plantedSeedling['weekly_planting'])) {
                                                                        foreach($plantedSeedling['weekly_planting'] as $weekly) {
                                                                            $plantedQty += $weekly['quantity'] ?? 0;
                                                                            $plantedHangaDhaabbii += $weekly['nama_hirmaate_dhaabbii'] ?? 0;
                                                                            $plantedHangaQophii += $weekly['baayina_guyyaa_dhaabbii'] ?? 0;
                                                                        }
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        $totalPlanted += $plantedQty;
                                                        
                                                        $remainingQty = max(0, $preparedQty - $plantedQty);
                                                        $remainingHangaDhaabbii = max(0, ($seedling['nama_hirmaate_qophii'] ?? 0) - $plantedHangaDhaabbii);
                                                        $remainingHangaQophii = max(0, ($seedling['baayina_guyyaa_qophii'] ?? 0) - $plantedHangaQophii);
                                                    @endphp
                                                    
                                                    <div class="col-md-4 col-sm-6 mb-3">
                                                        <div class="card bg-light h-100">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                    <div>
                                                                        <h6 class="card-title mb-1 text-success">{{ $seedling['name'] }}</h6>
                                                                        <small class="text-muted">{{ __('Seedling ID') }}: {{ $seedling['id'] ?? 'N/A' }}</small>
                                                                    </div>
                                                                    <span class="badge badge-pill badge-success">{{ number_format($preparedQty, 2) }}</span>
                                                                </div>
                                                                
                                                                <div class="seedling-stats mt-3">
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Prepared Seedlings') }}:</small>
                                                                        <small class="font-weight-bold text-success">{{ number_format($preparedQty, 2) }}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Planted Seedlings') }}:</small>
                                                                        <small class="font-weight-bold text-warning">{{ number_format($plantedQty, 2) }}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Remaining Seedlings') }}:</small>
                                                                        <small class="font-weight-bold text-info">{{ number_format($remainingQty, 2) }}</small>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="seedling-resources mt-3">
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Prepared Hanga Dhaabbii') }}:</small>
                                                                        <small class="font-weight-bold text-primary">{{ number_format($seedling['nama_hirmaate_qophii'] ?? 0, 2) }}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Used Hanga Dhaabbii') }}:</small>
                                                                        <small class="font-weight-bold text-danger">{{ number_format($plantedHangaDhaabbii, 2) }}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Remaining Hanga Dhaabbii') }}:</small>
                                                                        <small class="font-weight-bold text-success">{{ number_format($remainingHangaDhaabbii, 2) }}</small>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="seedling-resources mt-2">
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Prepared Hanga Qophii') }}:</small>
                                                                        <small class="font-weight-bold text-secondary">{{ number_format($seedling['baayina_guyyaa_qophii'] ?? 0, 2) }}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Used Hanga Qophii') }}:</small>
                                                                        <small class="font-weight-bold text-danger">{{ number_format($plantedHangaQophii, 2) }}</small>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <small class="text-muted">{{ __('Remaining Hanga Qophii') }}:</small>
                                                                        <small class="font-weight-bold text-success">{{ number_format($remainingHangaQophii, 2) }}</small>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="progress mb-2" style="height: 5px;">
                                                                    @if($preparedQty > 0)
                                                                        <div class="progress-bar bg-success" style="width: {{ ($plantedQty / $preparedQty) * 100 }}%"></div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                
                                                <!-- Store totals for JavaScript -->
                                                <input type="hidden" id="totalPreparedHidden" value="{{ $totalPrepared }}">
                                                <input type="hidden" id="totalPlantedHidden" value="{{ $totalPlanted }}">
                                                <input type="hidden" id="totalHangaDhaabbiiHidden" value="{{ $totalHangaDhaabbii }}">
                                                <input type="hidden" id="totalHangaQophiiHidden" value="{{ $totalHangaQophii }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seedling Scheduling Section -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-info mb-3">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ __('Seedling Planting Schedule') }}
                                            </h5>
                                            <hr class="mt-0">
                                            <p class="text-muted">
                                                {{ __('Select multiple quarters, months, and schedule planting across weeks for planting year') }} ({{ $seedlingPreparation->year }}).
                                            </p>
                                            <div class="callout callout-info mt-2">
                                                <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Planting Status') }}</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Remaining Seedlings') }}</small>
                                                        <div class="font-weight-bold text-primary">{{ number_format($transformedSeedlingPlans[0]['remaining_quantity'] ?? 0, 2) }}</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Planned Hanga Dhaabbii') }}</small>
                                                        <div class="font-weight-bold text-info" id="scheduledHangaDhaabbiiDisplay">0</div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">{{ __('Planned Hanga Qophii') }}</small>
                                                        <div class="font-weight-bold text-warning" id="scheduledHangaQophiiDisplay">0</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quarters Selection -->
                                    <div class="card card-outline card-primary mb-3">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-range mr-2"></i>
                                                {{ __('Select Quarters') }} ({{ $seedlingPreparation->year }})
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" onclick="selectAllQuarters()" title="{{ __('Select All') }}">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" onclick="clearAllQuarters()" title="{{ __('Clear All') }}">
                                                    <i class="fas fa-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="quartersContainer">
                                                <!-- Quarters will be populated here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Months Section (Initially Hidden) -->
                                    <div class="card card-outline card-info mb-3" id="monthsSection" style="display: none;">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-month mr-2"></i>
                                                {{ __('Select Months') }} ({{ $seedlingPreparation->year }})
                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" onclick="selectAllMonths()" title="{{ __('Select All') }}">
                                                    <i class="fas fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-tool" onclick="clearAllMonths()" title="{{ __('Clear All') }}">
                                                    <i class="fas fa-square"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th width="50">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input class="custom-control-input" type="checkbox" id="selectAllMonthsCheckbox">
                                                                    <label for="selectAllMonthsCheckbox" class="custom-control-label"></label>
                                                                </div>
                                                            </th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Year') }}</th>
                                                            <th>{{ __('Planned Hanga Dhaabbii') }}</th>
                                                            <th>{{ __('Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="monthsTable">
                                                        <!-- Months will be populated here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Weeks Section (Initially Hidden) -->
                                    <div class="card card-outline card-warning mb-3" id="weeksSection" style="display: none;">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-calendar-week mr-2"></i>
                                                {{ __('Schedule Planting Details') }} ({{ $seedlingPreparation->year }})
                                            </h3>
                                            <div class="card-tools">
                                                <span class="badge badge-success" id="totalScheduledBadge">0 Hanga Dhaabbii</span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>{{ __('Week') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Date Range') }}</th>
                                                            <th>{{ __('Year') }}</th>
                                                            <th>{{ __('Seedling') }}</th>
                                                            <th width="120">{{ __('Available Resources') }}</th>
                                                            <th width="120">{{ __('Hanga Dhaabbii') }}</th>
                                                            <th width="120">{{ __('Hanga Qophii') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="weeksTable">
                                                        <!-- Weeks will be populated here -->
                                                    </tbody>
                                                    <tfoot class="bg-light">
                                                        <tr>
                                                            <td colspan="7" class="text-right font-weight-bold">{{ __('Total Planned') }}:</td>
                                                            <td class="font-weight-bold text-info" id="weeksTotalHangaDhaabbii">0</td>
                                                            <td class="font-weight-bold text-warning" id="weeksTotalHangaQophii">0</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="callout callout-info mt-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h6><i class="fas fa-info-circle mr-2"></i>{{ __('Instructions') }}</h6>
                                                        <p class="mb-0">{{ __('Enter Hanga Dhaabbii and Hanga Qophii for each week. The system will validate against available resources.') }}</p>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-success" onclick="saveAllSchedules()">
                                                            <i class="fas fa-save mr-2"></i> {{ __('Save All Schedules') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-list-alt mr-2"></i>
                                                {{ __('Planting Summary') }} ({{ __('Planting Year') }}: {{ $seedlingPreparation->year }})
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>{{ __('Year') }}</th>
                                                            <th>{{ __('Seedling') }}</th>
                                                            <th>{{ __('Quarter') }}</th>
                                                            <th>{{ __('Month') }}</th>
                                                            <th>{{ __('Week') }}</th>
                                                            <th>{{ __('Date Range') }}</th>
                                                            <th>{{ __('Available Seedlings') }}</th>
                                                            <th>{{ __('Hanga Dhaabbii') }}</th>
                                                            <th>{{ __('Hanga Qophii') }}</th>
                                                            <th>{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summaryTable">
                                                        <tr>
                                                            <td colspan="10" class="text-center text-muted py-4">
                                                                <i class="fas fa-tree mr-2"></i>
                                                                {{ __('No planting scheduled yet. Start by selecting quarters above.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-gray">
                                                        <tr>
                                                            <td colspan="7" class="text-right font-weight-bold">{{ __('Total') }}:</td>
                                                            <td class="font-weight-bold text-info" id="summaryTotalHangaDhaabbii">0</td>
                                                            <td class="font-weight-bold text-warning" id="summaryTotalHangaQophii">0</td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-default">
                                                <i class="fas fa-arrow-left mr-2"></i> {{ __('Back to Seedling Preparations') }}
                                            </a>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="submit" class="btn btn-success ml-2" id="submitBtn">
                                                <i class="fas fa-save mr-2"></i> {{ __('Update Planting Schedule') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    {{-- JavaScript Section --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Translations for JavaScript messages
        const translations = {
            please_select_quarters_first: "{{ __('Please select quarters first') }}",
            all_quarters_selected: "{{ __('All quarters selected for planting year') }}",
            all_quarters_cleared: "{{ __('All quarters cleared') }}",
            all_months_selected: "{{ __('All months in selected quarters selected for planting year') }}",
            all_months_cleared: "{{ __('All months cleared') }}",
            all_schedules_saved: "{{ __('All schedules saved successfully for planting year') }}",
            schedule_removed: "{{ __('Schedule removed successfully!') }}",
            no_seedlings_available: "{{ __('No seedlings available to schedule for this month') }}",
            scheduled_equally: "{{ __('Distributed equally across weeks in') }}",
            all_schedules_cleared: "{{ __('All schedules cleared for') }}",
            no_months_available: "{{ __('No months available in selected quarters.') }}",
            fix_validation_errors: "{{ __('Please fix validation errors before saving') }}",
            no_schedule_warning: "{{ __('No planting has been scheduled for planting year . Do you want to proceed without scheduling?') }}",
            please_add_schedule: "{{ __('Please add planting schedule or confirm to proceed without it') }}",
            scheduled_exceeds_available: "{{ __('Planned resources exceeds available resources') }}",
            exceeds_available_hanga_dhaabbii: "{{ __('Exceeds available Hanga Dhaabbii') }}",
            exceeds_available_hanga_qophii: "{{ __('Exceeds available Hanga Qophii') }}",
            value_cannot_negative: "{{ __('Value cannot be negative') }}",
            valid: "{{ __('Valid') }}",
            week: "{{ __('Week') }}",
            days: "{{ __('Days') }}",
            year: "{{ __('Year') }}",
            scheduled: "{{ __('Scheduled') }}",
            not_scheduled: "{{ __('Not Scheduled') }}",
            partially_scheduled: "{{ __('Partially Scheduled') }}",
            months: "{{ __('months') }}",
            quarters: "{{ __('quarters') }}",
            weeks: "{{ __('weeks') }}",
            selected: "{{ __('Selected') }}",
            seedlings: "{{ __('seedlings') }}",
            q: "{{ __('Q') }}",
            please_fix_errors: "{{ __('Please fix the validation errors before submitting.') }}",
            planting_year: "{{ __('Planting Year') }}",
            available: "{{ __('Available') }}",
            planted: "{{ __('Planted') }}",
            hanga_dhaabbii: "{{ __('Hanga Dhaabbii') }}",
            hanga_qophii: "{{ __('Hanga Qophii') }}",
            exceeds_hanga_dhaabbii: "{{ __('Exceeds available Hanga Dhaabbii') }}",
            exceeds_hanga_qophii: "{{ __('Exceeds available Hanga Qophii') }}"
        };

        // Calculate initial available seedlings per type
        function calculateAvailableSeedlingsPerType() {
            const availableData = {};
            
            @foreach($seedlingPreparation->preparation_data['selected_seedlings'] ?? [] as $seedling)
                @php
                    $seedlingName = $seedling['name'];
                    $preparedQty = $seedling['hanga_qophii'] ?? 0;
                    $preparedHangaDhaabbii = $seedling['nama_hirmaate_qophii'] ?? 0;
                    $preparedHangaQophii = $seedling['baayina_guyyaa_qophii'] ?? 0;
                    
                    $plantedQty = 0;
                    $plantedHangaDhaabbii = 0;
                    $plantedHangaQophii = 0;
                    
                    // Calculate already planted from existing planting data
                    if(isset($seedlingPreparation->plantingdata_detail['selected_seedlings'])) {
                        foreach($seedlingPreparation->plantingdata_detail['selected_seedlings'] as $plantedSeedling) {
                            if($plantedSeedling['name'] == $seedlingName) {
                                if(isset($plantedSeedling['weekly_planting'])) {
                                    foreach($plantedSeedling['weekly_planting'] as $weekly) {
                                        $plantedQty += $weekly['quantity'] ?? 0;
                                        $plantedHangaDhaabbii += $weekly['nama_hirmaate_dhaabbii'] ?? 0;
                                        $plantedHangaQophii += $weekly['baayina_guyyaa_dhaabbii'] ?? 0;
                                    }
                                }
                                break;
                            }
                        }
                    }
                @endphp
                
                availableData['{{ $seedlingName }}'] = {
                    prepared: {{ $preparedQty }},
                    planted: {{ $plantedQty }},
                    available: {{ max(0, $preparedQty - $plantedQty) }},
                    hanga_dhaabbii_prepared: {{ $preparedHangaDhaabbii }},
                    hanga_dhaabbii_planted: {{ $plantedHangaDhaabbii }},
                    hanga_dhaabbii_available: {{ max(0, $preparedHangaDhaabbii - $plantedHangaDhaabbii) }},
                    hanga_qophii_prepared: {{ $preparedHangaQophii }},
                    hanga_qophii_planted: {{ $plantedHangaQophii }},
                    hanga_qophii_available: {{ max(0, $preparedHangaQophii - $plantedHangaQophii) }}
                };
            @endforeach
            
            return availableData;
        }

        let availableSeedlingsData = calculateAvailableSeedlingsPerType();
        let totalPrepared = parseFloat({{ $transformedSeedlingPlans[0]['total_prepared_quantity'] ?? 0 }});
        let alreadyPlanted = parseFloat({{ $transformedSeedlingPlans[0]['total_planted_quantity'] ?? 0 }});
        let remainingSeedlings = parseFloat({{ $transformedSeedlingPlans[0]['remaining_quantity'] ?? 0 }});
        let totalHangaDhaabbii = parseFloat(document.getElementById('totalHangaDhaabbiiHidden').value || 0);
        let totalHangaQophii = parseFloat(document.getElementById('totalHangaQophiiHidden').value || 0);
        
        let selectedQuarters = new Set();
        let selectedMonths = new Set();
        let plantingSummaryRows = [];
        const plantingYear = {{ $seedlingPreparation->year }};

        // Ethiopian Calendar Data with translations
        const EC_MONTHS = [
            { id: 1, name: "{{ __('Meskerem') }}", days: 30 },
            { id: 2, name: "{{ __('Tikimt') }}", days: 30 },
            { id: 3, name: "{{ __('Hidar') }}", days: 30 },
            { id: 4, name: "{{ __('Tahsas') }}", days: 30 },
            { id: 5, name: "{{ __('Tir') }}", days: 30 },
            { id: 6, name: "{{ __('Yekatit') }}", days: 30 },
            { id: 7, name: "{{ __('Megabit') }}", days: 30 },
            { id: 8, name: "{{ __('Miyazia') }}", days: 30 },
            { id: 9, name: "{{ __('Ginbot') }}", days: 30 },
            { id: 10, name: "{{ __('Sene') }}", days: 30 },
            { id: 11, name: "{{ __('Hamle') }}", days: 30 },
            { id: 12, name: "{{ __('Nehase') }}", days: 30 },
            { id: 13, name: "{{ __('Pagumen') }}", days: 5 }
        ];

        const EC_QUARTERS = {
            1: [1, 2, 3],
            2: [4, 5, 6],
            3: [7, 8, 9],
            4: [10, 11, 12, 13]
        };

        // Status calculation function
        function calculateStatus(hangaDhaabbiiValue) {
            if (hangaDhaabbiiValue === 0) return translations.not_scheduled;
            return translations.scheduled;
        }

        function getStatusClass(status) {
            switch(status) {
                case translations.not_scheduled: return 'badge-secondary';
                case translations.scheduled: return 'badge-success';
                case translations.partially_scheduled: return 'badge-warning';
                default: return 'badge-secondary';
            }
        }

        // Get available quantity for a specific seedling
        function getAvailableForSeedling(seedlingName) {
            if (availableSeedlingsData[seedlingName]) {
                return availableSeedlingsData[seedlingName].available;
            }
            return 0;
        }

        // Get already planted quantity for a specific seedling
        function getAlreadyPlantedForSeedling(seedlingName) {
            if (availableSeedlingsData[seedlingName]) {
                return availableSeedlingsData[seedlingName].planted;
            }
            return 0;
        }

        // Get total scheduled for a specific seedling
        function getScheduledForSeedling(seedlingName) {
            return plantingSummaryRows
                .filter(row => row.seedling === seedlingName)
                .reduce((total, row) => total + row.quantity, 0);
        }

        // Get remaining available for a specific seedling after scheduling
        function getRemainingForSeedling(seedlingName) {
            const available = getAvailableForSeedling(seedlingName);
            const scheduled = getScheduledForSeedling(seedlingName);
            return Math.max(0, available - scheduled);
        }

        // Get available Hanga Dhaabbii for a specific seedling
        function getAvailableHangaDhaabbiiForSeedling(seedlingName) {
            if (availableSeedlingsData[seedlingName]) {
                return availableSeedlingsData[seedlingName].hanga_dhaabbii_available;
            }
            return 0;
        }

        // Get already used Hanga Dhaabbii for a specific seedling
        function getUsedHangaDhaabbiiForSeedling(seedlingName) {
            if (availableSeedlingsData[seedlingName]) {
                return availableSeedlingsData[seedlingName].hanga_dhaabbii_planted;
            }
            return 0;
        }

        // Get total scheduled Hanga Dhaabbii for a specific seedling
        function getScheduledHangaDhaabbiiForSeedling(seedlingName) {
            return plantingSummaryRows
                .filter(row => row.seedling === seedlingName)
                .reduce((total, row) => total + row.hanga_dhaabbii, 0);
        }

        // Get available Hanga Qophii for a specific seedling
        function getAvailableHangaQophiiForSeedling(seedlingName) {
            if (availableSeedlingsData[seedlingName]) {
                return availableSeedlingsData[seedlingName].hanga_qophii_available;
            }
            return 0;
        }

        // Get already used Hanga Qophii for a specific seedling
        function getUsedHangaQophiiForSeedling(seedlingName) {
            if (availableSeedlingsData[seedlingName]) {
                return availableSeedlingsData[seedlingName].hanga_qophii_planted;
            }
            return 0;
        }

        // Get total scheduled Hanga Qophii for a specific seedling
        function getScheduledHangaQophiiForSeedling(seedlingName) {
            return plantingSummaryRows
                .filter(row => row.seedling === seedlingName)
                .reduce((total, row) => total + row.hanga_qophii, 0);
        }

        // Update available seedlings data after scheduling
        function updateAvailableSeedlingsData() {
            Object.keys(availableSeedlingsData).forEach(seedlingName => {
                const available = getAvailableForSeedling(seedlingName);
                const scheduled = getScheduledForSeedling(seedlingName);
                availableSeedlingsData[seedlingName].remaining = Math.max(0, available - scheduled);
                
                const hangaDhaabbiiAvailable = getAvailableHangaDhaabbiiForSeedling(seedlingName);
                const hangaDhaabbiiScheduled = getScheduledHangaDhaabbiiForSeedling(seedlingName);
                availableSeedlingsData[seedlingName].hanga_dhaabbii_remaining = Math.max(0, hangaDhaabbiiAvailable - hangaDhaabbiiScheduled);
                
                const hangaQophiiAvailable = getAvailableHangaQophiiForSeedling(seedlingName);
                const hangaQophiiScheduled = getScheduledHangaQophiiForSeedling(seedlingName);
                availableSeedlingsData[seedlingName].hanga_qophii_remaining = Math.max(0, hangaQophiiAvailable - hangaQophiiScheduled);
            });
        }

        // Initialize form
        $(document).ready(function() {
            console.log('Document ready - initializing seedling planting scheduling for planting year:', plantingYear);
            console.log('Available seedlings data:', availableSeedlingsData);
            console.log('Total prepared:', totalPrepared);
            console.log('Already planted:', alreadyPlanted);
            console.log('Total Hanga Dhaabbii:', totalHangaDhaabbii);
            console.log('Total Hanga Qophii:', totalHangaQophii);

            // Update the planting year display
            $('#plantingYear').text(plantingYear);
            updatePlantingProgress();
            updateRemainingQuantity();

            // Form submission validation
            $('#seedlingPlanForm').on('submit', function(e) {
                console.log('Form submitted, validating...');
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert(translations.please_fix_errors, 'error');
                } else {
                    console.log('Form validation passed, submitting...');
                    savePlantingJSON();
                }
            });

            renderQuarters();
            loadExistingPlantingDistribution();
            updatePlantingSummary();
        });

        /* -------------------------------
           PLANTING QUANTITY CALCULATION
        ------------------------------- */
        function getCurrentScheduledQuantity() {
            return plantingSummaryRows.reduce((total, row) => total + row.quantity, 0);
        }

        function getCurrentScheduledHangaDhaabbii() {
            return plantingSummaryRows.reduce((total, row) => total + row.hanga_dhaabbii, 0);
        }

        function getCurrentScheduledHangaQophii() {
            return plantingSummaryRows.reduce((total, row) => total + row.hanga_qophii, 0);
        }

        function getTotalHangaDhaabbiiUsed() {
            let totalUsed = 0;
            Object.keys(availableSeedlingsData).forEach(seedlingName => {
                totalUsed += availableSeedlingsData[seedlingName].hanga_dhaabbii_planted;
            });
            return totalUsed + getCurrentScheduledHangaDhaabbii();
        }

        function getTotalHangaQophiiUsed() {
            let totalUsed = 0;
            Object.keys(availableSeedlingsData).forEach(seedlingName => {
                totalUsed += availableSeedlingsData[seedlingName].hanga_qophii_planted;
            });
            return totalUsed + getCurrentScheduledHangaQophii();
        }

        function getRemainingSeedlings() {
            return Math.max(0, totalPrepared - alreadyPlanted - getCurrentScheduledQuantity());
        }

        function updatePlantingProgress() {
            const scheduled = getCurrentScheduledQuantity();
            const total = totalPrepared - alreadyPlanted;
            const progress = total > 0 ? (scheduled / total * 100) : 0;
            $('#plantingProgress').text(progress.toFixed(1) + '%');
        }

        function updateRemainingQuantity() {
            const remaining = getRemainingSeedlings();
            $('#remainingQuantity').text(remaining.toFixed(2));
            
            // Update color based on remaining quantity
            if (remaining === 0) {
                $('#remainingQuantity').removeClass('text-info').addClass('text-danger');
            } else if (remaining < totalPrepared * 0.2) {
                $('#remainingQuantity').removeClass('text-info').addClass('text-warning');
            } else {
                $('#remainingQuantity').removeClass('text-danger text-warning').addClass('text-info');
            }
        }

        function updatePlantingSummary() {
            const scheduledHangaDhaabbii = getCurrentScheduledHangaDhaabbii();
            const scheduledHangaQophii = getCurrentScheduledHangaQophii();

            $("#scheduledHangaDhaabbiiDisplay").text(scheduledHangaDhaabbii.toFixed(2));
            $("#scheduledHangaQophiiDisplay").text(scheduledHangaQophii.toFixed(2));
        }

        function validatePlantingInput(input, type) {
            const value = parseFloat($(input).val()) || 0;
            const seedlingName = $(input).data('seedling-name');
            const feedback = $(input).parent().siblings('.validation-feedback');

            $(input).removeClass('is-valid is-invalid');
            feedback.empty();

            if (value < 0) {
                $(input).addClass('is-invalid');
                feedback.html('<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>' + translations.value_cannot_negative + '</small>');
                return false;
            }

            // For Hanga Dhaabbii validation
            if (type === 'hanga_dhaabbii') {
                const availableHangaDhaabbii = getAvailableHangaDhaabbiiForSeedling(seedlingName);
                
                // Calculate scheduled for this seedling excluding current row
                const scheduledWithoutCurrent = plantingSummaryRows
                    .filter(row => row.seedling === seedlingName && 
                                   !(row.month === $(input).data('month-name') && 
                                     row.week === translations.week + ' ' + $(input).data('week')))
                    .reduce((total, row) => total + row.hanga_dhaabbii, 0);
                
                const maxAllowed = Math.max(0, availableHangaDhaabbii + (parseFloat($(input).val()) || 0) - scheduledWithoutCurrent);

                if (value > maxAllowed) {
                    $(input).addClass('is-invalid');
                    feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_hanga_dhaabbii} (${maxAllowed.toFixed(2)})</small>`);
                    return false;
                }
            }

            // For Hanga Qophii validation
            if (type === 'hanga_qophii') {
                const availableHangaQophii = getAvailableHangaQophiiForSeedling(seedlingName);
                
                // Calculate scheduled for this seedling excluding current row
                const scheduledWithoutCurrent = plantingSummaryRows
                    .filter(row => row.seedling === seedlingName && 
                                   !(row.month === $(input).data('month-name') && 
                                     row.week === translations.week + ' ' + $(input).data('week')))
                    .reduce((total, row) => total + row.hanga_qophii, 0);
                
                const maxAllowed = Math.max(0, availableHangaQophii + (parseFloat($(this).val()) || 0) - scheduledWithoutCurrent);

                if (value > maxAllowed) {
                    $(input).addClass('is-invalid');
                    feedback.html(`<small class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${translations.exceeds_hanga_qophii} (${maxAllowed.toFixed(2)})</small>`);
                    return false;
                }
            }

            if (value > 0) {
                $(input).addClass('is-valid');
            }

            return true;
        }

        function validateAllPlantingSchedules() {
            let isValid = true;
            const errors = [];

            // Check Hanga Dhaabbii totals
            const totalScheduledHangaDhaabbii = getCurrentScheduledHangaDhaabbii();
            const totalUsedHangaDhaabbii = getTotalHangaDhaabbiiUsed();
            
            if (totalUsedHangaDhaabbii > totalHangaDhaabbii) {
                isValid = false;
                errors.push(`${translations.hanga_dhaabbii}: ${translations.scheduled_exceeds_available} (${totalUsedHangaDhaabbii.toFixed(2)}/${totalHangaDhaabbii.toFixed(2)})`);
            }

            // Check Hanga Qophii totals
            const totalScheduledHangaQophii = getCurrentScheduledHangaQophii();
            const totalUsedHangaQophii = getTotalHangaQophiiUsed();
            
            if (totalUsedHangaQophii > totalHangaQophii) {
                isValid = false;
                errors.push(`${translations.hanga_qophii}: ${translations.scheduled_exceeds_available} (${totalUsedHangaQophii.toFixed(2)}/${totalHangaQophii.toFixed(2)})`);
            }

            // Check individual seedling Hanga Dhaabbii limits
            Object.keys(availableSeedlingsData).forEach(seedlingName => {
                const availableHangaDhaabbii = getAvailableHangaDhaabbiiForSeedling(seedlingName);
                const scheduledHangaDhaabbii = getScheduledHangaDhaabbiiForSeedling(seedlingName);
                
                if (scheduledHangaDhaabbii > availableHangaDhaabbii) {
                    isValid = false;
                    errors.push(`${seedlingName} ${translations.hanga_dhaabbii}: ${translations.exceeds_available_hanga_dhaabbii} (${scheduledHangaDhaabbii.toFixed(2)}/${availableHangaDhaabbii.toFixed(2)})`);
                }
            });

            // Check individual seedling Hanga Qophii limits
            Object.keys(availableSeedlingsData).forEach(seedlingName => {
                const availableHangaQophii = getAvailableHangaQophiiForSeedling(seedlingName);
                const scheduledHangaQophii = getScheduledHangaQophiiForSeedling(seedlingName);
                
                if (scheduledHangaQophii > availableHangaQophii) {
                    isValid = false;
                    errors.push(`${seedlingName} ${translations.hanga_qophii}: ${translations.exceeds_available_hanga_qophii} (${scheduledHangaQophii.toFixed(2)}/${availableHangaQophii.toFixed(2)})`);
                }
            });

            // Check individual inputs
            $('.hangadhaabbii-input, .hangaqophii-input').each(function() {
                const type = $(this).hasClass('hangadhaabbii-input') ? 'hanga_dhaabbii' : 'hanga_qophii';
                if (!validatePlantingInput(this, type)) {
                    isValid = false;
                }
            });

            return {
                isValid,
                errors
            };
        }

        /* -------------------------------
           QUARTERS MANAGEMENT
        ------------------------------- */
        function renderQuarters() {
            $("#quartersContainer").empty();

            Object.keys(EC_QUARTERS).forEach(q => {
                const quarterData = calculateQuarterData(parseInt(q));
                const isSelected = selectedQuarters.has(parseInt(q));
                const quarterName = `Q${q}`;
                const monthNames = EC_QUARTERS[q].map(m => EC_MONTHS[m-1].name).join(", ");

                $("#quartersContainer").append(`
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card card-outline ${isSelected ? 'card-primary card-selected' : 'card-secondary'} h-100 quarter-card" data-quarter="${q}">
                        <div class="card-header">
                            <h3 class="card-title">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input quarter-checkbox" type="checkbox"
                                           id="quarter${q}" ${isSelected ? 'checked' : ''}>
                                    <label for="quarter${q}" class="custom-control-label font-weight-bold">
                                        ${quarterName}
                                    </label>
                                </div>
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                <small>${monthNames}</small>
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-success">
                                        <i class="fas fa-users mr-1"></i>{{ __('Planned') }}: ${quarterData.hanga_dhaabbii.toFixed(2)} {{ __('Hanga Dhaabbii') }}
                                    </small>
                                </div>
                                <div class="col-12">
                                    <small class="text-info">
                                        <i class="fas fa-calendar mr-1"></i>{{ __('Year') }}: ${plantingYear}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

                // Add event listener to quarter card
                $(`#quarter${q}`).on('change', function() {
                    handleQuarterChange(parseInt(q), $(this).is(':checked'));
                });
            });

            // Add click event to whole quarter card
            $('.quarter-card').click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.quarter-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });
        }

        function handleQuarterChange(quarterNum, isChecked) {
            if (isChecked) {
                selectedQuarters.add(quarterNum);
                $(`.quarter-card[data-quarter="${quarterNum}"]`).removeClass('card-secondary').addClass('card-primary card-selected');
            } else {
                selectedQuarters.delete(quarterNum);
                $(`.quarter-card[data-quarter="${quarterNum}"]`).removeClass('card-primary card-selected').addClass('card-secondary');
                // Remove months from this quarter from selected months
                const quarterMonths = EC_QUARTERS[quarterNum];
                quarterMonths.forEach(month => {
                    selectedMonths.delete(month);
                });
            }

            renderMonthsTable();
            updatePlantingProgress();
        }

        function calculateQuarterData(quarter) {
            const quarterName = `Q${quarter}`;
            const quarterSchedules = plantingSummaryRows.filter(row => row.quarter === quarterName);
            
            return {
                quantity: quarterSchedules.reduce((total, row) => total + (row.quantity || 0), 0),
                hanga_dhaabbii: quarterSchedules.reduce((total, row) => total + (row.hanga_dhaabbii || 0), 0),
                hanga_qophii: quarterSchedules.reduce((total, row) => total + (row.hanga_qophii || 0), 0)
            };
        }

        function selectAllQuarters() {
            Object.keys(EC_QUARTERS).forEach(q => {
                if (!selectedQuarters.has(parseInt(q))) {
                    selectedQuarters.add(parseInt(q));
                }
            });
            renderQuarters();
            renderMonthsTable();
            showAlert(translations.all_quarters_selected + ' ' + plantingYear, 'success');
        }

        function clearAllQuarters() {
            selectedQuarters.clear();
            selectedMonths.clear();
            renderQuarters();
            $('#monthsSection').hide();
            $('#weeksSection').hide();
            showAlert(translations.all_quarters_cleared, 'info');
        }

        /* -------------------------------
           MONTHS TABLE MANAGEMENT
        ------------------------------- */
        function renderMonthsTable() {
            $("#monthsTable").empty();

            if (selectedQuarters.size === 0) {
                $("#monthsSection").hide();
                $("#weeksSection").hide();
                return;
            }

            $("#monthsSection").show();

            // Get all months from selected quarters
            let allMonths = [];
            selectedQuarters.forEach(quarter => {
                allMonths = allMonths.concat(EC_QUARTERS[quarter]);
            });

            // Remove duplicates and sort
            allMonths = [...new Set(allMonths)].sort((a, b) => a - b);

            if (allMonths.length === 0) {
                $("#monthsTable").append(`
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            ${translations.no_months_available}
                        </td>
                    </tr>
                `);
                return;
            }

            allMonths.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const monthData = calculateMonthData(monthNum);
                const isSelected = selectedMonths.has(monthNum);
                const quarterNum = findQuarter(monthNum);
                const status = monthData.hanga_dhaabbii > 0 ? translations.partially_scheduled : translations.not_scheduled;
                const statusClass = monthData.hanga_dhaabbii > 0 ? 'badge-warning' : 'badge-secondary';

                $("#monthsTable").append(`
                    <tr class="${isSelected ? 'table-info' : ''} month-row" data-month="${monthNum}">
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input month-checkbox" type="checkbox"
                                       id="month${monthNum}" ${isSelected ? 'checked' : ''}>
                                <label for="month${monthNum}" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <strong>${month.name}</strong>
                        </td>
                        <td>
                            <span class="badge badge-primary">Q${quarterNum}</span>
                        </td>
                        <td>${plantingYear}</td>
                        <td>
                            <span class="text-info font-weight-bold">${monthData.hanga_dhaabbii.toFixed(2)} {{ __('Hanga Dhaabbii') }}</span>
                        </td>
                        <td>
                            <span class="badge ${statusClass}">${status}</span>
                        </td>
                    </tr>
                `);

                // Add event listener to month checkbox
                $(`#month${monthNum}`).on('change', function() {
                    handleMonthChange(monthNum, $(this).is(':checked'));
                });
            });

            // Add select all functionality
            $('#selectAllMonthsCheckbox').off('change').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.month-checkbox').prop('checked', isChecked).trigger('change');
            });

            // Add click event to month rows
            $('.month-row').click(function(e) {
                if (!$(e.target).is('input') && !$(e.target).is('label')) {
                    const checkbox = $(this).find('.month-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });

            // Update select all checkbox state
            updateSelectAllMonthsCheckbox();
        }

        function updateSelectAllMonthsCheckbox() {
            const totalMonths = $('.month-checkbox').length;
            const checkedMonths = $('.month-checkbox:checked').length;
            const selectAllCheckbox = $('#selectAllMonthsCheckbox');
            
            selectAllCheckbox.prop('checked', totalMonths > 0 && totalMonths === checkedMonths);
            selectAllCheckbox.prop('indeterminate', checkedMonths > 0 && checkedMonths < totalMonths);
        }

        function handleMonthChange(monthNum, isChecked) {
            if (isChecked) {
                selectedMonths.add(monthNum);
                $(`.month-row[data-month="${monthNum}"]`).addClass('table-info');
            } else {
                selectedMonths.delete(monthNum);
                $(`.month-row[data-month="${monthNum}"]`).removeClass('table-info');
            }

            updateSelectAllMonthsCheckbox();
            renderWeeksTable();
        }

        function calculateMonthData(monthNum) {
            const monthName = EC_MONTHS[monthNum - 1].name;
            const monthSchedules = plantingSummaryRows.filter(row => row.month === monthName);
            
            return {
                quantity: monthSchedules.reduce((total, row) => total + (row.quantity || 0), 0),
                hanga_dhaabbii: monthSchedules.reduce((total, row) => total + (row.hanga_dhaabbii || 0), 0),
                hanga_qophii: monthSchedules.reduce((total, row) => total + (row.hanga_qophii || 0), 0)
            };
        }

        function selectAllMonths() {
            if (selectedQuarters.size === 0) {
                showAlert(translations.please_select_quarters_first, 'warning');
                return;
            }

            // Get all months from selected quarters
            let allMonths = [];
            selectedQuarters.forEach(quarter => {
                allMonths = allMonths.concat(EC_QUARTERS[quarter]);
            });

            allMonths.forEach(month => {
                if (!selectedMonths.has(month)) {
                    selectedMonths.add(month);
                }
            });

            renderMonthsTable();
            renderWeeksTable();
            showAlert(translations.all_months_selected + ' ' + plantingYear, 'success');
        }

        function clearAllMonths() {
            selectedMonths.clear();
            renderMonthsTable();
            $('#weeksSection').hide();
            showAlert(translations.all_months_cleared, 'info');
        }

        /* -------------------------------
           WEEKS TABLE MANAGEMENT
        ------------------------------- */
        function renderWeeksTable() {
            $("#weeksTable").empty();

            if (selectedMonths.size === 0) {
                $("#weeksSection").hide();
                return;
            }

            $("#weeksSection").show();

            // Group weeks by month for better organization
            const monthsArray = Array.from(selectedMonths).sort((a, b) => a - b);
            let totalHangaDhaabbii = 0;
            let totalHangaQophii = 0;

            monthsArray.forEach(monthNum => {
                const month = EC_MONTHS[monthNum - 1];
                const quarterNum = findQuarter(monthNum);
                const monthData = calculateMonthData(monthNum);

                // Calculate weeks for this month
                let weeks = [];
                let start = 1;
                while (start <= month.days) {
                    let end = Math.min(start + 6, month.days);
                    weeks.push({ start, end, weekNum: weeks.length + 1 });
                    start += 7;
                }

                weeks.forEach(week => {
                    // Create rows for each seedling type
                    Object.keys(availableSeedlingsData).forEach(seedlingName => {
                        const seedling = availableSeedlingsData[seedlingName];
                        const existingSchedule = plantingSummaryRows.find(row =>
                            row.month === month.name && 
                            row.week === translations.week + ' ' + week.weekNum && 
                            row.seedling === seedlingName
                        );
                        
                        const hangaDhaabbiiValue = existingSchedule ? existingSchedule.hanga_dhaabbii : 0;
                        const hangaQophiiValue = existingSchedule ? existingSchedule.hanga_qophii : 0;
                        
                        // Calculate available for this seedling
                        const availableForSeedling = getRemainingForSeedling(seedlingName);
                        const availableHangaDhaabbii = getAvailableHangaDhaabbiiForSeedling(seedlingName) - getScheduledHangaDhaabbiiForSeedling(seedlingName);
                        const availableHangaQophii = getAvailableHangaQophiiForSeedling(seedlingName) - getScheduledHangaQophiiForSeedling(seedlingName);
                        
                        totalHangaDhaabbii += hangaDhaabbiiValue;
                        totalHangaQophii += hangaQophiiValue;

                        $("#weeksTable").append(`
                            <tr class="week-row" data-month="${monthNum}" data-week="${week.weekNum}" data-seedling="${seedlingName}">
                                <td>
                                    <strong>${translations.week} ${week.weekNum}</strong>
                                </td>
                                <td>${month.name}</td>
                                <td>
                                    <span class="badge badge-primary">Q${quarterNum}</span>
                                </td>
                                <td>
                                    <small class="text-muted">${translations.days} ${week.start}-${week.end}</small>
                                </td>
                                <td>${plantingYear}</td>
                                <td>
                                    <small class="fw-bold">${seedlingName}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="text-success mb-1">
                                            <i class="fas fa-tree mr-1"></i> ${availableForSeedling.toFixed(2)}
                                        </small>
                                        <small class="text-info mb-1">
                                            <i class="fas fa-users mr-1"></i> ${Math.max(0, availableHangaDhaabbii).toFixed(2)}
                                        </small>
                                        <small class="text-warning">
                                            <i class="fas fa-calendar-day mr-1"></i> ${Math.max(0, availableHangaQophii).toFixed(2)}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0" max="${availableHangaDhaabbii}"
                                               class="form-control form-control-sm hangadhaabbii-input"
                                               data-month="${monthNum}"
                                               data-week="${week.weekNum}"
                                               data-month-name="${month.name}"
                                               data-seedling-name="${seedlingName}"
                                               value="${hangaDhaabbiiValue.toFixed(2)}"
                                               placeholder="0.00">
                                    </div>
                                    <div class="validation-feedback"></div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0" max="${availableHangaQophii}"
                                               class="form-control form-control-sm hangaqophii-input"
                                               data-month="${monthNum}"
                                               data-week="${week.weekNum}"
                                               data-month-name="${month.name}"
                                               data-seedling-name="${seedlingName}"
                                               value="${hangaQophiiValue.toFixed(2)}"
                                               placeholder="0.00">
                                    </div>
                                    <div class="validation-feedback"></div>
                                </td>
                            </tr>
                        `);
                    });
                });
            });

            // Add input event listeners
            enhancePlantingInputValidation();
            
            // Update footer totals
            $('#weeksTotalHangaDhaabbii').text(totalHangaDhaabbii.toFixed(2));
            $('#weeksTotalHangaQophii').text(totalHangaQophii.toFixed(2));
            $('#totalScheduledBadge').text(totalHangaDhaabbii.toFixed(2) + ' {{ __('Hanga Dhaabbii') }}');
        }

        /* -------------------------------
           INPUT VALIDATION
        ------------------------------- */
        function updatePlantingScheduling(monthName, weekNumber, seedlingName, type, value) {
            const monthNum = EC_MONTHS.findIndex(m => m.name === monthName) + 1;
            const quarter = `Q${findQuarter(monthNum)}`;
            
            // Find or create schedule
            let schedule = plantingSummaryRows.find(row =>
                row.month === monthName && 
                row.week === translations.week + ' ' + weekNumber && 
                row.seedling === seedlingName
            );

            if (schedule) {
                schedule[type] = value;
                
                // Auto-calculate seedlings based on Hanga Dhaabbii ratio
                if (type === 'hanga_dhaabbii' && value > 0) {
                    const seedlingData = availableSeedlingsData[seedlingName];
                    if (seedlingData && seedlingData.hanga_dhaabbii_prepared > 0 && seedlingData.prepared > 0) {
                        const ratio = seedlingData.prepared / seedlingData.hanga_dhaabbii_prepared;
                        schedule.quantity = value * ratio;
                    }
                }
            } else {
                const weekData = getWeekData(monthNum, weekNumber);
                schedule = {
                    year: plantingYear,
                    quarter: quarter,
                    month: monthName,
                    week: translations.week + ' ' + weekNumber,
                    range: weekData.range,
                    seedling: seedlingName,
                    quantity: 0,
                    hanga_dhaabbii: 0,
                    hanga_qophii: 0,
                    available: getAvailableForSeedling(seedlingName),
                    already_planted: getAlreadyPlantedForSeedling(seedlingName)
                };
                schedule[type] = value;
                
                // Auto-calculate seedlings based on Hanga Dhaabbii ratio
                if (type === 'hanga_dhaabbii' && value > 0) {
                    const seedlingData = availableSeedlingsData[seedlingName];
                    if (seedlingData && seedlingData.hanga_dhaabbii_prepared > 0 && seedlingData.prepared > 0) {
                        const ratio = seedlingData.prepared / seedlingData.hanga_dhaabbii_prepared;
                        schedule.quantity = value * ratio;
                    }
                }
                
                plantingSummaryRows.push(schedule);
            }

            // Remove if all values are 0
            if (schedule.hanga_dhaabbii === 0 && schedule.hanga_qophii === 0) {
                plantingSummaryRows = plantingSummaryRows.filter(row =>
                    !(row.month === monthName && row.week === translations.week + ' ' + weekNumber && row.seedling === seedlingName)
                );
            }

            // Update available seedlings data
            updateAvailableSeedlingsData();
            
            // Update UI
            updateSummaryTable();
            updatePlantingSummary();
            updatePlantingProgress();
            updateRemainingQuantity();
            updateMonthTableData(monthNum);
            updateWeeksTableFooter();
        }

        function updateMonthTableData(monthNum) {
            const monthData = calculateMonthData(monthNum);
            const monthName = EC_MONTHS[monthNum - 1].name;
            
            const monthRow = $(`.month-row[data-month="${monthNum}"]`);
            monthRow.find('td:nth-child(5)').html(`<span class="text-info font-weight-bold">${monthData.hanga_dhaabbii.toFixed(2)} {{ __('Hanga Dhaabbii') }}</span>`);
            
            const status = monthData.hanga_dhaabbii > 0 ? translations.partially_scheduled : translations.not_scheduled;
            const statusClass = monthData.hanga_dhaabbii > 0 ? 'badge-warning' : 'badge-secondary';
            monthRow.find('td:nth-child(6)').html(`<span class="badge ${statusClass}">${status}</span>`);
        }

        function enhancePlantingInputValidation() {
            $('.hangadhaabbii-input, .hangaqophii-input').off('input').on('input', function() {
                const type = $(this).hasClass('hangadhaabbii-input') ? 'hanga_dhaabbii' : 'hanga_qophii';
                if (validatePlantingInput(this, type)) {
                    const monthNum = parseInt($(this).data('month'));
                    const weekNumber = parseInt($(this).data('week'));
                    const monthName = $(this).data('month-name');
                    const seedlingName = $(this).data('seedling-name');
                    const value = parseFloat($(this).val()) || 0;
                    
                    updatePlantingScheduling(monthName, weekNumber, seedlingName, type, value);
                }
            });
        }

        function updateWeeksTableFooter() {
            let totalHangaDhaabbii = 0;
            let totalHangaQophii = 0;
            
            $('.hangadhaabbii-input').each(function() {
                const value = parseFloat($(this).val()) || 0;
                totalHangaDhaabbii += value;
            });
            
            $('.hangaqophii-input').each(function() {
                const value = parseFloat($(this).val()) || 0;
                totalHangaQophii += value;
            });
            
            $('#weeksTotalHangaDhaabbii').text(totalHangaDhaabbii.toFixed(2));
            $('#weeksTotalHangaQophii').text(totalHangaQophii.toFixed(2));
            $('#totalScheduledBadge').text(totalHangaDhaabbii.toFixed(2) + ' {{ __('Hanga Dhaabbii') }}');
        }

        function saveAllSchedules() {
            let hasErrors = false;

            // Validate all inputs first
            $('.hangadhaabbii-input, .hangaqophii-input').each(function() {
                const type = $(this).hasClass('hangadhaabbii-input') ? 'hanga_dhaabbii' : 'hanga_qophii';
                if (!validatePlantingInput(this, type)) hasErrors = true;
            });

            if (hasErrors) {
                showAlert(translations.fix_validation_errors, 'error');
                return;
            }

            // Update all schedules from inputs
            $('.hangadhaabbii-input, .hangaqophii-input').each(function() {
                const monthNum = parseInt($(this).data('month'));
                const weekNumber = parseInt($(this).data('week'));
                const monthName = $(this).data('month-name');
                const seedlingName = $(this).data('seedling-name');
                const value = parseFloat($(this).val()) || 0;
                const type = $(this).hasClass('hangadhaabbii-input') ? 'hanga_dhaabbii' : 'hanga_qophii';

                updatePlantingScheduling(monthName, weekNumber, seedlingName, type, value);
            });

            savePlantingJSON();
            updateSummaryTable();
            updatePlantingSummary();
            updatePlantingProgress();
            updateRemainingQuantity();
            
            // Check if all available resources are distributed
            checkDistributionCompletion();
            
            showAlert(translations.all_schedules_saved + ' ' + plantingYear + '!', 'success');
        }

        function checkDistributionCompletion() {
            let remainingHangaDhaabbii = totalHangaDhaabbii - getTotalHangaDhaabbiiUsed();
            let remainingHangaQophii = totalHangaQophii - getTotalHangaQophiiUsed();
            
            if (remainingHangaDhaabbii <= 0 && remainingHangaQophii <= 0) {
                showAlert('{{ __("All available Hanga Dhaabbii and Hanga Qophii have been distributed!") }}', 'success');
            } else {
                showAlert(`{{ __("Remaining:") }} ${remainingHangaDhaabbii.toFixed(2)} {{ __("Hanga Dhaabbii") }}, ${remainingHangaQophii.toFixed(2)} {{ __("Hanga Qophii") }}`, 'info');
            }
        }

        /* -------------------------------
           SUMMARY & DATA MANAGEMENT
        ------------------------------- */
        function updateSummaryTable() {
            $("#summaryTable").empty();
            let totalHangaDhaabbii = 0;
            let totalHangaQophii = 0;

            if (plantingSummaryRows.length === 0) {
                $("#summaryTable").append(`
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="fas fa-tree mr-2"></i>
                        {{ __('No planting scheduled yet. Start by selecting quarters above.') }}
                    </td>
                </tr>
            `);
            } else {
                plantingSummaryRows.forEach((row, i) => {
                    totalHangaDhaabbii += row.hanga_dhaabbii;
                    totalHangaQophii += row.hanga_qophii;
                    const availableForSeedling = getAvailableForSeedling(row.seedling);

                    $("#summaryTable").append(`
                    <tr>
                        <td>${row.year || plantingYear}</td>
                        <td>${row.seedling}</td>
                        <td>${row.quarter}</td>
                        <td>${row.month}</td>
                        <td>${row.week}</td>
                        <td>${row.range}</td>
                        <td class="font-weight-bold text-info">${availableForSeedling.toFixed(2)}</td>
                        <td class="font-weight-bold text-primary">${row.hanga_dhaabbii.toFixed(2)}</td>
                        <td class="font-weight-bold text-warning">${row.hanga_qophii.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="removePlantingRow(${i})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
                });
            }

            $("#summaryTotalHangaDhaabbii").text(totalHangaDhaabbii.toFixed(2));
            $("#summaryTotalHangaQophii").text(totalHangaQophii.toFixed(2));
        }

        function removePlantingRow(index) {
            const removedRow = plantingSummaryRows[index];
            plantingSummaryRows.splice(index, 1);
            
            // Update available seedlings data
            updateAvailableSeedlingsData();
            
            updateSummaryTable();
            savePlantingJSON();
            updatePlantingSummary();
            updatePlantingProgress();
            updateRemainingQuantity();
            renderWeeksTable();
            showAlert(translations.schedule_removed, 'info');
        }

        function savePlantingJSON() {
            // Group by seedling
            const seedlingGroups = {};
            plantingSummaryRows.forEach(row => {
                if (!seedlingGroups[row.seedling]) {
                    seedlingGroups[row.seedling] = [];
                }
                seedlingGroups[row.seedling].push({
                    year: row.year,
                    quarter: row.quarter,
                    month: row.month,
                    week: row.week,
                    range: row.range,
                    quantity: row.quantity,
                    nama_hirmaate_dhaabbii: row.hanga_dhaabbii,
                    baayina_guyyaa_dhaabbii: row.hanga_qophii,
                    available: getAvailableForSeedling(row.seedling),
                    already_planted: getAlreadyPlantedForSeedling(row.seedling)
                });
            });

            const plantingData = {
                seedling_type: {
                    id: {{ $seedlingPreparation->preparation_data['seedling_type']['id'] ?? 0 }},
                    name: '{{ $seedlingPreparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}'
                },
                selected_seedlings: Object.keys(seedlingGroups).map(seedlingName => ({
                    name: seedlingName,
                    weekly_planting: seedlingGroups[seedlingName],
                    prepared: availableSeedlingsData[seedlingName]?.prepared || 0,
                    already_planted: availableSeedlingsData[seedlingName]?.planted || 0,
                    available: availableSeedlingsData[seedlingName]?.available || 0,
                    hanga_dhaabbii_prepared: availableSeedlingsData[seedlingName]?.hanga_dhaabbii_prepared || 0,
                    hanga_dhaabbii_used: availableSeedlingsData[seedlingName]?.hanga_dhaabbii_planted || 0,
                    hanga_dhaabbii_available: getAvailableHangaDhaabbiiForSeedling(seedlingName),
                    hanga_qophii_prepared: availableSeedlingsData[seedlingName]?.hanga_qophii_prepared || 0,
                    hanga_qophii_used: availableSeedlingsData[seedlingName]?.hanga_qophii_planted || 0,
                    hanga_qophii_available: getAvailableHangaQophiiForSeedling(seedlingName)
                })),
                total_planned: getCurrentScheduledQuantity(),
                total_hanga_dhaabbii: getCurrentScheduledHangaDhaabbii(),
                total_hanga_qophii: getCurrentScheduledHangaQophii(),
                total_prepared: totalPrepared,
                already_planted: alreadyPlanted,
                remaining_seedlings: getRemainingSeedlings(),
                total_hanga_dhaabbii_prepared: totalHangaDhaabbii,
                total_hanga_qophii_prepared: totalHangaQophii,
                total_hanga_dhaabbii_used: getTotalHangaDhaabbiiUsed(),
                total_hanga_qophii_used: getTotalHangaQophiiUsed(),
                remaining_hanga_dhaabbii: Math.max(0, totalHangaDhaabbii - getTotalHangaDhaabbiiUsed()),
                remaining_hanga_qophii: Math.max(0, totalHangaQophii - getTotalHangaQophiiUsed()),
                updated_at: new Date().toISOString()
            };

            $("#plantingData").val(JSON.stringify(plantingData));
            console.log('Saved plantingdata_detail for planting year ' + plantingYear + ':', plantingData);
        }

        function loadExistingPlantingDistribution() {
            let existing = {!! json_encode($seedlingPreparation->plantingdata_detail ?? '[]') !!};
            console.log('Loading existing planting distribution:', existing);

            if (existing && typeof existing === 'object') {
                if (existing.selected_seedlings && Array.isArray(existing.selected_seedlings)) {
                    plantingSummaryRows = [];
                    existing.selected_seedlings.forEach(seedling => {
                        if (seedling.weekly_planting && Array.isArray(seedling.weekly_planting)) {
                            seedling.weekly_planting.forEach(planting => {
                                if (planting.nama_hirmaate_dhaabbii > 0 || planting.baayina_guyyaa_dhaabbii > 0) {
                                    plantingSummaryRows.push({
                                        year: planting.year || plantingYear,
                                        quarter: planting.quarter,
                                        month: planting.month,
                                        week: planting.week,
                                        range: planting.range || '',
                                        seedling: seedling.name,
                                        quantity: parseFloat(planting.quantity) || 0,
                                        hanga_dhaabbii: parseFloat(planting.nama_hirmaate_dhaabbii) || 0,
                                        hanga_qophii: parseFloat(planting.baayina_guyyaa_dhaabbii) || 0,
                                        available: seedling.available || getAvailableForSeedling(seedling.name),
                                        already_planted: seedling.already_planted || getAlreadyPlantedForSeedling(seedling.name)
                                    });
                                }
                            });
                        }
                    });
                }

                // Restore selections
                plantingSummaryRows.forEach(row => {
                    const quarterNum = parseInt(row.quarter.replace('Q', ''));
                    selectedQuarters.add(quarterNum);

                    const monthNum = EC_MONTHS.findIndex(m => m.name === row.month) + 1;
                    if (monthNum > 0) selectedMonths.add(monthNum);
                });

                updateAvailableSeedlingsData();
                updateSummaryTable();
                updatePlantingSummary();
                updatePlantingProgress();
                updateRemainingQuantity();

                if (selectedQuarters.size > 0) {
                    $("#monthsSection").show();
                    renderMonthsTable();
                }

                if (selectedMonths.size > 0) {
                    $("#weeksSection").show();
                    renderWeeksTable();
                }
            }
        }

        /* -------------------------------
           UTILITY FUNCTIONS
        ------------------------------- */
        function findQuarter(monthId) {
            return Object.keys(EC_QUARTERS).find(q => EC_QUARTERS[q].includes(monthId));
        }

        function getWeekData(monthNum, weekNumber) {
            const month = EC_MONTHS[monthNum - 1];
            let start = 1 + (weekNumber - 1) * 7;
            let end = Math.min(start + 6, month.days);
            return { range: `${start}-${end}` };
        }

        function validateForm() {
            let isValid = true;
            const errors = [];

            // Enhanced distribution validation
            const scheduledHangaDhaabbii = getCurrentScheduledHangaDhaabbii();
            const scheduledHangaQophii = getCurrentScheduledHangaQophii();

            if (scheduledHangaDhaabbii === 0 && scheduledHangaQophii === 0) {
                const proceed = confirm(translations.no_schedule_warning + ' ' + plantingYear + '?');
                if (!proceed) {
                    errors.push(translations.please_add_schedule);
                    isValid = false;
                }
            }

            // Validate planting data
            const plantingValidation = validateAllPlantingSchedules();
            if (!plantingValidation.isValid) {
                errors.push(...plantingValidation.errors);
                isValid = false;
            }

            // Check for individual input errors
            $('.hangadhaabbii-input, .hangaqophii-input').each(function() {
                if ($(this).hasClass('is-invalid')) {
                    errors.push('{{ __('Some planting schedules have validation errors') }}');
                    isValid = false;
                    return false; // break the loop
                }
            });

            // Show errors
            if (errors.length > 0) {
                showValidationErrors(errors);
            }

            return isValid;
        }

        function showValidationErrors(errors) {
            // Remove existing error alerts
            $('.validation-error-alert').remove();

            const errorHtml = `
            <div class="row mb-3 validation-error-alert">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> {{ __('Validation Error!') }}</h5>
                        <ul class="mb-0">
                            ${errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        `;

            $('.content-header').after(errorHtml);
        }

        function showAlert(message, type = 'info') {
            const toast = $(`
                <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'success'} border-0 position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 9999;">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" style="opacity: 1;"></button>
                    </div>
                </div>
            `);

            $('body').append(toast);
            
            // Simple show/hide instead of Bootstrap toast
            toast.fadeIn();
            setTimeout(() => {
                toast.fadeOut(400, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>

    <style>
        /* Card selection styles */
        .card-selected {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25) !important;
        }

        /* Info box hover effects */
        .info-box:hover {
            transform: translateY(-2px);
            transition: transform 0.3s ease;
        }

        /* Card hover effects */
        .card-outline:hover:not(.card-selected) {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Quarter and month card hover */
        .quarter-card, .month-row {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quarter-card:hover:not(.card-selected) {
            border-color: #007bff !important;
        }

        .month-row:hover {
            background-color: rgba(0, 123, 255, 0.05) !important;
        }

        /* Custom checkbox styling */
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #007bff;
            background-color: #007bff;
        }

        /* Week table row styling */
        .week-row input.form-control-sm {
            min-width: 80px;
        }

        /* Validation feedback */
        .validation-feedback {
            min-height: 20px;
            font-size: 0.75rem;
        }

        /* Toast positioning */
        .toast {
            display: none;
            min-width: 300px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-title {
                font-size: 0.9rem;
            }
            
            .info-box-number {
                font-size: 1.2rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .week-row td {
                padding: 0.5rem !important;
            }
            
            .hangadhaabbii-input,
            .hangaqophii-input {
                font-size: 0.8rem !important;
            }
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-info {
            background-color: rgba(0, 123, 255, 0.15) !important;
        }

        /* Input validation states */
        .hangadhaabbii-input.is-valid,
        .hangaqophii-input.is-valid {
            border-color: #28a745;
        }

        .hangadhaabbii-input.is-invalid,
        .hangaqophii-input.is-invalid {
            border-color: #dc3545;
        }

        /* Table Header Styles - WHITESMOKE with BLACK TEXT */
        table.table thead {
            background-color: whitesmoke !important;
        }
        
        table.table thead th,
        .table thead th {
            background-color: whitesmoke !important;
            border-color: #dee2e6 !important;
            font-weight: 600;
            color: #000000 !important;
            padding: 12px 8px;
        }
        
        table.table thead.thead-light {
            background-color: whitesmoke !important;
        }
        
        table.table thead.thead-light th,
        .table thead.thead-light th {
            background-color: whitesmoke !important;
            border-color: #dee2e6;
            color: #000000 !important;
        }
        
        table.table thead.thead-dark {
            background-color: #343a40 !important;
        }
        
        table.table thead.thead-dark th,
        .table thead.thead-dark th {
            background-color: #343a40 !important;
            border-color: #454d55;
            color: white !important;
        }
        
        /* EXCLUDE card headers from table header styles */
        .card-header,
        .card-header h3,
        .card-header .card-title,
        .card-header .card-tools,
        .card-header .badge {
            color: inherit !important;
            background-color: transparent !important;
            border-color: transparent !important;
        }
        
        /* Table footer styling */
        table.table tfoot.bg-gray,
        .table tfoot.bg-gray {
            background-color: #f8f9fa !important;
        }
        
        table.table tfoot.bg-light,
        .table tfoot.bg-light {
            background-color: #f8f9fa !important;
        }
        
        /* Zebra striping for better readability */
        table.table.table-striped tbody tr:nth-of-type(odd),
        .table.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        table.table.table-striped tbody tr:nth-of-type(even),
        .table.table-striped tbody tr:nth-of-type(even) {
            background-color: white;
        }
        
        /* Selected row highlighting */
        table.table tbody tr.month-row.table-info,
        .table tbody tr.month-row.table-info {
            background-color: rgba(23, 162, 184, 0.15) !important;
        }
        
        /* Card header backgrounds */
        .card-header {
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            color: #495057 !important;
        }
        
        /* Hover effects on table rows */
        table.table.table-hover tbody tr:hover,
        .table.table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        /* Reset card titles to their original colors */
        .card-title {
            color: #495057 !important;
        }
        
        .card-success .card-header .card-title {
            color: white !important;
        }
        
        .card-primary .card-header .card-title {
            color: white !important;
        }
        
        .card-info .card-header .card-title {
            color: white !important;
        }
        
        .card-outline .card-header .card-title {
            color: #495057 !important;
        }
        
        /* Seedling card specific styles */
        .card.bg-light .card-body {
            padding: 1rem;
        }
        
        .card.bg-light .card-title {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        /* Seedling stats styling */
        .seedling-stats {
            border-top: 1px solid #eee;
            padding-top: 0.5rem;
        }
        
        .seedling-resources {
            border-top: 1px solid #eee;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }
        
        /* Progress bar styling */
        .progress {
            border-radius: 2px;
        }
        
        /* Week table availability styling */
        .week-row td:nth-child(7) {
            background-color: #f8fff8;
        }
        
        /* Color coding for availability */
        .text-available {
            color: #28a745;
        }
        
        .text-planned {
            color: #007bff;
        }
        
        .text-planted {
            color: #ffc107;
        }
        
        /* Resource input styling */
        .input-group-sm {
            max-width: 120px;
        }
        
        /* Auto-calc indicator */
        .auto-calc-note {
            font-size: 0.75rem;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</x-layout>