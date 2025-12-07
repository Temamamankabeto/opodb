<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12">
                        <h2 class="mb-0">
                            <i class="fas fa-pencil-square text-primary mr-2"></i>
                            Edit Building Power Institution Plan
                        </h2>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sector.building-power-institution-plans.update', $plan->id) }}" method="POST" id="mainForm">
                    @csrf
                    @method('PUT')

                    <!-- Hidden JSON fields -->
                    <input type="hidden" name="new_building_data" id="newBuildingDataJson">
                    <input type="hidden" name="improvement_data" id="improvementDataJson">

                    <!-- Year Selection -->
                    <div class="card card-primary mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Year Selection</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control" id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ old('year', $plan->year) == $year ? 'selected' : '' }}>
                                                    {{ $year }} EC
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('year')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Institution <span class="text-danger">*</span></label>
                                        <select name="institution_id" class="form-control" id="institutionSelect" required>
                                            <option value="">-- Select Institution --</option>
                                            @foreach ($institutions as $institution)
                                                <option value="{{ $institution->id }}" {{ old('institution_id', $plan->institution_id) == $institution->id ? 'selected' : '' }}>
                                                    {{ $institution->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('institution_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New Building Data -->
                    <div class="card card-info mb-3" id="newBuildingCard">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>New Building Construction</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info py-2 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Edit new building construction data for the selected institution.
                            </div>

                            <div id="newBuildingsContainer">
                                @if (!empty($plan->new_building_data) && count($plan->new_building_data) > 0)
                                    @foreach ($plan->new_building_data as $index => $building)
                                        <div class="card border mb-3 new-building-item" data-index="{{ $index }}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="small font-weight-bold text-primary">
                                                            <i class="fas fa-building mr-1"></i>
                                                            Building Entry #{{ $index + 1 }}
                                                        </label>
                                                    </div>
                                                    
                                                    <!-- Public Section -->
                                                    <div class="col-12 border-bottom pb-2 mb-2">
                                                        <h6 class="text-success mb-2">Public Sector</h6>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control new-building-public-number"
                                                                        value="{{ old("new_building_data.$index.public_number", $building['public_number'] ?? '') }}"
                                                                        placeholder="0" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" class="form-control new-building-public-cost"
                                                                        value="{{ old("new_building_data.$index.public_cost", $building['public_cost'] ?? '') }}"
                                                                        placeholder="0.00" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Total Cost (ETB)</label>
                                                                    <input type="text" class="form-control new-building-public-total-cost"
                                                                        placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Private Section -->
                                                    <div class="col-12 border-bottom pb-2 mb-2">
                                                        <h6 class="text-warning mb-2">Private Sector</h6>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control new-building-private-number"
                                                                        value="{{ old("new_building_data.$index.private_number", $building['private_number'] ?? '') }}"
                                                                        placeholder="0" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" class="form-control new-building-private-cost"
                                                                        value="{{ old("new_building_data.$index.private_cost", $building['private_cost'] ?? '') }}"
                                                                        placeholder="0.00" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Total Cost (ETB)</label>
                                                                    <input type="text" class="form-control new-building-private-total-cost"
                                                                        placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Totals Section -->
                                                    <div class="col-12 bg-light p-2 rounded mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-0">
                                                                    <label class="small font-weight-bold">Total Buildings</label>
                                                                    <input type="text" class="form-control new-building-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-0">
                                                                    <label class="small font-weight-bold">Average Cost (ETB)</label>
                                                                    <input type="text" class="form-control new-building-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-0">
                                                                    <label class="small font-weight-bold">Grand Total (ETB)</label>
                                                                    <input type="text" class="form-control new-building-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="small">Project Description <span class="text-danger">*</span></label>
                                                            <textarea class="form-control new-building-description" rows="2"
                                                                placeholder="Describe the construction project..." required>{{ old("new_building_data.$index.description", $building['description'] ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    @if ($index > 0)
                                                        <div class="col-12 text-right">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger remove-new-building">
                                                                <i class="fas fa-trash mr-1"></i>Remove Building
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card border mb-3 new-building-item" data-index="0">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Public Section -->
                                                <div class="col-12 border-bottom pb-2 mb-2">
                                                    <h6 class="text-success mb-2">Public Sector</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control new-building-public-number"
                                                                    value="{{ old('new_building_data.0.public_number', '') }}" placeholder="0" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control new-building-public-cost"
                                                                    value="{{ old('new_building_data.0.public_cost', '') }}" placeholder="0.00" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Total Cost (ETB)</label>
                                                                <input type="text" class="form-control new-building-public-total-cost"
                                                                    placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Private Section -->
                                                <div class="col-12 border-bottom pb-2 mb-2">
                                                    <h6 class="text-warning mb-2">Private Sector</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control new-building-private-number"
                                                                    value="{{ old('new_building_data.0.private_number', '') }}" placeholder="0" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control new-building-private-cost"
                                                                    value="{{ old('new_building_data.0.private_cost', '') }}" placeholder="0.00" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Total Cost (ETB)</label>
                                                                <input type="text" class="form-control new-building-private-total-cost"
                                                                    placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Totals Section -->
                                                <div class="col-12 bg-light p-2 rounded mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">Total Buildings</label>
                                                                <input type="text" class="form-control new-building-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">Average Cost (ETB)</label>
                                                                <input type="text" class="form-control new-building-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">Grand Total (ETB)</label>
                                                                <input type="text" class="form-control new-building-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="small">Project Description <span class="text-danger">*</span></label>
                                                        <textarea class="form-control new-building-description" rows="2"
                                                            placeholder="Describe the construction project..." required>{{ old('new_building_data.0.description', '') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addNewBuilding">
                                    <i class="fas fa-plus-circle mr-1"></i>Add Another Building
                                </button>
                            </div>

                            @error('new_building_data')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Improvement Data -->
                    <div class="card card-warning mb-3" id="improvementCard">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tools mr-2"></i>Existing Building Improvements</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info py-2 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Edit improvement projects on existing buildings for the selected institution.
                            </div>

                            <div id="improvementsContainer">
                                @if (!empty($plan->improvement_data) && count($plan->improvement_data) > 0)
                                    @foreach ($plan->improvement_data as $index => $improvement)
                                        <div class="card border mb-3 improvement-item" data-index="{{ $index }}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="small font-weight-bold text-warning">
                                                            <i class="fas fa-tools mr-1"></i>
                                                            #{{ $index + 1 }}
                                                        </label>
                                                    </div>
                                                    
                                                    <!-- Public Section -->
                                                    <div class="col-12 border-bottom pb-2 mb-2">
                                                        <h6 class="text-success mb-2">Public Sector</h6>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Number of Improvements <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control improvement-public-number"
                                                                        value="{{ old("improvement_data.$index.public_number", $improvement['public_number'] ?? '') }}"
                                                                        placeholder="0" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" class="form-control improvement-public-cost"
                                                                        value="{{ old("improvement_data.$index.public_cost", $improvement['public_cost'] ?? '') }}"
                                                                        placeholder="0.00" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Total Cost (ETB)</label>
                                                                    <input type="text" class="form-control improvement-public-total-cost"
                                                                        placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Private Section -->
                                                    <div class="col-12 border-bottom pb-2 mb-2">
                                                        <h6 class="text-warning mb-2">Private Sector</h6>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Number of Improvements <span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control improvement-private-number"
                                                                        value="{{ old("improvement_data.$index.private_number", $improvement['private_number'] ?? '') }}"
                                                                        placeholder="0" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" class="form-control improvement-private-cost"
                                                                        value="{{ old("improvement_data.$index.private_cost", $improvement['private_cost'] ?? '') }}"
                                                                        placeholder="0.00" min="0" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-2">
                                                                    <label class="small">Total Cost (ETB)</label>
                                                                    <input type="text" class="form-control improvement-private-total-cost"
                                                                        placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Totals Section -->
                                                    <div class="col-12 bg-light p-2 rounded mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-0">
                                                                    <label class="small font-weight-bold">Total Improvements</label>
                                                                    <input type="text" class="form-control improvement-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-0">
                                                                    <label class="small font-weight-bold">Average Cost (ETB)</label>
                                                                    <input type="text" class="form-control improvement-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-0">
                                                                    <label class="small font-weight-bold">Grand Total (ETB)</label>
                                                                    <input type="text" class="form-control improvement-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="small">Improvement Description <span class="text-danger">*</span></label>
                                                            <textarea class="form-control improvement-description" rows="2" placeholder="Describe the improvement work..."
                                                                required>{{ old("improvement_data.$index.description", $improvement['description'] ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    @if ($index > 0)
                                                        <div class="col-12 text-right">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger remove-improvement">
                                                                <i class="fas fa-trash mr-1"></i>Remove Improvement
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="card border mb-3 improvement-item" data-index="0">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Public Section -->
                                                <div class="col-12 border-bottom pb-2 mb-2">
                                                    <h6 class="text-success mb-2">Public Sector</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Number of Improvements <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control improvement-public-number"
                                                                    value="{{ old('improvement_data.0.public_number', '') }}" placeholder="0" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control improvement-public-cost"
                                                                    value="{{ old('improvement_data.0.public_cost', '') }}" placeholder="0.00" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Total Cost (ETB)</label>
                                                                <input type="text" class="form-control improvement-public-total-cost"
                                                                    placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Private Section -->
                                                <div class="col-12 border-bottom pb-2 mb-2">
                                                    <h6 class="text-warning mb-2">Private Sector</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Number of Improvements <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control improvement-private-number"
                                                                    value="{{ old('improvement_data.0.private_number', '') }}" placeholder="0" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control improvement-private-cost"
                                                                    value="{{ old('improvement_data.0.private_cost', '') }}" placeholder="0.00" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-2">
                                                                <label class="small">Total Cost (ETB)</label>
                                                                <input type="text" class="form-control improvement-private-total-cost"
                                                                    placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Totals Section -->
                                                <div class="col-12 bg-light p-2 rounded mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">Total Improvements</label>
                                                                <input type="text" class="form-control improvement-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">Average Cost (ETB)</label>
                                                                <input type="text" class="form-control improvement-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-0">
                                                                <label class="small font-weight-bold">Grand Total (ETB)</label>
                                                                <input type="text" class="form-control improvement-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="small">Improvement Description <span class="text-danger">*</span></label>
                                                        <textarea class="form-control improvement-description" rows="2" placeholder="Describe the improvement work..."
                                                            required>{{ old('improvement_data.0.description', '') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-sm btn-outline-warning" id="addImprovement">
                                    <i class="fas fa-plus-circle mr-1"></i>Add Another Improvement
                                </button>
                            </div>

                            @error('improvement_data')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card card-secondary mb-3">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calculator mr-2"></i>Financial Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-success mb-1">Public Total</h6>
                                        <h4 class="text-success mb-0" id="publicTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="publicCount">0 projects</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-warning mb-1">Private Total</h6>
                                        <h4 class="text-warning mb-0" id="privateTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="privateCount">0 projects</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-info mb-1">New Construction</h6>
                                        <h4 class="text-info mb-0" id="newBuildingsTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="newBuildingsCount">0 buildings</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-warning mb-1">Improvements</h6>
                                        <h4 class="text-warning mb-0" id="improvementsTotal">0.00 ETB</h4>
                                        <small class="text-muted" id="improvementsCount">0 improvements</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="border rounded p-3 bg-success text-white text-center">
                                        <h5 class="mb-1">Grand Total</h5>
                                        <h2 class="mb-0" id="grandTotal">0.00 ETB</h2>
                                        <small id="totalProjects">0 total projects</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-footer clearfix">
                                    <a href="{{ route('sector.building-power-institution-plans.index') }}" class="btn btn-default float-left">
                                        <i class="fas fa-arrow-left mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary float-right" id="submitBtn">
                                        <i class="fas fa-save mr-2"></i>Update Building Plan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- KEEP ALL ORIGINAL JAVASCRIPT EXACTLY AS IS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let newBuildingIndex = {{ !empty($plan->new_building_data) ? count($plan->new_building_data) : 1 }};
            let improvementIndex = {{ !empty($plan->improvement_data) ? count($plan->improvement_data) : 1 }};

            // Add new building entry
            document.getElementById('addNewBuilding').addEventListener('click', () => {
                const container = document.getElementById('newBuildingsContainer');
                const newItem = document.createElement('div');
                newItem.className = 'card border mb-3 new-building-item';
                newItem.setAttribute('data-index', newBuildingIndex);

                newItem.innerHTML = `
                    <div class="card-body">
                        <div class="row">
                            <!-- Public Section -->
                            <div class="col-12 border-bottom pb-2 mb-2">
                                <h6 class="text-success mb-2">Public Sector</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control new-building-public-number" 
                                                   placeholder="0" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control new-building-public-cost" 
                                                   placeholder="0.00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Total Cost (ETB)</label>
                                            <input type="text" class="form-control new-building-public-total-cost" 
                                                   placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Private Section -->
                            <div class="col-12 border-bottom pb-2 mb-2">
                                <h6 class="text-warning mb-2">Private Sector</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Number of Buildings <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control new-building-private-number" 
                                                   placeholder="0" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Cost per Building (ETB) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control new-building-private-cost" 
                                                   placeholder="0.00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Total Cost (ETB)</label>
                                            <input type="text" class="form-control new-building-private-total-cost" 
                                                   placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals Section -->
                            <div class="col-12 bg-light p-2 rounded mb-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Total Buildings</label>
                                            <input type="text" class="form-control new-building-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Average Cost (ETB)</label>
                                            <input type="text" class="form-control new-building-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Grand Total (ETB)</label>
                                            <input type="text" class="form-control new-building-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="small">Project Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control new-building-description" 
                                              rows="2" 
                                              placeholder="Describe the construction project..." 
                                              required></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-new-building">
                                    <i class="fas fa-trash mr-1"></i>Remove Building
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(newItem);
                newBuildingIndex++;
                attachEventListeners();
                updateFinancialSummary();
            });

            // Add new improvement entry
            document.getElementById('addImprovement').addEventListener('click', () => {
                const container = document.getElementById('improvementsContainer');
                const newItem = document.createElement('div');
                newItem.className = 'card border mb-3 improvement-item';
                newItem.setAttribute('data-index', improvementIndex);

                newItem.innerHTML = `
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="small font-weight-bold text-warning">
                                    <i class="fas fa-tools mr-1"></i>
                                    Improvement Entry #${improvementIndex + 1}
                                </label>
                            </div>
                            
                            <!-- Public Section -->
                            <div class="col-12 border-bottom pb-2 mb-2">
                                <h6 class="text-success mb-2">Public Sector</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Number of Improvements <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control improvement-public-number" 
                                                   placeholder="0" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control improvement-public-cost" 
                                                   placeholder="0.00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Total Cost (ETB)</label>
                                            <input type="text" class="form-control improvement-public-total-cost" 
                                                   placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Private Section -->
                            <div class="col-12 border-bottom pb-2 mb-2">
                                <h6 class="text-warning mb-2">Private Sector</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Number of Improvements <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control improvement-private-number" 
                                                   placeholder="0" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Cost per Improvement (ETB) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control improvement-private-cost" 
                                                   placeholder="0.00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label class="small">Total Cost (ETB)</label>
                                            <input type="text" class="form-control improvement-private-total-cost" 
                                                   placeholder="0.00" readonly style="background-color: #f8f9fa;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals Section -->
                            <div class="col-12 bg-light p-2 rounded mb-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Total Improvements</label>
                                            <input type="text" class="form-control improvement-total-number" placeholder="0" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Average Cost (ETB)</label>
                                            <input type="text" class="form-control improvement-average-cost" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold">Grand Total (ETB)</label>
                                            <input type="text" class="form-control improvement-grand-total" placeholder="0.00" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="small">Improvement Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control improvement-description" 
                                              rows="2" 
                                              placeholder="Describe the improvement work..." 
                                              required></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-improvement">
                                    <i class="fas fa-trash mr-1"></i>Remove Improvement
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(newItem);
                improvementIndex++;
                attachEventListeners();
                updateFinancialSummary();
            });

            // Remove building entry
            function attachEventListeners() {
                document.querySelectorAll('.remove-new-building').forEach(button => {
                    button.addEventListener('click', function() {
                        if (document.querySelectorAll('.new-building-item').length > 1) {
                            this.closest('.new-building-item').remove();
                            reindexBuildings();
                            updateFinancialSummary();
                        } else {
                            alert('At least one building entry is required.');
                        }
                    });
                });

                document.querySelectorAll('.remove-improvement').forEach(button => {
                    button.addEventListener('click', function() {
                        if (document.querySelectorAll('.improvement-item').length > 1) {
                            this.closest('.improvement-item').remove();
                            reindexImprovements();
                            updateFinancialSummary();
                        } else {
                            alert('At least one improvement entry is required.');
                        }
                    });
                });

                // Add input event listeners for calculations
                document.querySelectorAll('.new-building-public-number, .new-building-public-cost, .new-building-private-number, .new-building-private-cost').forEach(input => {
                    input.addEventListener('input', updateNewBuildingCalculations);
                });

                document.querySelectorAll('.improvement-public-number, .improvement-public-cost, .improvement-private-number, .improvement-private-cost').forEach(input => {
                    input.addEventListener('input', updateImprovementCalculations);
                });
            }

            function reindexBuildings() {
                document.querySelectorAll('.new-building-item').forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    const label = item.querySelector('.small.font-weight-bold');
                    label.innerHTML = `<i class="fas fa-building mr-1"></i>Building Entry #${index + 1}`;
                });
                newBuildingIndex = document.querySelectorAll('.new-building-item').length;
            }

            function reindexImprovements() {
                document.querySelectorAll('.improvement-item').forEach((item, index) => {
                    item.setAttribute('data-index', index);
                    const label = item.querySelector('.small.font-weight-bold');
                    label.innerHTML = `<i class="fas fa-tools mr-1"></i>Improvement Entry #${index + 1}`;
                });
                improvementIndex = document.querySelectorAll('.improvement-item').length;
            }

            function updateNewBuildingCalculations(e) {
                const buildingItem = e.target.closest('.new-building-item');
                const publicNumber = parseFloat(buildingItem.querySelector('.new-building-public-number').value) || 0;
                const publicCost = parseFloat(buildingItem.querySelector('.new-building-public-cost').value) || 0;
                const privateNumber = parseFloat(buildingItem.querySelector('.new-building-private-number').value) || 0;
                const privateCost = parseFloat(buildingItem.querySelector('.new-building-private-cost').value) || 0;

                const publicTotal = publicNumber * publicCost;
                const privateTotal = privateNumber * privateCost;
                const totalNumber = publicNumber + privateNumber;
                const grandTotal = publicTotal + privateTotal;
                const averageCost = totalNumber > 0 ? grandTotal / totalNumber : 0;

                buildingItem.querySelector('.new-building-public-total-cost').value = publicTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                buildingItem.querySelector('.new-building-private-total-cost').value = privateTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                buildingItem.querySelector('.new-building-total-number').value = totalNumber;
                buildingItem.querySelector('.new-building-average-cost').value = averageCost.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                buildingItem.querySelector('.new-building-grand-total').value = grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                updateFinancialSummary();
            }

            function updateImprovementCalculations(e) {
                const improvementItem = e.target.closest('.improvement-item');
                const publicNumber = parseFloat(improvementItem.querySelector('.improvement-public-number').value) || 0;
                const publicCost = parseFloat(improvementItem.querySelector('.improvement-public-cost').value) || 0;
                const privateNumber = parseFloat(improvementItem.querySelector('.improvement-private-number').value) || 0;
                const privateCost = parseFloat(improvementItem.querySelector('.improvement-private-cost').value) || 0;

                const publicTotal = publicNumber * publicCost;
                const privateTotal = privateNumber * privateCost;
                const totalNumber = publicNumber + privateNumber;
                const grandTotal = publicTotal + privateTotal;
                const averageCost = totalNumber > 0 ? grandTotal / totalNumber : 0;

                improvementItem.querySelector('.improvement-public-total-cost').value = publicTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                improvementItem.querySelector('.improvement-private-total-cost').value = privateTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                improvementItem.querySelector('.improvement-total-number').value = totalNumber;
                improvementItem.querySelector('.improvement-average-cost').value = averageCost.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                improvementItem.querySelector('.improvement-grand-total').value = grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                updateFinancialSummary();
            }

            function updateFinancialSummary() {
                let publicTotal = 0;
                let privateTotal = 0;
                let newBuildingsTotal = 0;
                let improvementsTotal = 0;
                let validNewBuildingsCount = 0;
                let validImprovementsCount = 0;
                let publicProjectsCount = 0;
                let privateProjectsCount = 0;

                // Calculate new buildings totals
                document.querySelectorAll('.new-building-item').forEach(item => {
                    const publicNumber = parseFloat(item.querySelector('.new-building-public-number').value) || 0;
                    const publicCost = parseFloat(item.querySelector('.new-building-public-cost').value) || 0;
                    const privateNumber = parseFloat(item.querySelector('.new-building-private-number').value) || 0;
                    const privateCost = parseFloat(item.querySelector('.new-building-private-cost').value) || 0;
                    const description = item.querySelector('.new-building-description').value;

                    if (publicNumber && publicCost && privateNumber && privateCost && description) {
                        const publicSubtotal = publicNumber * publicCost;
                        const privateSubtotal = privateNumber * privateCost;
                        
                        publicTotal += publicSubtotal;
                        privateTotal += privateSubtotal;
                        newBuildingsTotal += publicSubtotal + privateSubtotal;
                        validNewBuildingsCount++;
                        
                        if (publicNumber > 0) publicProjectsCount++;
                        if (privateNumber > 0) privateProjectsCount++;
                    }
                });

                // Calculate improvements totals
                document.querySelectorAll('.improvement-item').forEach(item => {
                    const publicNumber = parseFloat(item.querySelector('.improvement-public-number').value) || 0;
                    const publicCost = parseFloat(item.querySelector('.improvement-public-cost').value) || 0;
                    const privateNumber = parseFloat(item.querySelector('.improvement-private-number').value) || 0;
                    const privateCost = parseFloat(item.querySelector('.improvement-private-cost').value) || 0;
                    const description = item.querySelector('.improvement-description').value;

                    if (publicNumber && publicCost && privateNumber && privateCost && description) {
                        const publicSubtotal = publicNumber * publicCost;
                        const privateSubtotal = privateNumber * privateCost;
                        
                        publicTotal += publicSubtotal;
                        privateTotal += privateSubtotal;
                        improvementsTotal += publicSubtotal + privateSubtotal;
                        validImprovementsCount++;
                        
                        if (publicNumber > 0) publicProjectsCount++;
                        if (privateNumber > 0) privateProjectsCount++;
                    }
                });

                const grandTotal = publicTotal + privateTotal;
                const totalProjects = validNewBuildingsCount + validImprovementsCount;

                // Update displays
                document.getElementById('publicTotal').textContent = publicTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('privateTotal').textContent = privateTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('newBuildingsTotal').textContent = newBuildingsTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('improvementsTotal').textContent = improvementsTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ETB';

                document.getElementById('publicCount').textContent = `${publicProjectsCount} project${publicProjectsCount !== 1 ? 's' : ''}`;
                document.getElementById('privateCount').textContent = `${privateProjectsCount} project${privateProjectsCount !== 1 ? 's' : ''}`;
                document.getElementById('newBuildingsCount').textContent = `${validNewBuildingsCount} building${validNewBuildingsCount !== 1 ? 's' : ''}`;
                document.getElementById('improvementsCount').textContent = `${validImprovementsCount} improvement${validImprovementsCount !== 1 ? 's' : ''}`;
                document.getElementById('totalProjects').textContent = `${totalProjects} total project${totalProjects !== 1 ? 's' : ''}`;
            }

            // Form submission handler
            document.getElementById('mainForm').addEventListener('submit', function(e) {
                // Prevent default submission
                e.preventDefault();

                // Collect new building data
                const newBuildingData = [];
                document.querySelectorAll('.new-building-item').forEach((item, index) => {
                    const publicNumber = item.querySelector('.new-building-public-number').value;
                    const publicCost = item.querySelector('.new-building-public-cost').value;
                    const privateNumber = item.querySelector('.new-building-private-number').value;
                    const privateCost = item.querySelector('.new-building-private-cost').value;
                    const description = item.querySelector('.new-building-description').value;

                    if (publicNumber && publicCost && privateNumber && privateCost && description) {
                        newBuildingData.push({
                            name: "{{ $plan->institution->name }}",
                            public_number: parseInt(publicNumber),
                            private_number: parseInt(privateNumber),
                            public_cost: parseFloat(publicCost),
                            private_cost: parseFloat(privateCost),
                            description: description
                        });
                    }
                });

                // Collect improvement data
                const improvementData = [];
                document.querySelectorAll('.improvement-item').forEach((item, index) => {
                    const publicNumber = item.querySelector('.improvement-public-number').value;
                    const publicCost = item.querySelector('.improvement-public-cost').value;
                    const privateNumber = item.querySelector('.improvement-private-number').value;
                    const privateCost = item.querySelector('.improvement-private-cost').value;
                    const description = item.querySelector('.improvement-description').value;

                    if (publicNumber && publicCost && privateNumber && privateCost && description) {
                        improvementData.push({
                            name: "{{ $plan->institution->name }}",
                            public_number: parseInt(publicNumber),
                            private_number: parseInt(privateNumber),
                            public_cost: parseFloat(publicCost),
                            private_cost: parseFloat(privateCost),
                            description: description
                        });
                    }
                });

                // Validate at least one building entry
                if (newBuildingData.length === 0) {
                    alert('Please add at least one building entry.');
                    return;
                }

                // Set the JSON data to hidden fields
                document.getElementById('newBuildingDataJson').value = JSON.stringify(newBuildingData);
                document.getElementById('improvementDataJson').value = JSON.stringify(improvementData);

                // Now submit the form
                this.submit();
            });

            // Initialize calculations on page load
            function initializeCalculations() {
                document.querySelectorAll('.new-building-item').forEach(item => {
                    updateNewBuildingCalculations({ target: item });
                });

                document.querySelectorAll('.improvement-item').forEach(item => {
                    updateImprovementCalculations({ target: item });
                });

                updateFinancialSummary();
            }

            // Initialize everything
            attachEventListeners();
            initializeCalculations();
        });
    </script>

    <style>
        .new-building-item,
        .improvement-item {
            transition: all 0.3s ease;
        }

        .new-building-item:hover,
        .improvement-item:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        .btn:disabled {
            cursor: not-allowed;
        }

        .card-header {
            border-bottom: none;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .is-valid {
            border-color: #198754 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .border-bottom {
            border-bottom: 2px solid #dee2e6 !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-warning {
            color: #fd7e14 !important;
        }
    </style>
</x-layout>