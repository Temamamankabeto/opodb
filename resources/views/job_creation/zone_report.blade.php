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
     <div class="report-header">
            <h1 class="report-title">Gabaasa Karoora Carraa hojii Uumuu Godana {{ Auth::user()->zone->name }} </h1>
            <p class="text-center mb-0">Gabaasa Ulaagaa barbaaddeen woraabii ilaali  </p>
        </div>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <label for="year" class="form-label">Waggaa</label>
            <select name="year" id="year" class="form-select">
                <option value="">-- Filadhu --</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="enterprise_type" class="form-label">Teessuma Hojii</label>
            <select name="enterprise_type" id="enterprise_type" class="form-select">
                <option value="">-- Filadhu --</option>
                @foreach($enterpriseTypes as $type)
                    <option value="{{ $type }}" {{ request('enterprise_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="job_type_id" class="form-label">Gosa Hojii</label>
            <select name="job_type_id" id="job_type_id" class="form-select">
                <option value="">-- Filadhu --</option>
                @foreach($jobTypes as $jobType)
                    <option value="{{ $jobType->id }}" {{ request('job_type_id') == $jobType->id ? 'selected' : '' }}>{{ $jobType->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="woreda_id" class="form-label">Woreda</label>
            <select name="woreda_id" id="woreda_id" class="form-select">
                <option value="">-- Filadhu --</option>
                @foreach($woredas as $woreda)
                    <option value="{{ $woreda->id }}" {{ request('woreda_id') == $woreda->id ? 'selected' : '' }}>{{ $woreda->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Barbaadi</button>
        </div>
    </form>

    @php
        $grouped = $jobs->groupBy([
            fn($item) => $item->woreda->name,
            'year',
            'enterprise_type',
            fn($item) => $item->jobType->name,
        ]);

        $totalMale = 0;
        $totalFemale = 0;
        $totalSum = 0;

        $allWoredasMaleTotal = 0;
        $allWoredasFemaleTotal = 0;
        $allWoredasSumTotal = 0;
    @endphp

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark text-center align-middle">
                <tr>
                    <th>Woreda</th>
                    <th>Waggaa</th>
                    <th>Teessuma Hojii</th>
                    <th>Gosa Hojii</th>
                    <th>Dhiira</th>
                    <th>Dubbii</th>
                    <th>Waliigala</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped as $woredaName => $years)
                    @php
                        $woredaRowspan = $years->flatten(3)->count();
                        $firstWoredaRendered = false;

                        $woredaMaleTotal = 0;
                        $woredaFemaleTotal = 0;
                        $woredaSumTotal = 0;
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
                                        @if (!$firstWoredaRendered)
                                            <td rowspan="{{ $woredaRowspan }}">{{ $woredaName }}</td>
                                            @php $firstWoredaRendered = true; @endphp
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
                                        $woredaMaleTotal += $job->male;
                                        $woredaFemaleTotal += $job->female;
                                        $woredaSumTotal += $job->sum;

                                        $totalMale += $job->male;
                                        $totalFemale += $job->female;
                                        $totalSum += $job->sum;
                                    @endphp
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach

                    <tr class="table-secondary fw-bold">
                        <td colspan="4" class="text-end">Waliigala Woreda {{ $woredaName }}</td>
                        <td>{{ $woredaMaleTotal }}</td>
                        <td>{{ $woredaFemaleTotal }}</td>
                        <td>{{ $woredaSumTotal }}</td>
                    </tr>

                    @php
                        $allWoredasMaleTotal += $woredaMaleTotal;
                        $allWoredasFemaleTotal += $woredaFemaleTotal;
                        $allWoredasSumTotal += $woredaSumTotal;
                    @endphp
                @endforeach
            </tbody>

            <tfoot class="table-primary fw-bold">
                <tr>
                    <td colspan="4" class="text-end">Waliigala Zone</td>
                    <td>{{ $allWoredasMaleTotal }}</td>
                    <td>{{ $allWoredasFemaleTotal }}</td>
                    <td>{{ $allWoredasSumTotal }}</td>
                </tr>
            </tfoot>
        </table>


        @if(request('year') && isset($comparisonTotals))
    <h4 class="mt-5">Waliigala Woreda ({{ request('year') }}) fi ({{ request('year') - 1 }}) Walbira</h4>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th rowspan="2">Woreda</th>
                    <th colspan="3">{{ request('year') }}</th>
                    <th colspan="3">{{ request('year') - 1 }}</th>
                </tr>
                <tr>
                    <th>Dhiira</th>
                    <th>Dubbii</th>
                    <th>Waliigala</th>
                    <th>Dhiira</th>
                    <th>Dubbii</th>
                    <th>Waliigala</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comparisonTotals as $data)
                    <tr class="text-end">
                        <td class="text-start">{{ $data['woreda_name'] }}</td>
                        <td>{{ $data['selected_year_male'] }}</td>
                        <td>{{ $data['selected_year_female'] }}</td>
                        <td>{{ $data['selected_year_total'] }}</td>
                        <td>{{ $data['previous_year_male'] }}</td>
                        <td>{{ $data['previous_year_female'] }}</td>
                        <td>{{ $data['previous_year_total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

    </div>

    <div class="my-5">
        <canvas id="jobChart" height="120"></canvas>
    </div>

    @php
        $chartData = $jobs->groupBy('year')->map(function ($group) {
            return [
                'male' => $group->sum('male'),
                'female' => $group->sum('female'),
                'total' => $group->sum('sum'),
            ];
        });

        $labels = $chartData->keys()->toArray();
        $maleData = $chartData->pluck('male')->toArray();
        $femaleData = $chartData->pluck('female')->toArray();
        $totalData = $chartData->pluck('total')->toArray();
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('jobChart').getContext('2d');
        const jobChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Dhiira',
                        data: @json($maleData),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Dubbii',
                        data: @json($femaleData),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Waliigala',
                        data: @json($totalData),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Gabaasa Hojii Uumuu Waggaa Wajjin'
                    }
                }
            }
        });
    </script>
</x-layout>
