 <x-layout>

  <style>
         .report-header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .report-title {
            font-weight: 700;
            text-align: center;
        }
    </style>
     <!-- <div class="report-header">
            <h1 class="report-title">Gabaasa Karoora Carraa hojii Uumuu Godana {{ Auth::user()->zone->name }} </h1>
            <p class="text-center mb-0">Gabaasa Ulaagaa barbaaddeen woraabii ilaali  </p>
        </div> -->
    <div class="container py-4">
        <div class="report-header">
            <h2 class="report-title">Gabasa Carraa Hojii Uumuu - {{ Auth::user()->woreda->name }}</h2>
            <p class="text-center mb-0">Carraa Hojii uumame akkaataa barbaaddan ilaalchisuun</p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section bg-light p-4 rounded shadow-sm mb-4">
            <form method="GET" action="{{ route('woreda.reports.job_report') }}" class="row g-3">
                <!-- Filters same as before -->
                <div class="col-md-3">
                    <label for="year" class="form-label">Bara</label>
                    <select name="year" id="year" class="form-select">
                        <option value="">-- Bara Filadhu --</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="enterprise_type" class="form-label">Akaaku Interpiraayizii</label>
                    <select name="enterprise_type" id="enterprise_type" class="form-select">
                        <option value="">-- Akaaku Filadhu --</option>
                        @foreach($enterpriseTypes as $type)
                            <option value="{{ $type }}" {{ request('enterprise_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="job_type_id" class="form-label">Gosa Hojii</label>
                    <select name="job_type_id" id="job_type_id" class="form-select">
                        <option value="">-- Gosa Hojii Filadhu --</option>
                        @foreach($jobTypes as $jobType)
                            <option value="{{ $jobType->id }}" {{ request('job_type_id') == $jobType->id ? 'selected' : '' }}>
                                {{ $jobType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="kebele_id" class="form-label">Ganda</label>
                    <select name="kebele_id" id="kebele_id" class="form-select">
                        <option value="">-- Ganda Filadhu --</option>
                        @foreach($kebeles as $kebele)
                            <option value="{{ $kebele->id }}" {{ request('kebele_id') == $kebele->id ? 'selected' : '' }}>
                                {{ $kebele->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filannoo Kee Galchi
                    </button>
                    <a href="{{ route('woreda.reports.job_report') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-sync me-1"></i>Deebii
                    </a>
                </div>
            </form>
        </div>

        <!-- Print Button -->
        <div class="mb-3 text-end">
            <button onclick="window.print()" class="btn btn-success">
                <i class="fas fa-print me-1"></i>Buufadhu
            </button>
        </div>

        <!-- Report Table -->
        @if($jobs->count())
            <div class="table-responsive mb-5">
                @php
                    $totalMale = 0;
                    $totalFemale = 0;
                    $totalSum = 0;

                    // Group by kebele, year, enterprise_type, jobType name
                    $grouped = $jobs->groupBy([
                        fn($item) => $item->kebele->name,
                        'year',
                        'enterprise_type',
                        fn($item) => $item->jobType->name,
                    ]);

                    $allKebelesMaleTotal = 0;
                    $allKebelesFemaleTotal = 0;
                    $allKebelesSumTotal = 0;
                @endphp

                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Ganda</th>
                            <th>Bara</th>
                            <th>Akaaku Interpiraayizii</th>
                            <th>Gosa Hojii</th>
                            <th>Dhiira</th>
                            <th>Dubartii</th>
                            <th>Waliigala</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grouped as $kebeleName => $years)
                            @php
                                $kebeleRowspan = $years->flatten(3)->count();
                                $firstKebeleRendered = false;

                                // Initialize kebele subtotal counters
                                $kebeleMaleTotal = 0;
                                $kebeleFemaleTotal = 0;
                                $kebeleSumTotal = 0;
                            @endphp

                            @foreach ($years as $year => $enterprises)
                                @php $yearRowspan = $enterprises->flatten(2)->count(); @endphp
                                @php $firstYearRendered = false; @endphp

                                @foreach ($enterprises as $enterprise => $jobGroups)
                                    @php $enterpriseRowspan = $jobGroups->flatten(1)->count(); @endphp
                                    @php $firstEnterpriseRendered = false; @endphp

                                    @foreach ($jobGroups as $jobType => $jobsList)
                                        @php $firstJobTypeRendered = false; @endphp

                                        @foreach ($jobsList as $job)
                                            <tr>
                                                @if (!$firstKebeleRendered)
                                                    <td rowspan="{{ $kebeleRowspan }}">{{ $kebeleName }}</td>
                                                    @php $firstKebeleRendered = true; @endphp
                                                @endif

                                                @if (!$firstYearRendered)
                                                    <td rowspan="{{ $yearRowspan }}">{{ $year }}</td>
                                                    @php $firstYearRendered = true; @endphp
                                                @endif

                                                @if (!$firstEnterpriseRendered)
                                                    <td rowspan="{{ $enterpriseRowspan }}">{{ $enterprise }}</td>
                                                    @php $firstEnterpriseRendered = true; @endphp
                                                @endif

                                                @if (!$firstJobTypeRendered)
                                                    <td rowspan="{{ count($jobsList) }}">{{ $jobType }}</td>
                                                    @php $firstJobTypeRendered = true; @endphp
                                                @endif

                                                <td>{{ $job->male }}</td>
                                                <td>{{ $job->female }}</td>
                                                <td>{{ $job->sum }}</td>
                                            </tr>

                                            @php
                                                // Add to kebele subtotal
                                                $kebeleMaleTotal += $job->male;
                                                $kebeleFemaleTotal += $job->female;
                                                $kebeleSumTotal += $job->sum;

                                                // Add to grand total
                                                $totalMale += $job->male;
                                                $totalFemale += $job->female;
                                                $totalSum += $job->sum;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach

                            {{-- Print subtotal row for this kebele --}}
                            <tr class="table-secondary fw-bold">
                                <td colspan="4" class="text-end">Waliigala {{ $kebeleName }}</td>
                                <td>{{ $kebeleMaleTotal }}</td>
                                <td>{{ $kebeleFemaleTotal }}</td>
                                <td>{{ $kebeleSumTotal }}</td>
                            </tr>

                            @php
                                // Add kebele subtotal to all kebeles total
                                $allKebelesMaleTotal += $kebeleMaleTotal;
                                $allKebelesFemaleTotal += $kebeleFemaleTotal;
                                $allKebelesSumTotal += $kebeleSumTotal;
                            @endphp
                        @endforeach

                        {{-- Print combined total of all kebeles --}}
                        <tr class="table-info fw-bold">
                            <td colspan="4" class="text-end">Waliigala Ganda Hunda</td>
                            <td>{{ $allKebelesMaleTotal }}</td>
                            <td>{{ $allKebelesFemaleTotal }}</td>
                            <td>{{ $allKebelesSumTotal }}</td>
                        </tr>

                        {{-- Your existing overall total row --}}
                        <tr class="table-warning fw-bold">
                            <td colspan="4">Waliigala</td>
                            <td>{{ $totalMale }}</td>
                            <td>{{ $totalFemale }}</td>
                            <td>{{ $totalSum }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm p-3">
                        <h5 class="card-title">Gabatee Carraa Hojii (Kebele Keessatti)</h5>
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm p-3">
                        <h5 class="card-title">Qoodinsa Saalaa (Dhiira fi Dubartii)</h5>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>

            @php
                // Prepare data for charts
                $chartLabels = $grouped->keys()->toArray();

                // Male, female, sum totals per kebele for bar chart
                $maleData = [];
                $femaleData = [];
                $sumData = [];

                foreach ($grouped as $kebeleName => $years) {
                    $kebeleMale = 0;
                    $kebeleFemale = 0;
                    $kebeleSum = 0;

                    foreach ($years as $year => $enterprises) {
                        foreach ($enterprises as $enterprise => $jobGroups) {
                            foreach ($jobGroups as $jobType => $jobsList) {
                                foreach ($jobsList as $job) {
                                    $kebeleMale += $job->male;
                                    $kebeleFemale += $job->female;
                                    $kebeleSum += $job->sum;
                                }
                            }
                        }
                    }

                    $maleData[] = $kebeleMale;
                    $femaleData[] = $kebeleFemale;
                    $sumData[] = $kebeleSum;
                }
            @endphp

            <!-- Include Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const barCtx = document.getElementById('barChart').getContext('2d');
                    const pieCtx = document.getElementById('pieChart').getContext('2d');

                    // Bar chart: Male, Female, Sum per kebele
                    const barChart = new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($chartLabels),
                            datasets: [
                                {
                                    label: 'Dhiira',
                                    data: @json($maleData),
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                },
                                {
                                    label: 'Dubartii',
                                    data: @json($femaleData),
                                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                },
                                {
                                    label: 'Waliigala',
                                    data: @json($sumData),
                                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 },
                                }
                            }
                        }
                    });

                    // Pie chart: Overall male vs female
                    const pieChart = new Chart(pieCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Dhiira', 'Dubartii'],
                            datasets: [{
                                data: [{{ $totalMale }}, {{ $totalFemale }}],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 99, 132, 0.7)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'bottom' },
                                tooltip: { enabled: true }
                            }
                        }
                    });
                });
            </script>
        @else
            <p class="text-center">Galmeen Carraa Hojii hin jiru.</p>
        @endif
    </div>
</x-layout>
