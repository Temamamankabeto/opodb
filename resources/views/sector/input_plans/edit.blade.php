<x-layout>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">Edit Input Plan</h3>
        <a href="{{ route('sector.input-plans.index') }}" class="btn btn-outline-secondary btn-sm">
            ← Back to Input Plans
        </a>
    </div>

    {{-- ✅ Summary Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title fw-bold text-secondary mb-3">Current Plan Overview</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Kebele:</strong> {{ $plan->kebeleCropPlan->kebele->name ?? 'N/A' }}</p>
                    <p><strong>Sector:</strong> {{ $plan->sector->name ?? 'N/A' }}</p>
                    <p><strong>Year:</strong> {{ $plan->year }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Crop Type:</strong> {{ $plan->cropType->name ?? 'N/A' }}</p>
                    <p><strong>Crop:</strong> {{ $plan->crop->name ?? 'N/A' }}</p>
                    <p><strong>Season:</strong> {{ ucfirst($plan->season) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Edit Form --}}
    <form action="{{ route('sector.input-plans.update', $plan->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 🔹 Year --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Year</label>
            <select name="year" id="year" class="form-select" required>
                <option value="">-- Select Year --</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $plan->year == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 🔹 Crop Type --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Crop Type</label>
            <select name="croptype_id" id="crop_type_id" class="form-select" required>
                <option value="">-- Select Crop Type --</option>
                @foreach($cropTypes as $type)
                    <option value="{{ $type->id }}" {{ $plan->croptype_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 🔹 Crop --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Crop</label>
            <select name="crop_id" id="crop_id" class="form-select" required>
                <option value="">-- Select Crop --</option>
                @foreach($crops as $crop)
                    <option value="{{ $crop->id }}" {{ $plan->crop_id == $crop->id ? 'selected' : '' }}>
                        {{ $crop->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 🔹 Season --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Season</label>
            <select name="season" id="season" class="form-select" required>
                <option value="">-- Select Season --</option>
                @foreach($seasons as $season)
                    <option value="{{ $season }}" {{ $plan->season == $season ? 'selected' : '' }}>
                        {{ ucfirst($season) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 🔹 Seed Required --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Seed Required (kg)</label>
            <input type="number" step="0.01" name="seed_required" value="{{ $plan->seed_required }}" class="form-control" required>
        </div>

        {{-- 🔹 Fertilizer Types --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Fertilizer Types</label>
            <div id="fertilizerTypesList" class="border rounded p-3">
                @php
                    $existingFertilizers = collect(json_decode($plan->fertilizer_details, true));
                @endphp

                @foreach($fertilizerTypes as $type)
                    @php
                        $typeFerts = $existingFertilizers->where('fertilizer_type_id', $type->id);
                    @endphp

                    <div class="mb-3">
                        <input 
                            type="checkbox" 
                            name="fertilizer_types[]" 
                            value="{{ $type->id }}" 
                            id="fertType{{ $type->id }}"
                            class="fertTypeCheckbox"
                            {{ $typeFerts->isNotEmpty() ? 'checked' : '' }}
                        >
                        <label for="fertType{{ $type->id }}" class="fw-bold">{{ $type->name }}</label>

                        <div class="ms-4 mt-2 border-start ps-3 {{ $typeFerts->isEmpty() ? 'd-none' : '' }}" 
                             id="fertilizers_{{ $type->id }}">
                            @if($typeFerts->isNotEmpty())
                                @foreach($typeFerts as $f)
                                    <div class="mb-2">
                                        <input type="checkbox" 
                                               name="fertilizers[{{ $type->id }}][]" 
                                               value="{{ $f['fertilizer_id'] }}" 
                                               id="fert_{{ $f['fertilizer_id'] }}" 
                                               checked>
                                        <label for="fert_{{ $f['fertilizer_id'] }}">
                                            {{ \App\Models\Fertilizer::find($f['fertilizer_id'])->name ?? 'Unknown' }}
                                        </label>

                                        <input type="number" 
                                               name="amount[{{ $type->id }}][]" 
                                               value="{{ $f['amount'] }}" 
                                               step="0.01" 
                                               class="form-control d-inline-block w-25 ms-2" 
                                               placeholder="Amount">

                                        <select name="unit[{{ $type->id }}][]" 
                                                class="form-select d-inline-block w-25 ms-2">
                                            <option value="kg" {{ $f['unit'] == 'kg' ? 'selected' : '' }}>kg</option>
                                            <option value="qun" {{ $f['unit'] == 'qun' ? 'selected' : '' }}>qun</option>
                                            <option value="ltr" {{ $f['unit'] == 'ltr' ? 'selected' : '' }}>ltr</option>
                                            <option value="bag" {{ $f['unit'] == 'bag' ? 'selected' : '' }}>bag</option>
                                            <option value="ton" {{ $f['unit'] == 'ton' ? 'selected' : '' }}>ton</option>
                                        </select>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary w-100 mt-3">💾 Update Input Plan</button>
    </form>
</div>

{{-- ✅ AJAX Script --}}
<script>
const cropTypeSelect = document.getElementById('crop_type_id');
const cropSelect = document.getElementById('crop_id');
const seasonSelect = document.getElementById('season');

// Load Crop Types when Year changes
document.getElementById('year').addEventListener('change', function() {
    const year = this.value;
    if (!year) return;
    fetch(`/sector/get-crop-types/${year}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Select Crop Type --</option>';
            data.forEach(t => options += `<option value="${t.id}">${t.name}</option>`);
            cropTypeSelect.innerHTML = options;
            cropSelect.innerHTML = '<option value="">-- Select Crop --</option>';
            seasonSelect.innerHTML = '<option value="">-- Select Season --</option>';
        });
});

// Load Crops when Crop Type changes
cropTypeSelect.addEventListener('change', function() {
    const id = this.value;
    if (!id) return;
    fetch(`/sector/get-crops/${id}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Select Crop --</option>';
            data.forEach(c => options += `<option value="${c.id}">${c.name}</option>`);
            cropSelect.innerHTML = options;
            seasonSelect.innerHTML = '<option value="">-- Select Season --</option>';
        });
});

// Load Seasons when Crop changes
cropSelect.addEventListener('change', function() {
    const cropId = this.value;
    if (!cropId) return;
    fetch(`/sector/get-seasons/${cropId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Select Season --</option>';
            data.forEach(s => options += `<option value="${s}">${s}</option>`);
            seasonSelect.innerHTML = options;
        });
});

// Show/Hide Fertilizers dynamically
document.querySelectorAll('.fertTypeCheckbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const container = document.getElementById(`fertilizers_${this.value}`);
        if (this.checked) {
            fetch(`/sector/get-fertilizers/${this.value}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<p class="text-muted small">No fertilizers available for this type.</p>';
                    } else {
                        data.forEach(f => {
                            html += `
                                <div class="mb-2">
                                    <input type="checkbox" name="fertilizers[${this.value}][]" value="${f.id}" id="fert_${f.id}">
                                    <label for="fert_${f.id}">${f.name}</label>
                                    <input type="number" name="amount[${this.value}][]" placeholder="Amount" step="0.01" class="form-control d-inline-block w-25 ms-2">
                                    <select name="unit[${this.value}][]" class="form-select d-inline-block w-25 ms-2">
                                        <option value="kg">kg</option>
                                        <option value="qun">qun</option>
                                        <option value="ltr">ltr</option>
                                        <option value="bag">bag</option>
                                        <option value="ton">ton</option>
                                    </select>
                                </div>`;
                        });
                    }
                    container.innerHTML = html;
                    container.classList.remove('d-none');
                });
        } else {
            container.classList.add('d-none');
            container.innerHTML = '';
        }
    });
});
</script>
</x-layout>
