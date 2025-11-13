<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Compost Plan</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        <form action="{{ route('sector.gabbina_biyyee.update', $gabbinaBiyyee->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-recycle me-2"></i>
                        Compost Plan Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-calendar3 text-muted"></i>
                                </span>
                                <select name="year" class="form-select border-start-0 ps-0" required>
                                    <option value="">-- Select Year --</option>
                                    @php
                                        $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                        $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                                    @endphp
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" 
                                            {{ old('year', $gabbinaBiyyee->year) == $year ? 'selected' : '' }}>
                                            {{ $year }} EC
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Compost Type <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag text-muted"></i>
                                </span>
                                <select name="compost_type" class="form-select border-start-0 ps-0" required id="compostType">
                                    <option value="">-- Select Compost Type --</option>
                                    @foreach ($compostTypes as $type)
                                        <option value="{{ $type->name }}" 
                                            {{ old('compost_type', $gabbinaBiyyee->compost_type) == $type->name ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('compost_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Common Quantity (Qt)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-people text-muted"></i>
                                </span>
                                <input type="number" step="0.01" name="common" class="form-control border-start-0 ps-0"
                                       value="{{ old('common', $gabbinaBiyyee->common) }}"
                                       placeholder="0.00" id="commonInput">
                            </div>
                            @error('common')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Individual Quantity (Qt)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="number" step="0.01" name="individual" class="form-control border-start-0 ps-0"
                                       value="{{ old('individual', $gabbinaBiyyee->individual) }}"
                                       placeholder="0.00" id="individualInput">
                            </div>
                            @error('individual')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Total Quantity (Qt)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-calculator text-muted"></i>
                                </span>
                                <input type="number" step="0.01" name="total" class="form-control border-start-0 ps-0"
                                       value="{{ old('total', $gabbinaBiyyee->total) }}"
                                       placeholder="0.00" id="totalInput" readonly>
                            </div>
                            @error('total')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="landAreaSection" 
                             style="{{ old('compost_type', $gabbinaBiyyee->compost_type) === 'Nooraa Hojiirra oole' ? '' : 'display: none;' }}">
                            <label class="form-label fw-semibold">Land Area (ha)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-geo-alt text-muted"></i>
                                </span>
                                <input type="number" step="0.01" name="land_area" class="form-control border-start-0 ps-0"
                                       value="{{ old('land_area', $gabbinaBiyyee->land_area) }}"
                                       placeholder="0.00">
                            </div>
                            @error('land_area')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end border-top pt-4">
                <a href="{{ route('sector.gabbina_biyyee.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Update Compost Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        // Calculate total automatically
        function calculateTotal() {
            const common = parseFloat(document.getElementById('commonInput').value) || 0;
            const individual = parseFloat(document.getElementById('individualInput').value) || 0;
            document.getElementById('totalInput').value = (common + individual).toFixed(2);
        }

        // Toggle land area visibility
        function toggleLandArea() {
            const compostType = document.getElementById('compostType').value;
            const landAreaSection = document.getElementById('landAreaSection');
            
            if (compostType === 'Nooraa Hojiirra oole') {
                landAreaSection.style.display = 'block';
            } else {
                landAreaSection.style.display = 'none';
                document.querySelector('input[name="land_area"]').value = '';
            }
        }

        // Event listeners
        document.getElementById('commonInput').addEventListener('input', calculateTotal);
        document.getElementById('individualInput').addEventListener('input', calculateTotal);
        document.getElementById('compostType').addEventListener('change', toggleLandArea);

        // Initial calculations
        calculateTotal();
        toggleLandArea();

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</x-layout>