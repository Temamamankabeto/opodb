<x-layout>
<div class="container py-5">
    <h2 class="fw-bold text-warning mb-4">
        <i class="bi bi-pencil"></i> Edit Farmer
    </h2>

    <form action="{{ route('farmers.update', $farmer->id) }}" method="POST" class="card p-4 shadow-sm border-0 rounded-3">
        @csrf
        {{-- Include the same form fields as in create, but use old() with defaults --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Farmer Name</label>
                <input type="text" name="farmer_name" class="form-control" value="{{ old('farmer_name', $farmer->farmer_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Gender</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" name="gender" value="Male" {{ $farmer->gender == 'Male' ? 'checked' : '' }} class="form-check-input">
                    <label class="form-check-label">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="gender" value="Female" {{ $farmer->gender == 'Female' ? 'checked' : '' }} class="form-check-input">
                    <label class="form-check-label">Female</label>
                </div>
            </div>
        </div>

        {{-- Keep the rest identical to create, but prefill using $farmer->field_name --}}
        {{-- Example: --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $farmer->phone_number) }}">
        </div>

        {{-- Include zone, woreda, kebele, union, association dropdowns --}}
        {{-- ... same as create, but with selected attribute --}}
        {{-- Example for one dropdown --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Zone</label>
            <select name="zone_id" class="form-select">
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" {{ $zone->id == $farmer->zone_id ? 'selected' : '' }}>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-warning mt-3">
            <i class="bi bi-save"></i> Update Farmer
        </button>
        <a href="{{ route('farmers.index') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
</x-layout>
