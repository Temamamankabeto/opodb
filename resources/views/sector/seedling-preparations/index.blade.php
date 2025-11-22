<x-layout>
    <div class="container-fluid py-4 seedling-preparations-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-tree text-success me-2"></i>Seedling Preparations (Qophii fi Dhaabbii)</h2>
                <p class="text-muted mb-0 mt-1">Track and manage seedling preparation and planting activities</p>
            </div>
            <a href="{{ route('sector.seedling-preparations.create') }}" class="btn btn-success btn-add-preparation">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Preparation
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

        <!-- Summary Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-primary mb-1">Total Seedling Preparations</h5>
                    <p class="mb-0 text-muted">{{ $preparations->total() }} Records</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="stat-card bg-success bg-opacity-10 p-3 rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-sprout text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">Total Seedlings</small>
                                <span class="fw-bold text-success">{{ $totalSeedlings ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card bg-warning bg-opacity-10 p-3 rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-account-group text-warning me-2"></i>
                            <div>
                                <small class="text-muted d-block">Avg Participants</small>
                                <span class="fw-bold text-warning">{{ number_format($avgParticipants ?? 0, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($preparations->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No seedling preparations found</h4>
                <p class="text-muted mb-4">Get started by creating your first seedling preparation</p>
                <a href="{{ route('sector.seedling-preparations.create') }}" class="btn btn-success">
                    <i class="mdi mdi-plus-circle-outline me-2"></i> Create Preparation
                </a>
            </div>
        @else
            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Seedling Preparations Overview
                    </h5>
                    <div class="table-controls">
                        <small class="text-muted me-3">{{ $preparations->count() }} preparations found</small>
                        <button class="btn btn-sm btn-outline-secondary" onclick="scrollToTop('seedlingTable')">
                            <i class="mdi mdi-arrow-up"></i> Top
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="seedlingTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="ps-4" style="width: 10%">Year (EC)</th>
                                    <th style="width: 15%">Seedling Type</th>
                                    <th style="width: 20%">Seedlings</th>
                                    <th class="text-center" style="width: 20%">Preparation Data (Qophii)</th>
                                    <th class="text-center" style="width: 20%">Planting Data (Dhaabbii)</th>
                                    <th class="text-center" style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentYearIndex = null;
                                    $currentTypeIndex = null;
                                    $yearRowspan = 0;
                                    $typeRowspan = 0;
                                    $yearCounter = 0;
                                    $typeCounter = 0;
                                    
                                    // Group data by year and seedling type
                                    $groupedData = [];
                                    foreach ($preparations as $preparation) {
                                        $prepData = $preparation->preparation_data ?? [];
                                        $plantData = $preparation->planting_data ?? [];
                                        
                                        $seedlingType = $prepData['seedling_type']['name'] ?? ($plantData['seedling_type']['name'] ?? 'N/A');
                                        
                                        $groupedData[] = [
                                            'year' => $preparation->year,
                                            'type' => $seedlingType,
                                            'preparation' => $preparation,
                                            'prepData' => $prepData,
                                            'plantData' => $plantData
                                        ];
                                    }
                                @endphp

                                @foreach($groupedData as $index => $data)
                                    @php
                                        $year = $data['year'];
                                        $type = $data['type'];
                                        $preparation = $data['preparation'];
                                        $prepData = $data['prepData'];
                                        $plantData = $data['plantData'];
                                        
                                        $prepSeedlings = $prepData['selected_seedlings'] ?? [];
                                        $plantSeedlings = $plantData['selected_seedlings'] ?? [];
                                        
                                        // Calculate rowspans dynamically
                                        if ($currentYearIndex !== $year) {
                                            $currentYearIndex = $year;
                                            $yearRowspan = array_reduce($groupedData, function($count, $item) use ($year) {
                                                return $item['year'] == $year ? $count + 1 : $count;
                                            }, 0);
                                            $yearCounter = 0;
                                        }

                                        if ($currentTypeIndex !== $year . '_' . $type) {
                                            $currentTypeIndex = $year . '_' . $type;
                                            $typeRowspan = array_reduce($groupedData, function($count, $item) use ($year, $type) {
                                                return $item['year'] == $year && $item['type'] == $type ? $count + 1 : $count;
                                            }, 0);
                                            $typeCounter = 0;
                                        }

                                        $yearCounter++;
                                        $typeCounter++;
                                    @endphp
                                    
                                    <tr class="data-row hover-row" 
                                        data-year="{{ $year }}"
                                        data-type="{{ $type }}"
                                        data-status="{{ $preparation->status }}">
                                        
                                        <!-- Year Column -->
                                        @if ($yearCounter === 1)
                                            <td class="year-cell align-middle text-center fw-bold" 
                                                rowspan="{{ $yearRowspan }}"
                                                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                <div class="year-content">
                                                    <div class="year-value fs-5 text-primary mb-1">
                                                        {{ $year }}
                                                    </div>
                                                    @if($year == date('Y'))
                                                        <span class="badge bg-success bg-opacity-20 text-success border border-success">
                                                            Current
                                                        </span>
                                                    @endif
                                                    <div class="year-total mt-2">
                                                        <small class="text-muted d-block">Year Summary:</small>
                                                        <div class="fw-bold text-warning">
                                                            {{ $yearRowspan }} Prep{{ $yearRowspan > 1 ? 's' : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Seedling Type Column -->
                                        @if ($typeCounter === 1)
                                            <td class="type-cell align-middle" 
                                                rowspan="{{ $typeRowspan }}"
                                                style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                                <div class="type-content">
                                                    <div class="type-name fw-bold text-dark mb-2">
                                                        {{ $type }}
                                                    </div>
                                                    <div class="type-total">
                                                        <small class="text-muted d-block">Type Summary:</small>
                                                        <div class="fw-bold text-warning">
                                                            {{ $typeRowspan }} Prep{{ $typeRowspan > 1 ? 's' : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Seedlings Column -->
                                        <td class="seedlings-cell">
                                            <div class="seedlings-list">
                                                @if (count($prepSeedlings))
                                                    @foreach ($prepSeedlings as $s)
                                                        <div class="seedling-item mb-2 p-2 border-start border-warning border-3 ps-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-sprout text-success me-2"></i>
                                                                <span class="fw-semibold text-dark">{{ $s['name'] }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif(count($plantSeedlings))
                                                    @foreach ($plantSeedlings as $s)
                                                        <div class="seedling-item mb-2 p-2 border-start border-success border-3 ps-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-sprout text-success me-2"></i>
                                                                <span class="fw-semibold text-dark">{{ $s['name'] }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No seedlings</span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Preparation Data -->
                                        <td class="preparation-cell">
                                            @forelse($prepSeedlings as $s)
                                                <div class="preparation-item mb-3 p-2 border-start border-warning border-3 ps-2 bg-warning bg-opacity-5 rounded">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <div class="fw-semibold text-warning">{{ $s['name'] }}</div>
                                                        <span class="badge bg-warning bg-opacity-20 text-dark">{{ $preparation->status === 'completed' ? 'Completed' : 'In Progress' }}</span>
                                                    </div>
                                                    <div class="preparation-stats">
                                                        <div class="stat-row d-flex justify-content-between">
                                                            <span class="text-muted">Hanga:</span>
                                                            <span class="fw-bold">{{ number_format($s['hanga_qophii'] ?? 0, 2) }}</span>
                                                        </div>
                                                        <div class="stat-row d-flex justify-content-between">
                                                            <span class="text-muted">Participants:</span>
                                                            <span class="fw-bold">{{ number_format($s['nama_hirmaate_qophii'] ?? 0, 2) }}</span>
                                                        </div>
                                                        <div class="stat-row d-flex justify-content-between">
                                                            <span class="text-muted">Days:</span>
                                                            <span class="fw-bold">{{ number_format($s['baayina_guyyaa_qophii'] ?? 0, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-3">
                                                    <i class="mdi mdi-information-outline me-1"></i>
                                                    No preparation data
                                                </div>
                                            @endforelse
                                        </td>

                                        <!-- Planting Data -->
                                        <td class="planting-cell">
                                            @if (count($plantSeedlings))
                                                @foreach ($plantSeedlings as $s)
                                                    <div class="planting-item mb-3 p-2 border-start border-success border-3 ps-2 bg-success bg-opacity-5 rounded">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <div class="fw-semibold text-success">{{ $s['name'] }}</div>
                                                            <span class="badge bg-success bg-opacity-20 text-dark">Planted</span>
                                                        </div>
                                                        <div class="planting-stats">
                                                            <div class="stat-row d-flex justify-content-between">
                                                                <span class="text-muted">Hanga:</span>
                                                                <span class="fw-bold">{{ number_format($s['hanga_dhaabbii'] ?? 0, 2) }}</span>
                                                            </div>
                                                            <div class="stat-row d-flex justify-content-between">
                                                                <span class="text-muted">Participants:</span>
                                                                <span class="fw-bold">{{ number_format($s['nama_hirmaate_dhaabbii'] ?? 0, 2) }}</span>
                                                            </div>
                                                            <div class="stat-row d-flex justify-content-between">
                                                                <span class="text-muted">Days:</span>
                                                                <span class="fw-bold">{{ number_format($s['baayina_guyyaa_dhaabbii'] ?? 0, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <i class="mdi mdi-information-outline me-1"></i>
                                                    No planting data
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Actions Column -->
                                        <td class="actions-cell text-center align-middle">
                                            <div class="action-buttons">
                                                <a href="{{ route('sector.seedling-preparations.edit', $preparation->id) }}" 
                                                   class="btn btn-primary btn-sm action-btn mb-1"
                                                   title="{{ $preparation->status === 'completed' ? 'View Details' : 'Plant Info' }}"
                                                   data-preparation-id="{{ $preparation->id }}">
                                                    <i class="mdi {{ $preparation->status === 'completed' ? 'mdi-eye' : 'mdi-tree' }}"></i>
                                                    <span class="d-block mt-1 small">{{ $preparation->status === 'completed' ? 'View' : 'Plant' }}</span>
                                                </a>
                                                <a href="{{ route('sector.seedling-preparations.editpreparation', $preparation->id) }}" 
                                                   class="btn btn-warning btn-sm action-btn"
                                                   title="Edit Preparation"
                                                   data-preparation-id="{{ $preparation->id }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                    <span class="d-block mt-1 small">Edit</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to table rows
            document.querySelectorAll('.hover-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(135deg, #f0f8ff 0%, #e7f3ff 100%)';
                    this.style.borderLeft = '3px solid #007bff';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.background = '';
                    this.style.borderLeft = '';
                });
            });

            // Add loading states to action buttons
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        if (this.disabled) {
                            this.innerHTML = originalHtml;
                            this.disabled = false;
                        }
                    }, 2000);
                });
            });

            // Initialize table scrolling indicators
            initializeTableScrolling();
        });

        function scrollToTop(tableId) {
            const tableContainer = document.querySelector(`#${tableId}`).closest('.table-container');
            tableContainer.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function initializeTableScrolling() {
            const tableContainers = document.querySelectorAll('.table-container');
            
            tableContainers.forEach(container => {
                container.addEventListener('scroll', function() {
                    if (this.scrollTop > 100) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            });
        }
    </script>

    <style>
        .seedling-preparations-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Table Styles */
        .table-container {
            position: relative;
            max-height: 70vh;
            overflow: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        .sticky-header th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Cell Styles */
        .year-cell, .type-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content, .type-content {
            padding: 0.5rem;
        }

        .seedlings-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .seedling-item {
            transition: all 0.2s ease;
        }

        .seedling-item:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }

        .preparation-item, .planting-item {
            transition: all 0.2s ease;
        }

        .preparation-item:hover, .planting-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-row {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        /* Total sections within cells */
        .year-total, .type-total {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }

        .year-total small, .type-total small {
            font-size: 0.7rem;
        }

        /* Table row styling */
        .table td {
            padding: 0.6rem;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.85rem;
        }

        /* Performance Cards */
        .performance-card {
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .performance-card:hover {
            transform: translateY(-5px);
        }

        .performance-growth {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
        }

        .performance-decline {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #f5c6cb;
        }

        .performance-new {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
        }

        .performance-total {
            background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
            border: 1px solid #b3d9ff;
        }

        .performance-value {
            margin: 0.5rem 0;
        }

        .performance-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .action-btn {
            font-size: 0.7rem;
            padding: 0.3rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            min-width: 50px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Scrollbar */
        .table-container::-webkit-scrollbar,
        .seedlings-list::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-container::-webkit-scrollbar-track,
        .seedlings-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb,
        .seedlings-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb:hover,
        .seedlings-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-container {
                font-size: 0.75rem;
            }
            
            .action-btn {
                font-size: 0.65rem;
                padding: 0.25rem;
            }
            
            .year-value {
                font-size: 0.9rem !important;
            }
            
            .performance-card {
                padding: 1rem;
            }
            
            .performance-value {
                font-size: 1.5rem !important;
            }
        }

        /* Animations */
        .mdi-loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Hover effects */
        .hover-row {
            transition: all 0.3s ease;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Button styles */
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #ff8c00 0%, #ff5722 100%);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
        }

        /* Ensure proper vertical alignment for rowspan cells */
        .year-cell, .type-cell {
            vertical-align: middle !important;
        }

        /* Fix for rowspan borders */
        .table > tbody > tr > td {
            border: 1px solid #dee2e6;
        }

        /* Stat cards */
        .stat-card {
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }
    </style>
</x-layout>