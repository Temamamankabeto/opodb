<x-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="mdi mdi-sprout text-success"></i> Add Crop Plan ({{ Auth::user()->kebele->name }})</h2>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-arrow-left"></i> Back</a>
        </div>

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

        <div class="card shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('sector.crop-plans.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Year --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">📅 Year</label>
                            <select name="year" id="year" class="form-select form-select-lg" required>
                                <option value="">-- Select Year --</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Crop Type --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">🌿 Crop Type</label>
                            <select name="croptype_id" id="croptype_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Crop Type --</option>
                            </select>
                        </div>

                        {{-- Crop --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">🌾 Crop</label>
                            <select name="crop_id" id="crop_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Crop --</option>
                            </select>
                        </div>

                        {{-- Area --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">🌱 Area to Plant (ha)</label>
                            <input type="number" step="0.01" name="area_to_plant" id="area_to_plant"
                                class="form-control form-control-lg" required>
                        </div>

                        {{-- Productivity --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">📈 Productivity (qt/ha)</label>
                            <input type="number" step="0.01" name="productivity" id="productivity"
                                class="form-control form-control-lg" readonly required>
                        </div>

                        {{-- Expected Yield --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">📊 Expected Yield (qt)</label>
                            <input type="number" step="0.01" id="expected_yield"
                                class="form-control form-control-lg bg-light" readonly>
                        </div>

                        {{-- Season --}}
<div class="col-md-4">
    <label class="form-label fw-semibold">🌞 Season</label>
    <select name="season" id="season" class="form-select form-select-lg" required>
        <option value="">-- Select Season --</option>
        <option value="Belg">Belg</option>
        <option value="Meher">Meher</option>
        <option value="Kiremt">Kiremt</option>
        <!-- Add more as needed -->
    </select>
</div>


                        {{-- Fertilizers --}}
                        <div class="col-12 mt-3" id="fertilizer_list">
                            <p class="text-muted">Select crop to view fertilizer requirements...</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="mdi mdi-content-save-outline"></i> Save Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cropData = [];

        // Step 1: Year → Crop Types
        $('#year').on('change', function() {
            const year = $(this).val();
            if (!year) return;

            $('#croptype_id').html('<option>Loading...</option>');
            $('#crop_id').html('<option value="">-- Select Crop --</option>');

            $.getJSON(`/sector/crops-by-year/${year}`, function(data) {
                $('#croptype_id').empty().append('<option value="">-- Select Crop Type --</option>');
                data.forEach(t => {
                    $('#croptype_id').append(`<option value="${t.id}">${t.name}</option>`);
                });
            });
        });

        // Step 2: Crop Type → Crops
        $('#croptype_id').on('change', function() {
            const year = $('#year').val();
            const typeId = $(this).val();
            if (!year || !typeId) return;

            $('#crop_id').html('<option>Loading...</option>');

            $.getJSON(`/sector/crops-by-year-type/${year}/${typeId}`, function(data) {
                cropData = data;
                $('#crop_id').empty().append('<option value="">-- Select Crop --</option>');
                data.forEach(c => {
                    $('#crop_id').append(`<option value="${c.id}">${c.name}</option>`);
                });
            });
        });

        // Step 3: Crop → fill Productivity & Fertilizers
        $('#crop_id').on('change', function() {
            const selectedId = $(this).val();
            const crop = cropData.find(c => c.id == selectedId);
            if (!crop) return;

            $('#productivity').val(crop.productivity || 0);
            updateExpectedYield();

            if (crop.fertilizers && crop.fertilizers.length > 0) {
                let fertArr = typeof crop.fertilizers === 'string' ? JSON.parse(crop.fertilizers) : crop.fertilizers;
                let html = `<table class="table table-bordered mt-2">
                    <thead class="table-secondary"><tr><th>Fertilizer Type</th><th>Per Ha (kg)</th></tr></thead><tbody>`;
                fertArr.forEach(f => {
                    html += `<tr><td>${f.type}</td><td>${f.quantity_per_ha}</td></tr>`;
                });
                html += '</tbody></table>';
                $('#fertilizer_list').html(html);
            } else {
                $('#fertilizer_list').html("<p class='text-muted'>No fertilizers defined for this crop.</p>");
            }
        });

        // Expected yield calculator
        $('#area_to_plant').on('input', updateExpectedYield);
        function updateExpectedYield() {
            const area = parseFloat($('#area_to_plant').val()) || 0;
            const prod = parseFloat($('#productivity').val()) || 0;
            $('#expected_yield').val((area * prod).toFixed(2));
        }
    </script>
</x-layout>
