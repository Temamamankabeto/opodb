<!DOCTYPE html>
<x-layout>


<div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="statistics-details d-flex align-items-center justify-content-between">
                            <div>
                              <p class="statistics-title">Karoora Midhaan Agadaa</p>
                              <h3 class="rate-percentage">32.53%</h3>
                              <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                            </div>
                            <div>
                              <p class="statistics-title">Karoora Midhaan Dheedhii </p>
                              <h3 class="rate-percentage">7,682</h3>
                              <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                            </div>
                            <div>
                              <p class="statistics-title">Karoora Midhaan Biilaa</p>
                              <h3 class="rate-percentage">68.8</h3>
                              <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                            </div>
                            <div class="d-none d-md-block">
                              <p class="statistics-title">Karoora Midhaan Dibataa</p>
                              <h3 class="rate-percentage">2m:35s</h3>
                              <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                            </div>
                            <div class="d-none d-md-block">
                              <p class="statistics-title">Midhaan Biroo</p>
                              <h3 class="rate-percentage">68.8</h3>
                              <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>




<div class="row">
  <div class="col-12">
    <div class="card card-rounded">
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-start mb-4">
          <div>
            <h2>Misooma Midhaanii Gurguddaa: Ganna</h2>
          </div>
        </div>

        <h4 class="card-title">Oomisha Midhaan Gurguddaa Gannaa (kun mil)</h4>

        <div class="mb-3">
          <label for="yearSelector" class="form-label">Filadhu Bara</label>
          <select id="yearSelector" class="form-select">
            @foreach(array_keys($chartData) as $year)
              <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
          </select>
        </div>

        <canvas id="cropProductionChart" height="120"></canvas>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-12">
    <div class="card card-rounded">
      <div class="card-body">
        <div class="d-sm-flex justify-content-between align-items-start mb-4">
          <div>
            <h2>Misooma Midhaanii Gurguddaa: Ganna</h2>
          </div>
        </div>

        <h4 class="card-title">Bal’ina Lafaa Midhaan Gurguddaa Gannaa (kun ha)</h4>

        <div class="mb-3">
          <label for="landYearSelector" class="form-label">Filadhu Bara</label>
          <select id="landYearSelector" class="form-select">
            @foreach(array_keys($chartData) as $year)
              <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
          </select>
        </div>

        <canvas id="landPlanChart" height="120"></canvas>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const chartData = @json($chartData);
  const yearSelector = document.getElementById('landYearSelector');
  const ctx = document.getElementById('landPlanChart').getContext('2d');

  let chartInstance;

  function renderLandChart(year) {
    const selected = chartData[year];

    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: selected.labels,
        datasets: [{
          label: 'Bal’ina Lafaa [ha]',
          data: selected.data,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 50
            }
          }
        }
      }
    });
  }

  yearSelector.addEventListener('change', (e) => {
    renderLandChart(e.target.value);
  });

  // Initial load
  renderLandChart(yearSelector.value);
</script>


<title>Crop Plan and Achievement Overview</title>

    <style>
        .card {
            border: none;
            border-radius: 0;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .table th, .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .year-filter {
            margin-bottom: 20px;
        }
    </style>

    <div class="container my-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Raawwii fi Karoora Misooma Midhaanii</h4>

                <!-- Year Filter -->
                <form method="GET" action="{{ route('crop_plan_achievements.index') }}" class="year-filter">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="year" class="col-form-label">Select Year:</label>
                        </div>
                        <div class="col-auto">
                            <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                                @foreach ($chartData as $year => $data)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th rowspan="2">Crop Type</th>
                            <th rowspan="2">Crop Name</th>
                            <th rowspan="2">Season</th>
                            <th colspan="2">Baseline</th>
                            <th colspan="2">Plan</th>
                            <th colspan="2">Achievement</th>
                            <th rowspan="2">Performance (%)</th>
                        </tr>
                        <tr>
                            <th>Land Area (ha)</th>
                            <th>Production (qt)</th>
                            <th>Land Area (ha)</th>
                            <th>Production (qt)</th>
                            <th>Land Area (ha)</th>
                            <th>Production (qt)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($grouped as $key => $items)
                            @php
                                $firstItem = $items->first();
                                $seasons = ['summury', 'spring', 'autumn'];
                            @endphp

                            @foreach ($seasons as $season)
                                <tr>
                                    @if ($loop->first)
                                        <td rowspan="{{ count($seasons) }}">{{ $firstItem->croptype->name ?? '-' }}</td>
                                        <td rowspan="{{ count($seasons) }}">{{ $firstItem->maqaa_midhaanii ?? '-' }}</td>
                                    @endif

                                    <td>{{ ucfirst($season) }}</td>

                                    {{-- Baseline --}}
                                    <td>{{ $firstItem->{$season . '_land_area_plan'} ?? '-' }}</td>
                                    <td>{{ $firstItem->{$season . '_production_plan'} ?? '-' }}</td>

                                    {{-- Plan --}}
                                    <td>{{ $firstItem->{$season . '_land_area_plan'} ?? '-' }}</td>
                                    <td>{{ $firstItem->{$season . '_production_plan'} ?? '-' }}</td>

                                    {{-- Achievement --}}
                                    <td>{{ $firstItem->{$season . '_land_area_achievement'} ?? '-' }}</td>
                                    <td>{{ $firstItem->{$season . '_production_achievement'} ?? '-' }}</td>

                                    {{-- Performancy --}}
                                    @if ($loop->first)
                                        <td rowspan="{{ count($seasons) }}">
                                            <div class="progress">
                                                <div class="progress-bar bg-success"
                                                     style="width: {{ $firstItem->performancy ?? 0 }}%">
                                                    {{ $firstItem->performancy ?? 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateBaraDarbeDropdown(selectedYear) {
            // Calculate the previous year
            var previousYear = parseInt(selectedYear) - 1;

            // Find the "Bara Darbe" dropdown
            var darbeDropdown = document.getElementById("year");

            // Clear previous options
            darbeDropdown.innerHTML = "";

            // Dynamically create the option for the previous year
            var option = document.createElement("option");
            option.value = previousYear;
            option.text = previousYear;
            darbeDropdown.appendChild(option);

            // Automatically select the previous year in the dropdown
            darbeDropdown.value = previousYear;

            // Submit the form to filter based on the selected year
            this.form.submit();
        }
    </script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cropDataByYear = {!! json_encode($chartData) !!};

        const ctx = document.getElementById('cropProductionChart').getContext('2d');
        let cropChart;

        function renderChart(year) {
            const yearData = cropDataByYear[year];

            if (!yearData) return;

            const { labels, data } = yearData;

            if (cropChart) cropChart.destroy();

            cropChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Oomisha karoorfame (kun mil) - ${year}`,
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Oomisha (kun mil)'
                            }
                        }
                    }
                }
            });
        }

        const yearSelector = document.getElementById('yearSelector');
        renderChart(yearSelector.value); // Initial render

        yearSelector.addEventListener('change', function () {
            renderChart(this.value);
        });
    });
</script>


</x-layout>
