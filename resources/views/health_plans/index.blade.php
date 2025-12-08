<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Health Plans</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('sector.health_plans.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Health Plan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Health Plans Overview</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th>Year</th>
                                                <th>Activities Count</th>
                                                <th>Type Summary</th>
                                                <th>Total Budget</th>
                                                <th>Status</th>
                                                <th width="15%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Group plans by year
                                                $groupedPlans = $plans->groupBy('year')->sortDesc();
                                            @endphp
                                            
                                            @foreach($groupedPlans as $year => $yearPlans)
                                                @php
                                                    $yearTotalBudget = 0;
                                                    $yearActivitiesCount = 0;
                                                    $typeSummary = [];
                                                    $yearStatus = 'active';
                                                @endphp
                                                
                                                @foreach($yearPlans as $plan)
                                                    @php
                                                        // Process each plan's activities
                                                        foreach($plan->health_activity_name as $activity) {
                                                            $yearTotalBudget += $activity['budget'] ?? 0;
                                                            $yearActivitiesCount++;
                                                            
                                                            $type = $activity['type'] ?? 'Uncategorized';
                                                            if(!isset($typeSummary[$type])) {
                                                                $typeSummary[$type] = [
                                                                    'count' => 0,
                                                                    'budget' => 0
                                                                ];
                                                            }
                                                            $typeSummary[$type]['count']++;
                                                            $typeSummary[$type]['budget'] += $activity['budget'] ?? 0;
                                                        }
                                                        
                                                        // Check status
                                                        if($plan->status !== 'active') {
                                                            $yearStatus = $plan->status;
                                                        }
                                                    @endphp
                                                @endforeach
                                                
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        <i class="fas fa-calendar-alt text-primary mr-2"></i>{{ $year }} EC
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $yearActivitiesCount }}</span> activities
                                                    </td>
                                                    <td>
                                                        <div class="type-summary">
                                                            @foreach($typeSummary as $type => $data)
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span class="text-muted">{{ $type }}:</span>
                                                                    <span class="font-weight-bold">
                                                                        {{ $data['count'] }} act. - 
                                                                        <span class="text-success">ETB {{ number_format($data['budget'], 2) }} Birr</span>
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="font-weight-bold text-success">
                                                        ETB {{ number_format($yearTotalBudget, 2) }} Birr
                                                    </td>
                                                    <td>
                                                        @if($yearStatus === 'active')
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ ucfirst($yearStatus) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#yearModal{{ $year }}">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                            <a href="{{ route('sector.health_plans.edit', $yearPlans->first()->id) }}" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('sector.health_plans.destroy', $yearPlans->first()->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this health plan?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            @if($plans->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                                            <p>No health plans found.</p>
                                                            <a href="{{ route('sector.health_plans.create') }}" class="btn btn-primary">
                                                                <i class="fas fa-plus"></i> Create Your First Health Plan
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Year Detail Modals -->
    @foreach($groupedPlans as $year => $yearPlans)
        <div class="modal fade" id="yearModal{{ $year }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-alt mr-2"></i>Health Plan Details for {{ $year }} EC
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @php
                            $yearActivities = [];
                            $yearTypeTotals = [];
                            $yearTotalBudget = 0;
                        @endphp
                        
                        @foreach($yearPlans as $plan)
                            @foreach($plan->health_activity_name as $activityId => $activity)
                                @php
                                    $type = $activity['type'] ?? 'Uncategorized';
                                    $budget = $activity['budget'] ?? 0;
                                    $amount = $activity['amount'] ?? 0;
                                    $unit = $activity['unit'] ?? '';
                                    
                                    // Add to year activities
                                    $yearActivities[] = [
                                        'name' => $activity['name'] ?? 'Unknown',
                                        'type' => $type,
                                        'amount' => $amount,
                                        'unit' => $unit,
                                        'budget' => $budget,
                                    ];
                                    
                                    // Add to type totals
                                    if(!isset($yearTypeTotals[$type])) {
                                        $yearTypeTotals[$type] = [
                                            'activities' => [],
                                            'total_budget' => 0
                                        ];
                                    }
                                    $yearTypeTotals[$type]['activities'][] = $activity['name'] ?? 'Unknown';
                                    $yearTypeTotals[$type]['total_budget'] += $budget;
                                    
                                    $yearTotalBudget += $budget;
                                @endphp
                            @endforeach
                        @endforeach
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-tasks"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Activities</span>
                                        <span class="info-box-number">{{ count($yearActivities) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Budget</span>
                                        <span class="info-box-number">ETB {{ number_format($yearTotalBudget, 2) }} Birr</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-primary">
                                    <span class="info-box-icon"><i class="fas fa-layer-group"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Activity Types</span>
                                        <span class="info-box-number">{{ count($yearTypeTotals) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activities by Type -->
                        @foreach($yearTypeTotals as $type => $typeData)
                            <div class="card card-outline card-info mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-tag mr-2"></i>{{ $type }}
                                        <span class="badge bg-secondary ml-2">{{ count($typeData['activities']) }} activities</span>
                                        <span class="badge bg-success ml-2">ETB {{ number_format($typeData['total_budget'], 2) }} Birr</span>
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="60%">Activity Name</th>
                                                    <th width="20%">Amount</th>
                                                    <th width="20%">Budget</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($yearActivities as $activity)
                                                    @if($activity['type'] === $type)
                                                        <tr>
                                                            <td>
                                                                <i class="fas fa-chevron-right text-primary mr-2"></i>
                                                                {{ $activity['name'] }}
                                                            </td>
                                                            <td>
                                                                <span class="font-weight-bold">{{ number_format($activity['amount'], 2) }}</span>
                                                                <small class="text-muted ml-1">{{ $activity['unit'] }}</small>
                                                            </td>
                                                            <td class="text-success font-weight-bold">
                                                                ETB {{ number_format($activity['budget'], 2) }} Birr
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-light">
                                                    <td class="font-weight-bold text-right">Total for {{ $type }}:</td>
                                                    <td></td>
                                                    <td class="font-weight-bold text-success">
                                                        ETB {{ number_format($typeData['total_budget'], 2) }} Birr
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Overall Summary -->
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Year {{ $year }} Summary</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($yearTypeTotals as $type => $typeData)
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-3 bg-white">
                                                <h6 class="text-primary mb-2">{{ $type }}</h6>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small class="text-muted">Activities:</small>
                                                    <span class="font-weight-bold">{{ count($typeData['activities']) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">Budget:</small>
                                                    <span class="font-weight-bold text-success">ETB {{ number_format($typeData['total_budget'], 2) }} Birr</span>
                                                </div>
                                                @if($yearTotalBudget > 0)
                                                    <div class="progress mt-2" style="height: 10px;">
                                                        <div class="progress-bar bg-success" 
                                                             style="width: {{ ($typeData['total_budget'] / $yearTotalBudget) * 100 }}%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted mt-1 d-block">
                                                        {{ number_format(($typeData['total_budget'] / $yearTotalBudget) * 100, 1) }}% of total budget
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="{{ route('sector.health_plans.edit', $yearPlans->first()->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-2"></i>Edit Plan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>

 
<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        background: #fff;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }
    .info-box .info-box-icon {
        border-radius: .25rem;
        -ms-flex-align: center;
        align-items: center;
        display: -ms-flexbox;
        display: flex;
        font-size: 1.875rem;
        -ms-flex-pack: center;
        justify-content: center;
        text-align: center;
        width: 70px;
    }
    .info-box .info-box-content {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        line-height: 1.8;
        -ms-flex: 1;
        flex: 1;
        padding: 0 10px;
    }
    .info-box .info-box-text {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-transform: uppercase;
        font-size: .875rem;
    }
    .info-box .info-box-number {
        display: block;
        margin-top: .25rem;
        font-weight: 700;
        font-size: 1.5rem;
    }
    .type-summary {
        max-height: 150px;
        overflow-y: auto;
        padding-right: 5px;
    }
    .type-summary::-webkit-scrollbar {
        width: 5px;
    }
    .type-summary::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .type-summary::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .type-summary::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .modal-xl {
        max-width: 1200px;
    }
</style>
 