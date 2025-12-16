<x-layout>
    <div class="content-header min-vh-100">
        <div class="container-fluid py-4 bg-light">
            <div class="row justify-content-center">
                <div class="col-12">
                
                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1 text-success fw-bold">Crop Configurations</h4>
                            <p class="text-muted mb-0">Manage crop-specific configurations across different kebeles</p>
                        </div>
                        <a href="{{ route('crop-configurations.create') }}" class="btn btn-success btn-sm rounded-pill px-4">
                            <i class="fas fa-plus-circle me-2"></i>Add New Configuration
                        </a>
                    </div>

                    <!-- Success Alert -->
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm d-flex align-items-center mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="fw-medium">{{ session('success') }}</div>
                        </div>
                    @endif

                     

                 

                    <!-- Configurations Table -->
                    <div class="card card-outline shadow">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-dark">
                                    <i class="fas fa-table text-success me-2"></i>
                                    All Configurations
                                    <span class="badge bg-success ms-2" id="table-count">{{ $configs->total() }}</span>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3" id="loading-indicator" style="display: none;">
                                        <i class="fas fa-sync-alt fa-spin me-1"></i>
                                        Searching...
                                    </small>
                                    <button class="btn btn-sm btn-outline-secondary" id="refresh-table">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0" id="configurations-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 ps-4">#</th>
                                            <th class="border-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-muted me-2"></i>
                                                    Year
                                                </div>
                                            </th>
                                            <th class="border-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-tags text-muted me-2"></i>
                                                    Crop Type
                                                </div>
                                            </th>
                                            <th class="border-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-seedling text-muted me-2"></i>
                                                    Crop
                                                </div>
                                            </th>
                                            <th class="border-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                    Kebele
                                                </div>
                                            </th>
                                            <th class="border-0 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-tachometer-alt text-muted me-2"></i>
                                                    Seed Rate
                                                </div>
                                            </th>
                                            <th class="border-0 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-chart-line text-muted me-2"></i>
                                                    Productivity
                                                </div>
                                            </th>
                                            <th class="border-0 text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        @php
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
                                                        <tr class="border-bottom config-row group-row" 
                                                            data-year="{{ $year }}"
                                                            data-croptype="{{ strtolower($config->croptype->name ?? '') }}"
                                                            data-crop="{{ strtolower($config->crop->name) }}"
                                                            data-kebele="{{ strtolower($config->kebele->name) }}">
                                                            <td class="ps-4 fw-semibold text-muted">
                                                                {{ ($configs->currentPage() - 1) * $configs->perPage() + $counter + 1 }}
                                                            </td>
                                                            <td>
                                                                @if($yearFirst)
                                                                    <div class="year-group bg-primary bg-opacity-10 p-2 rounded text-center">
                                                                        <div class="fw-bold text-primary">{{ $year }} EC</div>
                                                                        <small class="text-muted">Year</small>
                                                                    </div>
                                                                    @php $yearFirst = false; @endphp
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($cropTypeFirst)
                                                                    <div class="croptype-group bg-warning bg-opacity-10 p-2 rounded">
                                                                        <div class="fw-bold text-warning">{{ $config->croptype->name ?? 'N/A' }}</div>
                                                                        <small class="text-muted">Crop Type</small>
                                                                    </div>
                                                                    @php $cropTypeFirst = false; @endphp
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($cropFirst)
                                                                    <div class="crop-group bg-success bg-opacity-10 p-2 rounded">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="bg-success bg-opacity-25 rounded-circle p-1 me-2">
                                                                                <i class="fas fa-seedling text-success"></i>
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
                                                            <td>
                                                                <div class="fw-medium text-dark kebele-name">{{ $config->kebele->name }}</div>
                                                                <small class="text-muted">Kebele</small>
                                                            </td>
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
                                                            <td class="text-end pe-4">
                                                                <div class="btn-group" role="group">
                                                                    <a href="{{ route('crop-configurations.edit', $config->id) }}" 
                                                                       class="btn btn-sm btn-outline-warning rounded-pill me-2"
                                                                       data-bs-toggle="tooltip" title="Edit Configuration">
                                                                        <i class="fas fa-pencil-alt"></i>
                                                                        <span class="d-none d-md-inline">Edit</span>
                                                                    </a>
                                                                    <form action="{{ route('crop-configurations.destroy', $config->id) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" 
                                                                                class="btn btn-sm btn-outline-danger rounded-pill"
                                                                                onclick="return confirm('Are you sure you want to delete this configuration?')"
                                                                                data-bs-toggle="tooltip" title="Delete Configuration">
                                                                            <i class="fas fa-trash-alt"></i>
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
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="py-4">
                                                        <i class="fas fa-inbox display-1 text-muted"></i>
                                                        <h5 class="text-muted mt-3">No Configurations Found</h5>
                                                        <p class="text-muted mb-4">Get started by creating your first crop configuration</p>
                                                        <a href="{{ route('crop-configurations.create') }}" class="btn btn-success rounded-pill px-4">
                                                            <i class="fas fa-plus-circle me-2"></i>Create Configuration
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
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
    </div>
</x-layout>
