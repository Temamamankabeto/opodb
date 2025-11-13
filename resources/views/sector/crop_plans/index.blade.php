<x-layout>
    <div class="container-fluid py-4 crop-plans-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-sprout text-success me-2"></i> Kebele Crop Plans Summary</h2>
                <p class="text-muted mb-0 mt-1">Track and manage agricultural production plans</p>
            </div>
            <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-success btn-add-plan">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Crop Plan
            </a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                    <span class="flex-grow-1">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Search Section -->
        <div class="search-container mb-4">
            <div class="input-group search-input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="mdi mdi-magnify text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0"
                    placeholder="Search by crop, crop type, season, or year...">
            </div>
        </div>

        @if ($cropPlans->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No crop plans found</h4>
                <p class="text-muted mb-4">Get started by creating your first crop plan</p>
                <a href="{{ route('sector.crop-plans.create') }}" class="btn btn-success">
                    <i class="mdi mdi-plus-circle-outline me-2"></i> Create Crop Plan
                </a>
            </div>
        @else
            @php
                $grandTotalArea = 0;
                $grandTotalYield = 0;
            @endphp

            <!-- Data Table -->
            <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
                <table class="table table-bordered align-middle table-hover mb-0" id="cropPlansTable">
                    <thead class="table-success position-sticky top-0">
                        <tr>
                            <th class="ps-3">Year</th>
                            <th>Crop Type</th>
                            <th>Season</th>
                            <th>Crop</th>
                            <th>Area (ha)</th>
                            <th>Productivity</th>
                            <th>Expected Yield (qt)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cropPlans->groupBy('year') as $year => $plansByYear)
                            @php
                                $yearTotalArea = 0;
                                $yearTotalYield = 0;

                                // Calculate total data rows for this year
                                $yearDataRows = $plansByYear->count();
                                $yearDisplayed = false;
                            @endphp

                            @foreach ($plansByYear->groupBy('croptype_id') as $typeId => $plansByType)
                                @php
                                    $cropType = $plansByType->first()->cropType;
                                    $cropTypeTotalArea = 0;
                                    $cropTypeTotalYield = 0;

                                    // Calculate total data rows for this crop type
                                    $cropTypeDataRows = $plansByType->count();
                                    $cropTypeDisplayed = false;
                                @endphp

                                @foreach ($plansByType->groupBy('season') as $season => $plansBySeason)
                                    @php
                                        $seasonTotalArea = 0;
                                        $seasonTotalYield = 0;
                                        $seasonFirstRow = true;
                                    @endphp

                                    @foreach ($plansBySeason as $plan)
                                        <tr class="data-row">
                                            {{-- Year Column --}}
                                            @if (!$yearDisplayed)
                                                <td rowspan="{{ $yearDataRows }}" class="fw-bold text-center year-cell">
                                                    {{ $year }}</td>
                                                @php $yearDisplayed = true; @endphp
                                            @endif

                                            {{-- Crop Type Column --}}
                                            @if (!$cropTypeDisplayed && $seasonFirstRow)
                                                <td rowspan="{{ $cropTypeDataRows }}" class="fw-bold crop-type-cell">
                                                    {{ $cropType->name ?? '-' }}</td>
                                                @php $cropTypeDisplayed = true; @endphp
                                            @endif

                                            {{-- Season Column --}}
                                            @if ($seasonFirstRow)
                                                <td><span
                                                        class="season-badge season-{{ $season }}">{{ ucfirst($season) }}</span>
                                                </td>
                                                @php $seasonFirstRow = false; @endphp
                                            @else
                                                <td></td>
                                            @endif

                                            {{-- Crop Details --}}
                                            <td class="crop-name">{{ $plan->crop->name ?? '-' }}</td>
                                            <td class="text-end">{{ number_format($plan->area_to_plant, 2) }}</td>
                                            <td class="text-center">{{ $plan->productivity ?? '-' }}</td>
                                            <td class="text-end">{{ number_format($plan->expected_yield, 2) }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm" type="button"
                                                        id="dropdownMenuButton{{ $plan->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical fs-5"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                        aria-labelledby="dropdownMenuButton{{ $plan->id }}">
                                                        <li>
                                                            <a class="dropdown-item text-warning"
                                                                href="{{ route('sector.crop-plans.edit', $plan->id) }}">
                                                                <i class="mdi mdi-pencil-outline me-2"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning"
                                                                href="{{ route('sector.crop-plans.editfertilizer', $plan->id) }}">
                                                                <i class="mdi mdi-pencil-outline me-2"></i> Add Fertilizer
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('sector.crop-plans.destroy', $plan->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this crop plan?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger">
                                                                    <i class="mdi mdi-delete-outline me-2"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>

                                        </tr>

                                        @php
                                            $seasonTotalArea += $plan->area_to_plant;
                                            $seasonTotalYield += $plan->expected_yield;
                                            $cropTypeTotalArea += $plan->area_to_plant;
                                            $cropTypeTotalYield += $plan->expected_yield;
                                            $yearTotalArea += $plan->area_to_plant;
                                            $yearTotalYield += $plan->expected_yield;
                                        @endphp
                                    @endforeach
                                @endforeach
                            @endforeach

                            @php
                                $grandTotalArea += $yearTotalArea;
                                $grandTotalYield += $yearTotalYield;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary Section --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card summary-card shadow-sm border-0">
                        <div class="card-header bg-success text-white summary-header">
                            <h5 class="card-title mb-0"><i class="mdi mdi-chart-bar me-2"></i> Production Summary</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="summary-content p-4">
                                @foreach ($cropPlans->groupBy('year') as $year => $plansByYear)
                                    @php
                                        $yearTotalArea = 0;
                                        $yearTotalYield = 0;
                                    @endphp

                                    <div class="year-summary mb-4">
                                        <div class="year-header d-flex align-items-center mb-3">
                                            <i class="mdi mdi-calendar-text text-primary me-2"></i>
                                            <h6 class="mb-0 text-primary">Year: {{ $year }}</h6>
                                        </div>

                                        @foreach ($plansByYear->groupBy('croptype_id') as $typeId => $plansByType)
                                            @php
                                                $cropType = $plansByType->first()->cropType;
                                                $cropTypeTotalArea = 0;
                                                $cropTypeTotalYield = 0;
                                            @endphp

                                            <div class="crop-type-summary mb-3 ps-3">
                                                <div class="crop-type-header d-flex align-items-center mb-2">
                                                    <i class="mdi mdi-seed text-warning me-2"></i>
                                                    <h6 class="mb-0 text-warning">Crop Type:
                                                        {{ $cropType->name ?? 'Unknown' }}</h6>
                                                </div>

                                                @foreach ($plansByType->groupBy('season') as $season => $plansBySeason)
                                                    @php
                                                        $seasonTotalArea = $plansBySeason->sum('area_to_plant');
                                                        $seasonTotalYield = $plansBySeason->sum('expected_yield');
                                                        $cropTypeTotalArea += $seasonTotalArea;
                                                        $cropTypeTotalYield += $seasonTotalYield;
                                                    @endphp

                                                    <div
                                                        class="season-summary d-flex justify-content-between mb-2 ps-3">
                                                        <span class="d-flex align-items-center">
                                                            <span
                                                                class="season-indicator season-{{ $season }} me-2"></span>
                                                            {{ ucfirst($season) }} Subtotal:
                                                        </span>
                                                        <span class="text-end">
                                                            Area: <strong>{{ number_format($seasonTotalArea, 2) }}
                                                                ha</strong> |
                                                            Yield: <strong>{{ number_format($seasonTotalYield, 2) }}
                                                                qt</strong>
                                                        </span>
                                                    </div>
                                                @endforeach

                                                <div
                                                    class="crop-type-total d-flex justify-content-between mt-2 mb-3 border-top pt-2">
                                                    <strong>Total for {{ $cropType->name ?? 'Crop Type' }}:</strong>
                                                    <strong class="text-end">
                                                        Area: {{ number_format($cropTypeTotalArea, 2) }} ha |
                                                        Yield: {{ number_format($cropTypeTotalYield, 2) }} qt
                                                    </strong>
                                                </div>
                                            </div>

                                            @php
                                                $yearTotalArea += $cropTypeTotalArea;
                                                $yearTotalYield += $cropTypeTotalYield;
                                            @endphp
                                        @endforeach

                                        <div
                                            class="year-total d-flex justify-content-between mt-3 mb-4 p-3 bg-light rounded">
                                            <strong>Total for {{ $year }}:</strong>
                                            <strong class="text-end">
                                                Area: {{ number_format($yearTotalArea, 2) }} ha |
                                                Yield: {{ number_format($yearTotalYield, 2) }} qt
                                            </strong>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Grand Total --}}
                                <div
                                    class="grand-total d-flex justify-content-between mt-4 p-3 bg-dark text-white rounded">
                                    <strong class="h5 d-flex align-items-center">
                                        <i class="mdi mdi-earth me-2"></i> Grand Total
                                    </strong>
                                    <strong class="h5 text-end">
                                        Area: {{ number_format($grandTotalArea, 2) }} ha |
                                        Yield: {{ number_format($grandTotalYield, 2) }} qt
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            document.querySelectorAll('#cropPlansTable tbody tr.data-row').forEach(function(row) {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(value) ? '' : 'none';

                // Show/hide parent rows based on filtered results
                let currentRow = row;
                while (currentRow) {
                    if (currentRow.classList.contains('data-row')) {
                        break;
                    }
                    currentRow = currentRow.previousElementSibling;
                }
            });
        });
    </script>

    <style>
        .crop-plans-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header h2 {
            font-weight: 600;
            color: #2c3e50;
        }

        .btn-add-plan {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-add-plan:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .custom-alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-input-group {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-input-group .form-control {
            padding: 12px 15px;
            border-radius: 0 8px 8px 0;
        }

        .search-input-group .input-group-text {
            padding: 12px 15px;
            border-radius: 8px 0 0 8px;
        }

        .empty-state {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 60px 20px;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            font-weight: 600;
            padding: 15px 10px;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .year-cell {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5) !important;
            color: #1565c0;
            font-size: 1.1rem;
        }

        .crop-type-cell {
            background: linear-gradient(135deg, #e8f5e8, #f0f4c3) !important;
            color: #2e7d32;
        }

        .season-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .season-spring {
            background-color: #c8e6c9;
            color: #2e7d32;
        }

        .season-summer {
            background-color: #fff9c4;
            color: #f57f17;
        }

        .season-autumn {
            background-color: #ffccbc;
            color: #d84315;
        }

        .season-winter {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .crop-name {
            font-weight: 500;
            color: #37474f;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .btn-action {
            border-radius: 6px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: scale(1.05);
        }

        .summary-card {
            border-radius: 12px;
            overflow: hidden;
        }

        .summary-header {
            padding: 15px 20px;
            border: none;
        }

        .summary-content {
            background: #fafafa;
        }

        .year-header,
        .crop-type-header {
            padding-bottom: 5px;
        }

        .season-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .season-summary,
        .crop-type-total,
        .year-total {
            font-size: 0.95rem;
        }

        .grand-total {
            border-radius: 10px;
            background: linear-gradient(135deg, #424242, #616161) !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-header h2 {
                font-size: 1.5rem;
            }

            .btn-add-plan {
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 3px;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }

            .season-summary,
            .crop-type-total,
            .year-total {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .grand-total {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</x-layout>
