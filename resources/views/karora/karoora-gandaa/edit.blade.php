<x-layout>
    <div class="container mt-4">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <h2 class="mb-4 text-primary">Edit Land Farming Plan</h2>

        <form action="{{ route('land-plans.update', $plan->id) }}" method="POST" class="border p-4 rounded shadow-sm bg-white">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-control" required>
                        @php $currentYear = date('Y'); @endphp
                        @for ($y = 2010; $y <= $currentYear + 5; $y++)
                            <option value="{{ $y }}" {{ $plan->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Season</label>
                    <select name="season" class="form-control" required>
                        <option value="Ganna" {{ $plan->season == 'Ganna' ? 'selected' : '' }}>Ganna</option>
                        <option value="Bona" {{ $plan->season == 'Bona' ? 'selected' : '' }}>Bona</option>
                        <option value="Arfaasa" {{ $plan->season == 'Arfaasa' ? 'selected' : '' }}>Arfaasa</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Crop Type</label>
                    <select name="crop_type_id" id="crop-type" class="form-control" required onchange="fetchCrops(this)">
                        <option value="">-- Select --</option>
                        @foreach ($cropTypes as $type)
                        <option value="{{ $type->id }}" {{ $plan->crop_type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Crop</label>
                    <select name="crop_id" id="crop-select" class="form-control" required>
                        <option value="">-- Select --</option>
                        @foreach ($crops as $crop)
                        <option value="{{ $crop->id }}" {{ $plan->crop_id == $crop->id ? 'selected' : '' }}>
                            {{ $crop->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Land (ha)</label>
                    <input type="number" step="0.01" name="land_hectares" class="form-control" value="{{ $plan->land_hectares }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Productivity (Qt/ha)</label>
                    <input type="number" step="0.01" name="productivity" class="form-control" value="{{ $plan->productivity }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Production (Qt)</label>
                <input type="number" step="0.01" name="production" class="form-control" value="{{ $plan->production }}" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Update Plan</button>
            <a href="{{ route('land-plans.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script>
        function fetchCrops(selectElement) {
            const cropTypeId = selectElement.value;
            const cropSelect = document.getElementById('crop-select');

            if (!cropTypeId) {
                cropSelect.innerHTML = '<option value="">-- Select --</option>';
                return;
            }

            fetch(`/get-crops/${cropTypeId}`)
                .then(res => res.json())
                .then(data => {
                    cropSelect.innerHTML = '<option value="">-- Select --</option>';
                    data.forEach(crop => {
                        const option = document.createElement('option');
                        option.value = crop.id;
                        option.textContent = crop.name;
                        cropSelect.appendChild(option);
                    });
                })
                .catch(err => console.error("Failed to fetch crops", err));
        }

        document.addEventListener('input', function(e) {
            if (e.target.name === 'land_hectares' || e.target.name === 'productivity') {
                const land = parseFloat(document.querySelector('[name="land_hectares"]').value) || 0;
                const productivity = parseFloat(document.querySelector('[name="productivity"]').value) || 0;
                const production = land * productivity;
                document.querySelector('[name="production"]').value = production.toFixed(2);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            @if($errors -> any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
            });
            @endif

            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
            @endif
        });
    </script>
</x-layout>