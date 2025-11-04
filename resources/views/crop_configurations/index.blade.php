<x-layout>
    <div class="container-fluid py-4 bg-light min-vh-100">
        <div class="row justify-content-center">
            <div class="col-12">
            
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 text-success fw-bold">Crop Configurations</h4>
                        <p class="text-muted mb-0">Manage crop-specific configurations across different kebeles and soil types</p>
                    </div>
                    <a href="{{ route('crop-configurations.create') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Add New Configuration
                    </a>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div class="fw-medium">{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-seedling text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-dark" id="total-configs">{{ $configs->total() }}</h4>
                                        <small class="text-muted">Total Configurations</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-geo-alt text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-dark" id="total-kebeles">{{ $configs->pluck('kebele_id')->unique()->count() }}</h4>
                                        <small class="text-muted">Kebeles Covered</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-flower1 text-warning fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-dark" id="total-crops">{{ $configs->pluck('crop_id')->unique()->count() }}</h4>
                                        <small class="text-muted">Different Crops</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-droplet text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold text-dark" id="total-soils">{{ $configs->pluck('soil_type_id')->unique()->count() }}</h4>
                                        <small class="text-muted">Soil Types</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="live-search" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="Search configurations by crop name, kebele, soil type, fertilizer, or year..."
                                           aria-label="Search configurations">
                                    <button class="btn btn-outline-secondary" type="button" id="clear-search">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-funnel text-muted"></i>
                                    </span>
                                    <select class="form-select border-start-0 ps-0" id="filter-column">
                                        <option value="all">All Columns</option>
                                        <option value="crop">Crop Name</option>
                                        <option value="kebele">Kebele</option>
                                        <option value="soil">Soil Type</option>
                                        <option value="fertilizer">Fertilizer</option>
                                        <option value="year">Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <span id="search-results-count">{{ $configs->count() }}</span> configurations found
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Configurations Table -->
                <div class="card shadow border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark">
                                <i class="bi bi-table text-success me-2"></i>
                                All Configurations
                                <span class="badge bg-success ms-2" id="table-count">{{ $configs->total() }}</span>
                            </h5>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-3" id="loading-indicator" style="display: none;">
                                    <i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-1"></i>
                                    Searching...
                                </small>
                                <button class="btn btn-sm btn-outline-secondary" id="refresh-table">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="configurations-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4">#</th>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar text-muted me-2"></i>
                                                Year
                                            </div>
                                        </th>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-tags text-muted me-2"></i>
                                                Crop Type
                                            </div>
                                        </th>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-seedling text-muted me-2"></i>
                                                Crop
                                            </div>
                                        </th>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-geo-alt text-muted me-2"></i>
                                                Kebele
                                            </div>
                                        </th>
                                        <th class="border-0 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="bi bi-speedometer2 text-muted me-2"></i>
                                                Seed Rate
                                            </div>
                                        </th>
                                        <th class="border-0 text-center">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <i class="bi bi-graph-up text-muted me-2"></i>
                                                Productivity
                                            </div>
                                        </th>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-droplet text-muted me-2"></i>
                                                Soil Type
                                            </div>
                                        </th>
                                        <th class="border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-flower1 text-muted me-2"></i>
                                                Fertilizer Requirements
                                            </div>
                                        </th>
                                        <th class="border-0 text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    @php
                                        // Group configurations by year, crop type, and crop
                                        $groupedConfigs = $configs->groupBy(['year', 'croptype_id', 'crop_id']);
                                        $counter = 0;
                                    @endphp
                                    
                                    @forelse($groupedConfigs as $year => $cropTypes)
                                        @php $yearFirst = true; @endphp
                                        @foreach($cropTypes as $cropTypeId => $crops)
                                            @php $cropTypeFirst = true; @endphp
                                            @foreach($crops as $cropId => $yearCropConfigs)
                                                @php $cropFirst = true; @endphp
                                                @foreach($yearCropConfigs as $config)
                                                    @php
                                                        $fertilizerRequirements = $config->fertilizer_requirements ?? [];
                                                        if (is_string($fertilizerRequirements)) {
                                                            $fertilizerRequirements = json_decode($fertilizerRequirements, true) ?? [];
                                                        }
                                                        $fertilizerText = collect($fertilizerRequirements)
                                                            ->map(function($fert) {
                                                                $name = $fert['fertilizer_name'] ?? 'Unknown';
                                                                $rate = $fert['rate_kg_per_ha'] ?? 0;
                                                                return "{$name} ({$rate} kg/ha)";
                                                            })
                                                            ->join(', ');
                                                    @endphp
                                                    <tr class="border-bottom config-row group-row" 
                                                        data-year="{{ $year }}"
                                                        data-croptype="{{ strtolower($config->croptype->name ?? '') }}"
                                                        data-crop="{{ strtolower($config->crop->name) }}"
                                                        data-kebele="{{ strtolower($config->kebele->name) }}"
                                                        data-soil="{{ strtolower($config->soilType->name) }}"
                                                        data-fertilizer="{{ strtolower($fertilizerText) }}">
                                                        <td class="ps-4 fw-semibold text-muted">
                                                            {{ ($configs->currentPage() - 1) * $configs->perPage() + $counter + 1 }}
                                                        </td>
                                                        
                                                        <!-- Year Column with Grouping -->
                                                        <td>
                                                            @if($yearFirst)
                                                                <div class="year-group bg-primary bg-opacity-10 p-2 rounded text-center">
                                                                    <div class="fw-bold text-primary">{{ $year }} EC</div>
                                                                    <small class="text-muted">Year</small>
                                                                </div>
                                                                @php $yearFirst = false; @endphp
                                                            @endif
                                                        </td>
                                                        
                                                        <!-- Crop Type Column with Grouping -->
                                                        <td>
                                                            @if($cropTypeFirst)
                                                                <div class="croptype-group bg-warning bg-opacity-10 p-2 rounded">
                                                                    <div class="fw-bold text-warning">{{ $config->croptype->name ?? 'N/A' }}</div>
                                                                    <small class="text-muted">Crop Type</small>
                                                                </div>
                                                                @php $cropTypeFirst = false; @endphp
                                                            @endif
                                                        </td>
                                                        
                                                        <!-- Crop Column with Grouping -->
                                                        <td>
                                                            @if($cropFirst)
                                                                <div class="crop-group bg-success bg-opacity-10 p-2 rounded">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="bg-success bg-opacity-25 rounded-circle p-1 me-2">
                                                                            <i class="bi bi-seedling text-success"></i>
                                                                        </div>
                                                                        <div>
                                                                            <div class="fw-bold text-dark crop-name">{{ $config->crop->name }}</div>
                                                                            <small class="text-muted">Crop</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @php $cropFirst = false; @endphp
                                                            @endif
                                                        </td>
                                                        
                                                        <!-- Kebele Column -->
                                                        <td>
                                                            <div class="fw-medium text-dark kebele-name">{{ $config->kebele->name }}</div>
                                                            <small class="text-muted">Kebele</small>
                                                        </td>
                                                        
                                                        <!-- Seed Rate Column -->
                                                        <td class="text-center">
                                                            <div class="fw-medium text-dark">
                                                                @if($config->seed_rate_kg_per_ha)
                                                                    {{ number_format($config->seed_rate_kg_per_ha, 2) }}
                                                                    <small class="text-muted d-block">kg/ha</small>
                                                                @else
                                                                    <span class="text-muted">Not set</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Productivity Column -->
                                                        <td class="text-center">
                                                            <div class="fw-medium text-dark">
                                                                @if($config->productivity_per_ha)
                                                                    {{ number_format($config->productivity_per_ha, 2) }}
                                                                    <small class="text-muted d-block">qt/ha</small>
                                                                @else
                                                                    <span class="text-muted">Not set</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Soil Type Column -->
                                                        <td>
                                                            <div class="fw-medium text-dark soil-name">{{ $config->soilType->name }}</div>
                                                            <small class="text-muted">Soil Type</small>
                                                        </td>
                                                        
                                                        <!-- Fertilizer Requirements Column -->
                                                        <td>
                                                            <div class="fertilizer-content">
                                                                @if(!empty($fertilizerRequirements))
                                                                    <div class="fw-medium text-dark fertilizer-text">
                                                                        @foreach(collect($fertilizerRequirements)->take(2) as $fert)
                                                                            <div class="mb-1">
                                                                                <small class="text-success fw-semibold">
                                                                                    {{ $fert['fertilizer_name'] ?? 'Unknown' }}:
                                                                                </small>
                                                                                <span class="text-dark">
                                                                                    {{ number_format($fert['rate_kg_per_ha'] ?? 0, 2) }} kg/ha
                                                                                </span>
                                                                            </div>
                                                                        @endforeach
                                                                        @if(count($fertilizerRequirements) > 2)
                                                                            <button class="btn btn-sm btn-outline-primary mt-1 view-more-fertilizers" 
                                                                                    data-bs-toggle="modal" 
                                                                                    data-bs-target="#fertilizerModal"
                                                                                    data-fertilizers='@json($fertilizerRequirements)'
                                                                                    data-crop="{{ $config->crop->name }}"
                                                                                    data-kebele="{{ $config->kebele->name }}">
                                                                                +{{ count($fertilizerRequirements) - 2 }} more
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">No fertilizers</span>
                                                                @endif
                                                            </div>
                                                            <small class="text-muted">Fertilizers</small>
                                                        </td>
                                                        
                                                        <!-- Actions Column -->
                                                        <td class="text-end pe-4">
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('crop-configurations.edit', $config->id) }}" 
                                                                   class="btn btn-sm btn-outline-warning rounded-pill me-2"
                                                                   data-bs-toggle="tooltip" title="Edit Configuration">
                                                                    <i class="bi bi-pencil"></i>
                                                                    <span class="d-none d-md-inline">Edit</span>
                                                                </a>
                                                                <form action="{{ route('crop-configurations.destroy', $config->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="btn btn-sm btn-outline-danger rounded-pill"
                                                                            onclick="return confirm('Are you sure you want to delete this configuration?')"
                                                                            data-bs-toggle="tooltip" title="Delete Configuration">
                                                                        <i class="bi bi-trash"></i>
                                                                        <span class="d-none d-md-inline">Delete</span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php $counter++; @endphp
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @empty
                                        <tr id="no-results-row">
                                            <td colspan="10" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                                    <h5 class="text-muted mt-3">No Configurations Found</h5>
                                                    <p class="text-muted mb-4">Get started by creating your first crop configuration</p>
                                                    <a href="{{ route('crop-configurations.create') }}" class="btn btn-success rounded-pill px-4">
                                                        <i class="bi bi-plus-circle me-2"></i>Create Configuration
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Table Footer with Pagination -->
                    @if($configs->count() > 0)
                        <div class="card-footer bg-white py-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Showing <span id="showing-from">{{ $configs->firstItem() }}</span> to 
                                    <span id="showing-to">{{ $configs->lastItem() }}</span> of 
                                    <span id="showing-total">{{ $configs->total() }}</span> entries
                                </div>
                                <div>
                                    {{ $configs->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fertilizer Details Modal -->
    <div class="modal fade" id="fertilizerModal" tabindex="-1" aria-labelledby="fertilizerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fertilizerModalLabel">Fertilizer Requirements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong id="modal-crop-name"></strong> - <span id="modal-kebele-name"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fertilizer</th>
                                    <th class="text-end">Rate (kg/ha)</th>
                                </tr>
                            </thead>
                            <tbody id="modal-fertilizer-list">
                                <!-- Fertilizer list will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
        }
        
        .btn {
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }
        
        .table td {
            vertical-align: middle;
            border-color: #f8f9fa;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(25, 135, 84, 0.02);
        }
        
        .btn-group .btn {
            border-radius: 20px !important;
        }

        /* Grouping Styles */
        .group-row {
            border-left: 3px solid transparent;
        }
        
        .year-group {
            border-left: 3px solid #0d6efd;
            margin: -8px 0;
        }
        
        .croptype-group {
            border-left: 3px solid #ffc107;
            margin: -8px 0;
        }
        
        .crop-group {
            border-left: 3px solid #198754;
            margin: -8px 0;
        }
        
        .group-row:hover .year-group,
        .group-row:hover .croptype-group,
        .group-row:hover .crop-group {
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }
        
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
        }
        
        .page-item.active .page-link {
            background-color: #198754;
            border-color: #198754;
        }

        /* Search highlight */
        .highlight {
            background-color: #fff3cd !important;
            border-left: 3px solid #ffc107;
        }
        
        .search-match {
            background-color: #ffc107;
            padding: 0.1rem 0.2rem;
            border-radius: 3px;
            font-weight: 600;
        }

        /* Fertilizer styles */
        .fertilizer-content {
            max-width: 200px;
        }
        
        .view-more-fertilizers {
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
        }

        /* Loading animation */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Group borders */
        .group-row:not(:first-child) {
            border-top: 1px solid #e9ecef;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Fertilizer modal functionality
            const fertilizerModal = document.getElementById('fertilizerModal');
            if (fertilizerModal) {
                fertilizerModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const fertilizers = JSON.parse(button.getAttribute('data-fertilizers'));
                    const cropName = button.getAttribute('data-crop');
                    const kebeleName = button.getAttribute('data-kebele');
                    
                    document.getElementById('modal-crop-name').textContent = cropName;
                    document.getElementById('modal-kebele-name').textContent = kebeleName;
                    
                    const fertilizerList = document.getElementById('modal-fertilizer-list');
                    fertilizerList.innerHTML = '';
                    
                    fertilizers.forEach(fert => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${fert.fertilizer_name || 'Unknown'}</td>
                            <td class="text-end">${Number(fert.rate_kg_per_ha || 0).toFixed(2)} kg/ha</td>
                        `;
                        fertilizerList.appendChild(row);
                    });
                });
            }

            // Search functionality
            const searchInput = document.getElementById('live-search');
            const clearSearchBtn = document.getElementById('clear-search');
            const filterColumn = document.getElementById('filter-column');
            const tableBody = document.getElementById('table-body');
            const configRows = document.querySelectorAll('.config-row');
            const noResultsRow = document.getElementById('no-results-row');
            const searchResultsCount = document.getElementById('search-results-count');
            const tableCount = document.getElementById('table-count');
            const loadingIndicator = document.getElementById('loading-indicator');
            const refreshBtn = document.getElementById('refresh-table');
            
            // Statistics elements
            const totalConfigs = document.getElementById('total-configs');
            const totalKebeles = document.getElementById('total-kebeles');
            const totalCrops = document.getElementById('total-crops');
            const totalSoils = document.getElementById('total-soils');
            
            // Showing elements
            const showingFrom = document.getElementById('showing-from');
            const showingTo = document.getElementById('showing-to');
            const showingTotal = document.getElementById('showing-total');

            let searchTimeout;

            // Search function
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const filterValue = filterColumn.value;
                
                loadingIndicator.style.display = 'inline-block';
                
                // Clear previous highlights
                document.querySelectorAll('.search-match').forEach(el => {
                    const parent = el.parentNode;
                    parent.replaceChild(document.createTextNode(el.textContent), el);
                    parent.normalize();
                });
                
                let visibleCount = 0;
                let foundKebeles = new Set();
                let foundCrops = new Set();
                let foundSoils = new Set();

                configRows.forEach(row => {
                    let shouldShow = false;
                    
                    if (searchTerm === '') {
                        shouldShow = true;
                    } else {
                        const year = row.getAttribute('data-year');
                        const cropType = row.getAttribute('data-croptype');
                        const cropName = row.querySelector('.crop-name')?.textContent.toLowerCase() || '';
                        const kebeleName = row.querySelector('.kebele-name')?.textContent.toLowerCase() || '';
                        const soilName = row.querySelector('.soil-name')?.textContent.toLowerCase() || '';
                        const fertilizerText = row.getAttribute('data-fertilizer');
                        
                        switch (filterValue) {
                            case 'crop':
                                shouldShow = cropName.includes(searchTerm);
                                break;
                            case 'kebele':
                                shouldShow = kebeleName.includes(searchTerm);
                                break;
                            case 'soil':
                                shouldShow = soilName.includes(searchTerm);
                                break;
                            case 'fertilizer':
                                shouldShow = fertilizerText.includes(searchTerm);
                                break;
                            case 'year':
                                shouldShow = year.includes(searchTerm);
                                break;
                            case 'all':
                            default:
                                shouldShow = year.includes(searchTerm) ||
                                           cropType.includes(searchTerm) ||
                                           cropName.includes(searchTerm) || 
                                           kebeleName.includes(searchTerm) || 
                                           soilName.includes(searchTerm) || 
                                           fertilizerText.includes(searchTerm);
                                break;
                        }
                        
                        // Highlight matching text
                        if (shouldShow && searchTerm) {
                            highlightMatches(row, searchTerm, filterValue);
                        }
                    }
                    
                    if (shouldShow) {
                        row.style.display = '';
                        visibleCount++;
                        
                        // Collect unique values for statistics
                        foundKebeles.add(row.getAttribute('data-kebele'));
                        foundCrops.add(row.getAttribute('data-crop'));
                        foundSoils.add(row.getAttribute('data-soil'));
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update UI
                updateSearchResults(visibleCount, foundKebeles.size, foundCrops.size, foundSoils.size);
                loadingIndicator.style.display = 'none';
            }
            
            // Highlight matching text
            function highlightMatches(row, searchTerm, filterValue) {
                const textElements = row.querySelectorAll('.crop-name, .kebele-name, .soil-name, .fertilizer-text');
                
                textElements.forEach(element => {
                    const text = element.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const originalText = element.textContent;
                        const newText = originalText.replace(regex, '<span class="search-match">$1</span>');
                        
                        if (newText !== originalText) {
                            element.innerHTML = newText;
                            row.classList.add('highlight');
                        }
                    }
                });
            }
            
            // Update search results and statistics
            function updateSearchResults(visibleCount, kebelesCount, cropsCount, soilsCount) {
                searchResultsCount.textContent = visibleCount;
                tableCount.textContent = visibleCount;
                
                // Update statistics
                totalConfigs.textContent = visibleCount;
                totalKebeles.textContent = kebelesCount;
                totalCrops.textContent = cropsCount;
                totalSoils.textContent = soilsCount;
                
                // Update showing text
                if (showingFrom && showingTo && showingTotal) {
                    showingFrom.textContent = visibleCount > 0 ? 1 : 0;
                    showingTo.textContent = visibleCount;
                    showingTotal.textContent = visibleCount;
                }
                
                // Show/hide no results message
                if (noResultsRow) {
                    if (visibleCount === 0 && searchInput.value.trim() !== '') {
                        noResultsRow.style.display = '';
                        noResultsRow.innerHTML = `
                            <td colspan="10" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-search display-1 text-muted"></i>
                                    <h5 class="text-muted mt-3">No matching configurations found</h5>
                                    <p class="text-muted mb-3">No results found for "${searchInput.value}"</p>
                                    <button class="btn btn-outline-secondary btn-sm" id="clear-search-from-results">
                                        <i class="bi bi-x me-1"></i>Clear search
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        // Add event listener to clear search button in no results
                        document.getElementById('clear-search-from-results')?.addEventListener('click', clearSearch);
                    } else if (visibleCount === 0) {
                        noResultsRow.style.display = '';
                    } else {
                        noResultsRow.style.display = 'none';
                    }
                }
            }
            
            // Clear search function
            function clearSearch() {
                searchInput.value = '';
                filterColumn.value = 'all';
                performSearch();
                searchInput.focus();
            }
            
            // Event listeners
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 300);
            });
            
            filterColumn.addEventListener('change', performSearch);
            
            clearSearchBtn.addEventListener('click', clearSearch);
            
            refreshBtn.addEventListener('click', function() {
                clearSearch();
                // Add refresh animation
                refreshBtn.classList.add('loading');
                setTimeout(() => {
                    refreshBtn.classList.remove('loading');
                }, 1000);
            });
            
            // Add animation to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
            
            // Initial search to set up statistics
            performSearch();
        });
    </script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>