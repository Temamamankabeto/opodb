<x-layout>
<div class="content-header">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="bg-white border rounded-circle d-flex align-items-center justify-content-center mr-3" style="width:60px;height:60px;">
                        <i class="fas fa-graduation-cap fa-lg text-info"></i>
                    </div>
                    <div>
                        <h1 class="m-0 text-dark">{{ __('Kebele Level Education Report') }}</h1>
                        <small class="text-dark">{{ __('Education participation, achievement, and student feeding analysis') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                // Prepare data for charts
                $hirmaannaaKeys = array_keys($hirmaannaaData['current'] ?? []);
                $hirmaannaaAmounts = array_values($hirmaannaaData['current'] ?? []);
                
                $qabeessummaaKeys = array_keys($qabeessummaaData['current'] ?? []);
                $qabeessummaaAmounts = array_values($qabeessummaaData['current'] ?? []);
                
                $nyaataKeys = array_keys($nyaataData['current'] ?? []);
                $nyaataAmounts = [];
                $nyaataCosts = [];
                
                foreach ($nyaataKeys as $key) {
                    $nyaataAmounts[] = $nyaataData['current'][$key]['amount'] ?? 0;
                    $nyaataCosts[] = $nyaataData['current'][$key]['cost'] ?? 0;
                }
                
                // Summary data from reportData
                $currentGaEessota = 0;
                $currentBarataaIdilee = 0;
                $currentHirmaannaaTotal = 0;
                $currentExamsPassed = 0;
                $currentExamsTaken = 0;
                $currentGradesCompleted = 0;
                $currentSchoolFoodAmount = 0;
                $currentSchoolFoodCost = 0;
                $currentExternalFoodAmount = 0;
                $currentExternalFoodCost = 0;
                $currentTotalFoodAmount = 0;
                $currentTotalFoodCost = 0;
                
                $previousGaEessota = 0;
                $previousBarataaIdilee = 0;
                $previousHirmaannaaTotal = 0;
                $previousExamsPassed = 0;
                $previousExamsTaken = 0;
                $previousGradesCompleted = 0;
                $previousSchoolFoodAmount = 0;
                $previousSchoolFoodCost = 0;
                $previousExternalFoodAmount = 0;
                $previousExternalFoodCost = 0;
                $previousTotalFoodAmount = 0;
                $previousTotalFoodCost = 0;
                
                foreach ($reportData as $data) {
                    // Current year data
                    $currentGaEessota += $data['current']['hirmaannaa']['ga_eessota']['total'] ?? 0;
                    $currentBarataaIdilee += $data['current']['hirmaannaa']['barataa_idilee']['total'] ?? 0;
                    $currentHirmaannaaTotal += $data['current']['hirmaannaa']['total_students'] ?? 0;
                    $currentExamsPassed += $data['current']['qabeessummaa']['exams_passed'] ?? 0;
                    $currentExamsTaken += $data['current']['qabeessummaa']['exams_taken'] ?? 0;
                    $currentGradesCompleted += $data['current']['qabeessummaa']['grades_completed'] ?? 0;
                    $currentSchoolFoodAmount += $data['current']['nyaata']['school_produced']['amount'] ?? 0;
                    $currentSchoolFoodCost += $data['current']['nyaata']['school_produced']['cost'] ?? 0;
                    $currentExternalFoodAmount += $data['current']['nyaata']['external_source']['amount'] ?? 0;
                    $currentExternalFoodCost += $data['current']['nyaata']['external_source']['cost'] ?? 0;
                    $currentTotalFoodAmount += $data['current']['nyaata']['total_amount'] ?? 0;
                    $currentTotalFoodCost += $data['current']['nyaata']['total_cost'] ?? 0;
                    
                    // Previous year data
                    $previousGaEessota += $data['previous']['hirmaannaa']['ga_eessota']['total'] ?? 0;
                    $previousBarataaIdilee += $data['previous']['hirmaannaa']['barataa_idilee']['total'] ?? 0;
                    $previousHirmaannaaTotal += $data['previous']['hirmaannaa']['total_students'] ?? 0;
                    $previousExamsPassed += $data['previous']['qabeessummaa']['exams_passed'] ?? 0;
                    $previousExamsTaken += $data['previous']['qabeessummaa']['exams_taken'] ?? 0;
                    $previousGradesCompleted += $data['previous']['qabeessummaa']['grades_completed'] ?? 0;
                    $previousSchoolFoodAmount += $data['previous']['nyaata']['school_produced']['amount'] ?? 0;
                    $previousSchoolFoodCost += $data['previous']['nyaata']['school_produced']['cost'] ?? 0;
                    $previousExternalFoodAmount += $data['previous']['nyaata']['external_source']['amount'] ?? 0;
                    $previousExternalFoodCost += $data['previous']['nyaata']['external_source']['cost'] ?? 0;
                    $previousTotalFoodAmount += $data['previous']['nyaata']['total_amount'] ?? 0;
                    $previousTotalFoodCost += $data['previous']['nyaata']['total_cost'] ?? 0;
                }
                
                $currentTotalStudents = $currentHirmaannaaTotal + $currentExamsTaken;
                $previousTotalStudents = $previousHirmaannaaTotal + $previousExamsTaken;
                
                $studentChange = $previousTotalStudents > 0 ? 
                    (($currentTotalStudents - $previousTotalStudents) / $previousTotalStudents) * 100 : 0;
            @endphp

            <!-- Year Filter -->
            <div class="card card-outline card-info mb-4">
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
                            <button type="submit" class="btn btn-info btn-sm">
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
                            <h3>{{ number_format($currentTotalStudents) }}</h3>
                            <p>{{ __('Total Students') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($currentExamsPassed) }}</h3>
                            <p>{{ __('Exams Passed') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-award"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($currentGradesCompleted) }}</h3>
                            <p>{{ __('Grades Completed') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ number_format($currentTotalFoodAmount) }}</h3>
                            <p>{{ __('Food Distributed') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <!-- Student Participation Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Student Participation') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($hirmaannaaKeys) > 0)
                                <canvas id="studentParticipationChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No student participation data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Exam Performance Distribution -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Exam Performance') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($qabeessummaaKeys) > 0)
                                <canvas id="examPerformanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-award fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No exam performance data available') }}</h5>
                                    <p class="text-muted">{{ __('Try selecting a different year') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Year Comparison Charts -->
            <div class="row mb-4">
                <!-- Student Type Comparison Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                {{ __('Student Type Comparison') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="studentTypeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Food Distribution Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                {{ __('Food Distribution') }} ({{ $selectedYear }})
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($currentTotalFoodAmount > 0)
                                <canvas id="foodDistributionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No food distribution data available') }}</h5>
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
                        {{ __('Detailed Education Report') }}
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
                                    <th rowspan="3">{{ __('Kebele') }}</th>
                                    <th colspan="8" class="text-center">{{ __('Student Participation') }}</th>
                                    <th colspan="4" class="text-center">{{ __('Academic Achievement') }}</th>
                                    <th colspan="4" class="text-center">{{ __('Student Feeding') }}</th>
                                </tr>
                                <tr>
                                    <!-- Student Participation Headers -->
                                    <th colspan="2">{{ __('Ga`eessota') }}</th>
                                    <th colspan="2">{{ __('Barataa Idilee') }}</th>
                                    <th colspan="2">{{ __('Total') }}</th>
                                    <th>{{ __('K1-6') }}</th>
                                    <th>{{ __('K7-8') }}</th>
                                    <th>{{ __('K9-12') }}</th>
                                    
                                    <!-- Academic Achievement Headers -->
                                    <th>{{ __('Exams Taken') }}</th>
                                    <th>{{ __('Exams Passed') }}</th>
                                    <th>{{ __('Grades Completed') }}</th>
                                    <th>{{ __('Pass Rate') }}</th>
                                    
                                    <!-- Student Feeding Headers -->
                                    <th>{{ __('School Produced') }}</th>
                                    <th>{{ __('External Source') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                    <th>{{ __('Total Cost') }}</th>
                                </tr>
                                <tr>
                                    <!-- Student Participation Sub-headers -->
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Previous') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Previous') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Previous') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    
                                    <!-- Academic Achievement Sub-headers -->
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('%') }}</th>
                                    
                                    <!-- Student Feeding Sub-headers -->
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Current') }}</th>
                                    <th>{{ __('Birr') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportData as $data)
                                    @php
                                        // Calculate pass rate
                                        $examsTaken = $data['current']['qabeessummaa']['exams_taken'] ?? 0;
                                        $examsPassed = $data['current']['qabeessummaa']['exams_passed'] ?? 0;
                                        $passRate = $examsTaken > 0 ? ($examsPassed / $examsTaken) * 100 : 0;
                                        
                                        // Calculate totals
                                        $currentTotal = ($data['current']['hirmaannaa']['ga_eessota']['total'] ?? 0) + 
                                                       ($data['current']['hirmaannaa']['barataa_idilee']['total'] ?? 0);
                                        $previousTotal = ($data['previous']['hirmaannaa']['ga_eessota']['total'] ?? 0) + 
                                                        ($data['previous']['hirmaannaa']['barataa_idilee']['total'] ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                        <!-- Student Participation Data -->
                                        <td class="text-center">{{ number_format($data['current']['hirmaannaa']['ga_eessota']['total'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['hirmaannaa']['ga_eessota']['total'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['hirmaannaa']['barataa_idilee']['total'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['previous']['hirmaannaa']['barataa_idilee']['total'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($currentTotal) }}</td>
                                        <td class="text-center">{{ number_format($previousTotal) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['hirmaannaa']['ga_eessota']['k1_6'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['hirmaannaa']['ga_eessota']['k7_8'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['hirmaannaa']['ga_eessota']['k9_12'] ?? 0) }}</td>
                                        
                                        <!-- Academic Achievement Data -->
                                        <td class="text-center">{{ number_format($examsTaken) }}</td>
                                        <td class="text-center">{{ number_format($examsPassed) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['qabeessummaa']['grades_completed'] ?? 0) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $passRate >= 70 ? 'bg-success' : ($passRate >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ number_format($passRate, 1) }}%
                                            </span>
                                        </td>
                                        
                                        <!-- Student Feeding Data -->
                                        <td class="text-center">{{ number_format($data['current']['nyaata']['school_produced']['amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['nyaata']['external_source']['amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['nyaata']['total_amount'] ?? 0) }}</td>
                                        <td class="text-center">{{ number_format($data['current']['nyaata']['total_cost'] ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="19" class="text-center py-4">
                                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">{{ __('No education data found') }}</h5>
                                            <p class="text-muted">{{ __('Try selecting a different year or ensure data exists for your kebele') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Data Tables -->
            <div class="row mt-4">
                <!-- Hirmaannaa Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title">
                                <i class="fas fa-user-graduate mr-1"></i>
                                {{ __('Student Participation Details') }} ({{ $selectedYear }})
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>{{ __('Type & Grade') }}</th>
                                            <th class="text-right">{{ __('Count') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($hirmaannaaData['current'] as $key => $count)
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td class="text-right">{{ number_format($count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-3 text-muted">
                                                    {{ __('No data available') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Qabeessummaa Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">
                                <i class="fas fa-award mr-1"></i>
                                {{ __('Academic Achievement Details') }} ({{ $selectedYear }})
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>{{ __('Exam Result') }}</th>
                                            <th class="text-right">{{ __('Count') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($qabeessummaaData['current'] as $key => $count)
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td class="text-right">{{ number_format($count) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-3 text-muted">
                                                    {{ __('No data available') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nyaata Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">
                                <i class="fas fa-utensils mr-1"></i>
                                {{ __('Student Feeding Details') }} ({{ $selectedYear }})
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>{{ __('Source') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                            <th class="text-right">{{ __('Cost') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($nyaataData['current'] as $key => $data)
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td class="text-right">{{ number_format($data['amount'] ?? 0) }}</td>
                                                <td class="text-right">{{ number_format($data['cost'] ?? 0, 2) }} {{ __('Birr') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted">
                                                    {{ __('No data available') }}
                                                </td>
                                            </tr>
                                        @endforelse
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

 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Student Participation Chart
        const studentParticipationCtx = document.getElementById('studentParticipationChart');
        if (studentParticipationCtx) {
            const studentParticipationChart = new Chart(studentParticipationCtx, {
                type: 'bar',
                data: {
                    labels: @json($hirmaannaaKeys),
                    datasets: [{
                        label: '{{ __("Number of Students") }}',
                        data: @json($hirmaannaaAmounts),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Number of Students") }}'
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }

        // Exam Performance Chart
        const examPerformanceCtx = document.getElementById('examPerformanceChart');
        if (examPerformanceCtx) {
            const examPerformanceChart = new Chart(examPerformanceCtx, {
                type: 'bar',
                data: {
                    labels: @json($qabeessummaaKeys),
                    datasets: [{
                        label: '{{ __("Number of Students") }}',
                        data: @json($qabeessummaaAmounts),
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Number of Students") }}'
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }

        // Student Type Comparison Chart
        const studentTypeCtx = document.getElementById('studentTypeChart');
        if (studentTypeCtx) {
            const studentTypeChart = new Chart(studentTypeCtx, {
                type: 'bar',
                data: {
                    labels: ['{{ __("Ga`eessota") }}', '{{ __("Barataa Idilee") }}'],
                    datasets: [
                        {
                            label: '{{ $selectedYear }}',
                            data: [{{ $currentGaEessota }}, {{ $currentBarataaIdilee }}],
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: '{{ $previousYear }}',
                            data: [{{ $previousGaEessota }}, {{ $previousBarataaIdilee }}],
                            backgroundColor: 'rgba(201, 203, 207, 0.7)',
                            borderColor: 'rgba(201, 203, 207, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '{{ __("Number of Students") }}'
                            }
                        }
                    }
                }
            });
        }

        // Food Distribution Chart
        const foodDistributionCtx = document.getElementById('foodDistributionChart');
        if (foodDistributionCtx && {{ $currentTotalFoodAmount }} > 0) {
            const foodDistributionChart = new Chart(foodDistributionCtx, {
                type: 'pie',
                data: {
                    labels: ['{{ __("School Produced") }}', '{{ __("External Source") }}'],
                    datasets: [{
                        data: [{{ $currentSchoolFoodAmount }}, {{ $currentExternalFoodAmount }}],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
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