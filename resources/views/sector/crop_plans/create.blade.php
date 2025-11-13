<x-layout>
<div class="container py-4">
    <h3 class="fw-bold mb-4 text-success text-center">
        🌱 Add Kebele Crop Plan ({{ Auth::user()->kebele->name }})
    </h3>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sector.crop-plans.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            {{-- 🔹 Year --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">📅 Year</label>
                <select name="year" id="year" class="form-select" required>
                    <option value="">-- Select Year --</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 🔹 Crop Type --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">🌿 Crop Type</label>
                <select name="croptype_id" id="croptype_id" class="form-select" required>
                    <option value="">-- Select Crop Type --</option>
                </select>
            </div>

            {{-- 🔹 Crop --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">🌾 Crop</label>
                <select name="crop_id" id="crop_id" class="form-select" required>
                    <option value="">-- Select Crop --</option>
                </select>
            </div>

            {{-- 🔹 Area to Plant --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">🌱 Area to Plant (ha)</label>
                <input type="number" step="0.01" name="area_to_plant" id="area_to_plant"
                    class="form-control" required>
            </div>

            {{-- 🔹 Productivity --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">📈 Productivity (qt/ha)</label>
                <input type="number" step="0.01" name="productivity" id="productivity"
                    class="form-control" required>
            </div>

            {{-- 🔹 Expected Yield --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">📊 Expected Yield (qt)</label>
                <input type="number" step="0.01" id="expected_yield"
                    class="form-control bg-light" readonly>
            </div>

            {{-- 🔹 Season --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">🌞 Season</label>
                <select name="season" id="season" class="form-select" required>
                    <option value="">-- Select Season --</option>
                    <option value="Ganna">Ganna</option>
                    <option value="Bona">Bona</option>
                    <option value="Arfaasaa">Arfaasaa</option>
                </select>
            </div>

            {{-- 🔹 Seed Required --}}
            <div class="col-md-4">
                <label class="form-label fw-semibold">🌰 Seed Required (kg)</label>
                <input type="number" step="0.01" name="seed_required"
                    class="form-control" required>
            </div>
        </div>

        {{-- 🔹 Fertilizer Section --}}
        <div class="mt-4">
            <h5 class="fw-bold mb-3">💧 Fertilizer Plan</h5>
            <div id="fertilizerTypesContainer" class="border rounded p-3">
                @foreach($fertilizerTypes as $type)
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input fertTypeCheckbox"
                                id="fertType{{ $type->id }}" value="{{ $type->id }}"
                                name="fertilizer_types[]">
                            <label class="form-check-label fw-semibold" for="fertType{{ $type->id }}">
                                {{ $type->name }}
                            </label>
                        </div>
                        <div id="fertilizers_{{ $type->id }}" class="ms-4 mt-2 d-none border-start ps-3"></div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 🔹 Responsible Staff --}}
        <div class="mt-4">
            <label class="form-label fw-semibold">👨‍🌾 Responsible Staff</label>
            <input type="text" name="responsible_staff" class="form-control" placeholder="Enter staff name">
        </div>

        {{-- 🔹 Notes --}}
        <div class="mt-3">
            <label class="form-label fw-semibold">🗒 Notes</label>
            <textarea name="notes" rows="3" class="form-control"></textarea>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="mdi mdi-content-save-outline"></i> Save Plan
            </button>
        </div>
    </form>
</div>

{{-- ✅ JS Section --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let cropData = [];

// 🔹 Step 1: Load Crop Types for Selected Year
$('#year').on('change', function() {
    const year = $(this).val();
    if (!year) return;
    $('#croptype_id').html('<option>Loading...</option>');
    $.getJSON(`/sector/crops-by-year/${year}`, function(data) {
        $('#croptype_id').html('<option value="">-- Select Crop Type --</option>');
        data.forEach(t => $('#croptype_id').append(`<option value="${t.id}">${t.name}</option>`));
    });
});

// 🔹 Step 2: Load Crops for Selected Type
$('#croptype_id').on('change', function() {
    const year = $('#year').val();
    const typeId = $(this).val();
    if (!year || !typeId) return;

    $('#crop_id').html('<option>Loading...</option>');
    $.getJSON(`/sector/crops-by-year-type/${year}/${typeId}`, function(data) {
        cropData = data;
        $('#crop_id').html('<option value="">-- Select Crop --</option>');
        data.forEach(c => $('#crop_id').append(`<option value="${c.id}">${c.name}</option>`));
    });
});

// 🔹 Step 3: Set Productivity from Crop Data
$('#crop_id').on('change', function() {
    const crop = cropData.find(c => c.id == $(this).val());
    $('#productivity').val(crop?.productivity || 0);
    updateExpectedYield();
});

// 🔹 Step 4: Calculate Expected Yield
$('#area_to_plant, #productivity').on('input', updateExpectedYield);
function updateExpectedYield() {
    const area = parseFloat($('#area_to_plant').val()) || 0;
    const prod = parseFloat($('#productivity').val()) || 0;
    $('#expected_yield').val((area * prod).toFixed(2));
}

// 🔹 Step 5: Fertilizer Type → Fertilizers
$('.fertTypeCheckbox').on('change', function() {
    const typeId = this.value;
    const container = $(`#fertilizers_${typeId}`);
    if (this.checked) {
        container.removeClass('d-none').html('<p>Loading fertilizers...</p>');
        fetch(`/sector/get-fertilizers/${typeId}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    container.html('<p class="text-muted">No fertilizers for this type.</p>');
                    return;
                }
                let html = '';
                data.forEach(f => {
                    html += `
                        <div class="form-check mb-2">
                            <input type="checkbox" name="fertilizers[${typeId}][]" value="${f.id}" id="fert_${typeId}_${f.id}" class="form-check-input">
                            <label for="fert_${typeId}_${f.id}" class="form-check-label">${f.name}</label>
                            <input type="number" step="0.01" name="amount[${typeId}][]" placeholder="Amount" class="form-control form-control-sm d-inline-block w-25 ms-2">
                            <select name="unit[${typeId}][]" class="form-select form-select-sm d-inline-block w-25 ms-2">
                                <option value="kg">kg</option>
                                <option value="qun">qun</option>
                                <option value="ltr">ltr</option>
                                <option value="bag">bag</option>
                                <option value="ton">ton</option>
                            </select>
                        </div>`;
                });
                container.html(html);
            });
    } else {
        container.addClass('d-none').html('');
    }
});
</script>
</x-layout>
