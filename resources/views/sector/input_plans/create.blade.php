<x-layout>
<div class="container py-4">
    <h3 class="mb-3 fw-bold text-center text-success">Create Input Plan</h3>

    {{-- ✅ Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Form --}}
    <form action="{{ route('sector.input-plans.store') }}" method="POST">
        @csrf

        {{-- 🔹 Year --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Year</label>
            <select name="year" id="year" class="form-select" required>
                <option value="">-- Select Year --</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        {{-- 🔹 Crop Type --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Crop Type</label>
            <select name="croptype_id" id="crop_type_id" class="form-select" required>
                <option value="">-- Select Crop Type --</option>
            </select>
        </div>

        {{-- 🔹 Crop --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Crop</label>
            <select name="crop_id" id="crop_id" class="form-select" required>
                <option value="">-- Select Crop --</option>
            </select>
        </div>

        {{-- 🔹 Season --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Season</label>
            <select name="season" id="season" class="form-select" required>
                <option value="">-- Select Season --</option>
            </select>
        </div>

        {{-- 🔹 Seed Required --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Seed Required (kg)</label>
            <input type="number" step="0.01" name="seed_required" class="form-control" placeholder="Enter seed amount" required>
        </div>

        {{-- 🔹 Fertilizer Types --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Fertilizer Types</label>
            <div id="fertilizerTypesList" class="border rounded p-3">
                @foreach($fertilizerTypes as $type)
                    <div class="mb-2">
                        <input type="checkbox" name="fertilizer_types[]" value="{{ $type->id }}" class="fertTypeCheckbox" id="fertType{{ $type->id }}">
                        <label for="fertType{{ $type->id }}" class="fw-bold">{{ $type->name }}</label>
                        <div class="ms-4 mt-2 d-none border-start ps-3" id="fertilizers_{{ $type->id }}"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-success w-100 mt-3">💾 Save Input Plan</button>
    </form>
</div>

{{-- ✅ AJAX Script --}}
<script>
const cropTypeSelect = document.getElementById('crop_type_id');
const cropSelect = document.getElementById('crop_id');
const seasonSelect = document.getElementById('season');

// 🔹 Load Crop Types when Year changes
document.getElementById('year').addEventListener('change', function() {
    const year = this.value;
    if (!year) return;
    fetch(`/sector/get-crop-types/${year}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Select Crop Type --</option>';
            data.forEach(t => { options += `<option value="${t.id}">${t.name}</option>` });
            cropTypeSelect.innerHTML = options;
            cropSelect.innerHTML = '<option value="">-- Select Crop --</option>';
            seasonSelect.innerHTML = '<option value="">-- Select Season --</option>';
        });
});

// 🔹 Load Crops when Crop Type changes
cropTypeSelect.addEventListener('change', function() {
    const id = this.value;
    if (!id) return;
    fetch(`/sector/get-crops/${id}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Select Crop --</option>';
            data.forEach(c => { options += `<option value="${c.id}">${c.name}</option>` });
            cropSelect.innerHTML = options;
            seasonSelect.innerHTML = '<option value="">-- Select Season --</option>';
        });
});

// 🔹 Load Seasons when Crop changes
cropSelect.addEventListener('change', function() {
    const cropId = this.value;
    if (!cropId) return;
    fetch(`/sector/get-seasons/${cropId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- Select Season --</option>';
            data.forEach(s => { options += `<option value="${s}">${s}</option>` });
            seasonSelect.innerHTML = options;
        });
});

// 🔹 Show Fertilizers when Fertilizer Type checked
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
