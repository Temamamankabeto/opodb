<x-layout>
<div class="container py-5">
    <h4 class="mb-4 text-success fw-bold">Edit Crop Plan for {{ $farmer->farmer_name }}</h4>

    <form action="{{ route('parcel-crop-plans.update', $plan->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Year</label>
                <input type="number" name="year" class="form-control" value="{{ $plan->year }}" required>
            </div>

            <div class="col-md-8">
                <label>Parcel</label>
                <select name="parcel_id" id="parcel_id" class="form-select" required>
                    <option value="">-- Select Parcel --</option>
                    @foreach($parcels as $parcel)
                        <option value="{{ $parcel['id'] }}" data-soil="{{ $parcel['soil_type'] }}" data-area="{{ $parcel['area'] }}"
                            {{ $plan->parcel_id == $parcel['id'] ? 'selected' : '' }}>
                            {{ $parcel['land_type'] }} | Soil: {{ $parcel['soil_type'] }} | {{ $parcel['area'] }} ha
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Crop Type</label>
                <select id="croptype_id" name="croptype_id" class="form-select" required>
                    <option value="">-- Select Crop Type --</option>
                    @foreach($cropTypes as $ct)
                        <option value="{{ $ct->id }}" {{ $plan->croptype_id == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Crop</label>
                <select id="crop_id" name="crop_id" class="form-select" required>
                    <option value="{{ $plan->crop_id }}">{{ $plan->crop->name ?? '—' }}</option>
                </select>
            </div>

            <div class="col-md-4">
                <label>Season</label>
                <select name="season" class="form-select" required>
                    <option value="Meher" {{ $plan->season == 'Meher' ? 'selected' : '' }}>Meher</option>
                    <option value="Belg" {{ $plan->season == 'Belg' ? 'selected' : '' }}>Belg</option>
                    <option value="Irrigated" {{ $plan->season == 'Irrigated' ? 'selected' : '' }}>Irrigated</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Area (ha)</label>
                <input type="number" step="0.01" name="area" id="area" class="form-control" value="{{ $plan->area }}" required>
            </div>
            <div class="col-md-3">
                <label>Productivity (qt/ha)</label>
                <input type="number" step="0.01" name="productivity_per_ha" id="productivity" class="form-control" value="{{ $plan->productivity_per_ha }}" required>
            </div>
            <div class="col-md-3">
                <label>Expected Yield (qt)</label>
                <input type="number" step="0.01" id="expected_yield" name="expected_yield" class="form-control" value="{{ $plan->expected_yield }}" readonly>
            </div>
        </div>

        <div class="mb-3" id="fertilizer-section">
            <h5 class="text-primary">Recommended Fertilizers</h5>
            <table class="table table-bordered" id="fertilizer-table">
                <thead>
                    <tr>
                        <th>Fertilizer</th>
                        <th>Required (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plan->fertilizer_recommendations as $fert)
                        <tr>
                            <td><input type="text" name="fertilizer_name[]" class="form-control" value="{{ $fert['fertilizer'] }}" readonly></td>
                            <td><input type="number" step="0.01" name="fertilizer_required_kg[]" class="form-control" value="{{ $fert['required_kg'] }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-success mt-3">Update Plan</button>
    </form>
</div>

<script>
const cropTypeSelect = document.getElementById('croptype_id');
const cropSelect = document.getElementById('crop_id');
const parcelSelect = document.getElementById('parcel_id');
const areaInput = document.getElementById('area');
const productivityInput = document.getElementById('productivity');
const expectedYieldInput = document.getElementById('expected_yield');

// Update expected yield dynamically
function calcYield() {
    const area = parseFloat(areaInput.value || 0);
    const prod = parseFloat(productivityInput.value || 0);
    expectedYieldInput.value = (area * prod).toFixed(2);
}
areaInput.addEventListener('input', calcYield);
productivityInput.addEventListener('input', calcYield);
</script>
</x-layout>
