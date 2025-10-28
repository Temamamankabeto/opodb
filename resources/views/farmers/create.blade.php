<x-layout>
<div class="container py-5">
    <h2 class="fw-bold text-success mb-4">
        <i class="bi bi-person-plus"></i> Add New Farmer
    </h2>

    <form action="{{ route('farmers.store') }}" method="POST" class="card p-4 shadow-sm border-0 rounded-3">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Farmer Name</label>
                <input type="text" name="farmer_name" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Gender</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" name="gender" value="Male" class="form-check-input" required>
                    <label class="form-check-label">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="gender" value="Female" class="form-check-input">
                    <label class="form-check-label">Female</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Phone Number</label>
                <input type="text" name="phone_number" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Region</label>
                <input type="text" name="region" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Zone</label>
                <select name="zone_id" class="form-select" required>
                    <option value="">Select Zone</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Woreda</label>
                <select name="woreda_id" class="form-select" required>
                    <option value="">Select Woreda</option>
                    @foreach($woredas as $woreda)
                        <option value="{{ $woreda->id }}">{{ $woreda->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Kebele</label>
                <select name="kebele_id" class="form-select" required>
                    <option value="">Select Kebele</option>
                    @foreach($kebeles as $kebele)
                        <option value="{{ $kebele->id }}">{{ $kebele->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Union</label>
                <select name="union_id" class="form-select" required>
                    <option value="">Select Union</option>
                    @foreach($unions as $union)
                        <option value="{{ $union->id }}">{{ $union->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Association</label>
                <select name="association_id" class="form-select" required>
                    <option value="">Select Association</option>
                    @foreach($associations as $association)
                        <option value="{{ $association->id }}">{{ $association->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">House Number</label>
                <input type="text" name="house_number" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Field Description</label>
                <input type="text" name="field_description" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Landholding Size</label>
                <input type="text" name="landholding_size" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">GPS Coordinates (optional)</label>
            <input type="text" name="gps_coordinates" class="form-control">
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Irrigation Type</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" name="irrigation_type" value="Rain-fed" class="form-check-input">
                    <label class="form-check-label">Rain-fed</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="irrigation_type" value="Irrigated" class="form-check-input">
                    <label class="form-check-label">Irrigated</label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Soil Type</label><br>
                @foreach(['Clay', 'Loam', 'Sandy', 'Other'] as $soil)
                    <div class="form-check form-check-inline">
                        <input type="radio" name="soil_type" value="{{ $soil }}" class="form-check-input">
                        <label class="form-check-label">{{ $soil }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="bi bi-save"></i> Save Farmer
        </button>
        <a href="{{ route('farmers.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
</x-layout>
