<x-layout>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-recycle me-2"></i>Gabaasa Fayyadama Kompostii (Woreda Level)
                </h3>
            </div>
        </div>
       <!-- Floating Summary Card -->
       <div id="floating-summary" class="position-absolute top-0 end-0 m-4" style="z-index: 1050; width: 400px; cursor: move;">
    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center" style="cursor: grab;" onclick="toggleSummaryBody()">
            <span><i class="fas fa-chart-bar me-2"></i>Waliigala Gosa Compostii</span>
            <i class="fas fa-chevron-down" id="toggle-icon"></i>
        </div>
        <div class="card-body p-2" id="summary-body" style="max-height: 400px; overflow-y: auto;">
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Gosa</th>
                            <th>{{ $baselineYear }}</th>
                            <th>{{ $selectedYear }}</th>
                            <th>% Jijjiirama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compostTypes as $type)
                            @php
                                $name = $type->name;
                                $data = $reportData[$name];
                                $baseline = $data['baseline_common'] + $data['baseline_individual'];
                                $current = $data['current_common'] + $data['current_individual'];
                            @endphp
                            <tr>
                                <td>{{ $name }}</td>
                                <td class="text-end">{{ number_format($baseline) }}</td>
                                <td class="text-end">{{ number_format($current) }}</td>
                                <td class="text-end {{ $data['change'] > 0 ? 'text-success' : ($data['change'] < 0 ? 'text-danger' : '') }}">
                                    {{ number_format($data['change'], 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('compost.report.woreda') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="year" class="form-label">Bara Filadhu:</label>
                        <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                            <option value="">-- All Years --</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            @if($hasData)
                <!-- Woreda Summary -->
                <div class="table-responsive mb-5">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th rowspan="2">Gosa Compostii</th>
                                <th colspan="1">Raawwii Bara {{ $baselineYear }}</th>
                                <th colspan="1">Karoora Bara {{ $selectedYear }}</th>
                                <th rowspan="2">Jijjiirama (%)</th>
                            </tr>
                            <tr>
                                <th>Hanga</th>
                                 
                                <th>Hanga</th>
                                 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compostTypes as $type)
                                @php
                                    $typeName = $type->name;
                                    $data = $reportData[$typeName];
                                    $baselineTotal = $data['baseline_common'] + $data['baseline_individual'];
                                    $currentTotal = $data['current_common'] + $data['current_individual'];
                                @endphp

                                <tr class="table-secondary">
                                    <td colspan="6" class="fw-bold">{{ $typeName }}</td>
                                </tr>

                                <tr>
                                    <td>Kan Woloo</td>
                                    <td class="text-end">{{ number_format($data['baseline_common']) }}</td>
                                  
                                    <td class="text-end">{{ number_format($data['current_common']) }}</td>
                                    
                                    <td rowspan="3" class="text-center align-middle {{ $data['change'] > 0 ? 'text-success' : ($data['change'] < 0 ? 'text-danger' : '') }}">
                                        {{ number_format($data['change'], 1) }}%
                                    </td>
                                </tr>

                                <tr>
                                    <td>Kan Dhuunfaa</td>
                                    <td class="text-end">{{ number_format($data['baseline_individual']) }}</td>
                                   
                                    <td class="text-end">{{ number_format($data['current_individual']) }}</td>
                                    
                                </tr>

                                <tr>
                                    <td>Woliigala</td>
                                    <td class="text-end">{{ number_format($baselineTotal) }}</td>
                                   
                                    <td class="text-end">{{ number_format($currentTotal) }}</td>
                                    
                                </tr>

                                @if($typeName === 'Nooraa Hojiirra oole')
                                <tr>
                                    <td>Lafa (ha)</td>
                                    <td class="text-end">{{ number_format($data['baseline_land'], 2) }}</td>
                                     
                                    <td class="text-end">{{ number_format($data['current_land'], 2) }}</td>
                                    
                                    <td></td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Kebele Breakdown -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>Gabaasa Kebele-Kebele
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="accordion accordion-flush" id="kebeleAccordion">
                            @foreach($kebeleSummaries as $kebeleId => $summary)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $kebeleId }}">
                                        <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $kebeleId }}" 
                                            aria-expanded="false" aria-controls="collapse{{ $kebeleId }}">
                                            {{ $summary['name'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $kebeleId }}" class="accordion-collapse collapse" 
                                        aria-labelledby="heading{{ $kebeleId }}" data-bs-parent="#kebeleAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th rowspan="2">Gosa</th>
                                                            <th colspan="2">{{ $baselineYear }}</th>
                                                            <th colspan="2">{{ $selectedYear }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Hanga</th>
                                                            <th>Safartuu</th>
                                                            <th>Hanga</th>
                                                            <th>Safartuu</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($compostTypes as $type)
                                                            @php
                                                                $typeName = $type->name;
                                                                $baseline = $summary['data'][$baselineYear][$typeName] ?? null;
                                                                $current = $summary['data'][$selectedYear][$typeName] ?? null;
                                                            @endphp

                                                            <tr class="table-secondary">
                                                                <td colspan="5" class="fw-bold">{{ $typeName }}</td>
                                                            </tr>

                                                            <tr>
                                                                <td>Kan Woloo</td>
                                                                <td class="text-end">{{ number_format($baseline->common ?? 0) }}</td>
                                                                <td>Q</td>
                                                                <td class="text-end">{{ number_format($current->common ?? 0) }}</td>
                                                                <td>Q</td>
                                                            </tr>

                                                            <tr>
                                                                <td>Kan Dhuunfa</td>
                                                                <td class="text-end">{{ number_format($baseline->individual ?? 0) }}</td>
                                                                <td>Q</td>
                                                                <td class="text-end">{{ number_format($current->individual ?? 0) }}</td>
                                                                <td>Q</td>
                                                            </tr>

                                                            @if($typeName === 'Nooraa Hojiirra oole')
                                                            <tr>
                                                                <td>Lafa (ha)</td>
                                                                <td class="text-end">{{ number_format($baseline->land_area ?? 0, 2) }}</td>
                                                                <td>ha</td>
                                                                <td class="text-end">{{ number_format($current->land_area ?? 0, 2) }}</td>
                                                                <td>ha</td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Odeeffannoon gabaasaaf hin jiruu.
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    function toggleSummaryBody() {
        const body = document.getElementById('summary-body');
        const icon = document.getElementById('toggle-icon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            body.style.display = 'none';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }
</script>
@endpush

@push('styles')
<style>
    .table th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table td {
        vertical-align: middle;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
        color: #0c63e4;
    }
    .text-success {
        color: #28a745 !important;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    #floating-summary {
        max-height: 90vh;
        overflow-y: auto;
        transition: top 0.1s, left 0.1s;
        background: white;
    }

    #floating-summary .card-header {
        cursor: grab;
        user-select: none;
    }

    .table-sm th, .table-sm td {
        padding: 0.25rem;
        font-size: 0.875rem;
    }
    
</style>
@endpush
</x-layout>