<x-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><i class="mdi mdi-sprout text-success"></i> Edit Kebele Crop Plan</h2>
            <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back
            </a>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('sector.crop-plans.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">

                        {{-- Year --}}
                        <div class="col-md-4">
                            <label for="year" class="form-label fw-semibold">📅 Year (Ethiopian)</label>
                            <input type="number" name="year" id="year" class="form-control form-control-lg"
                                value="{{ old('year', $plan->year) }}" min="2000" required>
                        </div>

                        {{-- Crop Type --}}
                        <div class="col-md-4">
                            <label for="croptype_id" class="form-label fw-semibold">🌿 Crop Type</label>
                            <select name="croptype_id" id="croptype_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Crop Type --</option>
                                @foreach($cropTypes as $type)
                                <option value="{{ $type->id }}" {{ old('croptype_id', $plan->croptype_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Crop --}}
                        <div class="col-md-4">
                            <label for="crop_id" class="form-label fw-semibold">🌾 Crop</label>
                            <select name="crop_id" id="crop_id" class="form-select form-select-lg" required>
                                <option value="">-- Select Crop --</option>
                            </select>
                        </div>

                        {{-- Area --}}
                        <div class="col-md-4">
                            <label for="area_to_plant" class="form-label fw-semibold">🌱 Area to Plant (ha)</label>
                            <input type="number" step="0.01" name="area_to_plant" id="area_to_plant"
                                class="form-control form-control-lg" value="{{ old('area_to_plant', $plan->area_to_plant) }}" required>
                        </div>

                        {{-- Productivity --}}
                        <div class="col-md-4">
                            <label for="productivity" class="form-label fw-semibold">📈 Productivity (qt/ha)</label>
                            <input type="number" step="0.01" name="productivity" id="productivity"
                                class="form-control form-control-lg" value="{{ old('productivity', $plan->productivity) }}" required>
                        </div>

                        {{-- Expected Yield --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">📊 Expected Yield (qt)</label>
                            <input type="number" step="0.01" id="expected_yield_display"
                                class="form-control form-control-lg bg-light" readonly
                                value="{{ old('expected_yield', $plan->expected_yield) }}">
                            {{-- Hidden input for submission --}}
                            <input type="hidden" name="expected_yield" id="expected_yield"
                                value="{{ old('expected_yield', $plan->expected_yield) }}">
                        </div>

                        {{-- Fertilizer / Inputs Accordion --}}
                        <div class="col-12">
                            <div class="accordion mt-3" id="fertilizerAccordion">
                                <div class="accordion-item border rounded-3 shadow-sm">
                                    <h2 class="accordion-header" id="headingFertilizer">
                                        <button class="accordion-button fw-semibold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFertilizer"
                                            aria-expanded="true" aria-controls="collapseFertilizer">
                                            🧪 Input & Fertilizer Details
                                        </button>
                                    </h2>
                                    <div id="collapseFertilizer" class="accordion-collapse collapse show"
                                        aria-labelledby="headingFertilizer" data-bs-parent="#fertilizerAccordion">
                                        <div class="accordion-body">
                                            <label class="form-label fw-semibold">Input Needed</label>
                                            <input type="text" name="input_needed" id="input_needed"
                                                class="form-control form-control-lg" value="{{ old('input_needed', $plan->input_needed) }}">
                                            <div id="fertilizer_list" class="mt-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Season --}}
                        <div class="col-md-6">
                            <label for="season" class="form-label fw-semibold">☀️ Season</label>
                            <select name="season" id="season" class="form-select form-select-lg" required>
                                <option value="">-- Select Season --</option>
                                @foreach(['Belg','Meher','Irrigation','Year-round'] as $season)
                                <option value="{{ $season }}" {{ old('season', $plan->season) == $season ? 'selected' : '' }}>
                                    {{ $season }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Responsible Staff --}}
                        <div class="col-md-6">
                            <label for="responsible_staff" class="form-label fw-semibold">👤 Responsible Staff</label>
                            <input type="text" name="responsible_staff" id="responsible_staff"
                                class="form-control form-control-lg" value="{{ old('responsible_staff', $plan->responsible_staff) }}">
                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success btn-lg me-2">
                            <i class="mdi mdi-content-save-outline"></i> Update Crop Plan
                        </button>
                        <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="mdi mdi-arrow-left"></i> Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let cropsData = {};
        const oldCropType = "{{ old('croptype_id', $plan->croptype_id) }}";
        const oldCrop = "{{ old('crop_id', $plan->crop_id) }}";

        function loadCrops(typeId, selectedCropId = null) {
            if (!typeId) return;
            $('#crop_id').html('<option value="">Loading...</option>');
            $.getJSON('{{ url("/sector/crops-by-type") }}/' + typeId, function(data) {
                cropsData = data;
                $('#crop_id').empty().append('<option value="">-- Select Crop --</option>');
                $.each(data, function(id, crop) {
                    $('#crop_id').append(
                        `<option value="${id}" ${selectedCropId == id ? 'selected' : ''}>${crop.name}</option>`
                    );
                });
                calculateInputs();
            });
        }

        function calculateInputs() {
            const cropId = $('#crop_id').val();
            const area = parseFloat($('#area_to_plant').val()) || 0;
            const productivity = parseFloat($('#productivity').val()) || 0;

            if (!cropId || !cropsData[cropId]) return;

            const crop = cropsData[cropId];
            const totalSeeds = area * (crop.seeds_per_ha ?? 20);
            const fertilizers = crop.fertilizers ?? [];

            const expectedYield = (area * productivity).toFixed(2);
            $('#expected_yield_display').val(expectedYield);
            $('#expected_yield').val(expectedYield);

            $('#input_needed').val(`${crop.name} Seeds: ${totalSeeds.toFixed(2)} kg`);

            let fertHtml = "";
            if (fertilizers.length > 0) {
                fertHtml = `<table class="table table-bordered mt-2">
                <thead><tr><th>Type</th><th>Quantity (kg)</th></tr></thead><tbody>`;
                fertilizers.forEach(f => {
                    fertHtml += `<tr><td>${f.type}</td><td>${(f.quantity_per_ha * area).toFixed(2)}</td></tr>`;
                });
                fertHtml += "</tbody></table>";
            } else {
                fertHtml = "<p class='text-muted mt-2'>No fertilizers defined for this crop.</p>";
            }
            $('#fertilizer_list').html(fertHtml);
        }

        $('#croptype_id').on('change', function() {
            loadCrops($(this).val());
        });
        $('#area_to_plant, #productivity, #crop_id').on('input change', calculateInputs);

        $(document).ready(function() {
            if (oldCropType) {
                loadCrops(oldCropType, oldCrop);
            }
        });
    </script>
</x-layout>