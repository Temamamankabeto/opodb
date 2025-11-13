<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-tree text-success me-2"></i>Add preparation Data</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sector.seedling-preparations.updatepreparation', $preparation->id) }}" method="POST"
            class="needs-validation" novalidate id="mainForm">
            @csrf
            @method('PUT')

            <!-- Year and Type Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Preparation Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Year</label>
                            <div class="form-control bg-light">
                                {{ $preparation->year }} EC
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Seedling Type</label>
                            <div class="form-control bg-light">
                                {{ $preparation->preparation_data['seedling_type']['name'] ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- preparation Section -->
            <div id="PreparationContainer">
                <!-- preparation Details -->
                <div class="card border-0 shadow-sm mb-4" id="preparationSection">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-tree me-2"></i>
                            preparation Details (Qophii)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row" id="PreparationDetailsContainer">
                            @foreach ($preparation->preparation_data['selected_seedlings'] ?? [] as $seedling)
                                <div class="col-md-6 mb-3 seedling-row" data-seedling-id="{{ $seedling['id'] }}">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="fw-semibold seedling-name mb-3">{{ $seedling['name'] }}</h6>

                                            <!-- Preparation Details -->
                                            <div class="planting-details">
                                                <h6 class="text-success mb-2">
                                                    <i class="bi bi-tree me-1"></i>Preparation (Dhaabbii)
                                                </h6>
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small">Hanga Qophii <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="0.01"
                                                            name="hanga_qophii[{{ $seedling['id'] }}]"
                                                            class="form-control hanga-dhaabbii" placeholder="0.00"
                                                            required min="0"
                                                            value="{{ old('hanga_qophii.' . $seedling['id'], $preparation->preparation_data['selected_seedlings'][$loop->index]['hanga_qophii'] ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Nama Hirmaate <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="0.01"
                                                            name="nama_hirmaate_qophii[{{ $seedling['id'] }}]"
                                                            class="form-control nama-hirmaate-qophii" placeholder="0.00"
                                                            required min="0"
                                                            value="{{ old('nama_hirmaate_qophii.' . $seedling['id'], $preparation->preparation_data['selected_seedlings'][$loop->index]['nama_hirmaate_qophii'] ?? '') }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Baayina Guyyaa <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" step="0.01"
                                                            name="baayina_guyyaa_qophii[{{ $seedling['id'] }}]"
                                                            class="form-control baayina-guyyaa-qophii"
                                                            placeholder="0.00" required min="0"
                                                            value="{{ old('baayina_guyyaa_qophii.' . $seedling['id'], $preparation->preparation_data['selected_seedlings'][$loop->index]['baayina_guyyaa_qophii'] ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end border-top pt-4">
                <a href="{{ route('sector.seedling-preparations.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4" id="submitBtn">
                    <i class="bi bi-check-circle me-2"></i>Update preparation Data
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');

            // Validate number inputs
            function validateInput(input) {
                const value = parseFloat(input.value);
                const isValid = !isNaN(value) && value >= 0;
                input.classList.toggle('is-invalid', !isValid);
                input.classList.toggle('is-valid', isValid);
                return isValid;
            }

            function validateAllInputs() {
                let valid = true;
                document.querySelectorAll('input[type="number"][required]').forEach(input => {
                    if (!validateInput(input)) valid = false;
                });
                return valid;
            }

            document.getElementById('mainForm').addEventListener('submit', function(e) {
                console.log('Submitting preparation form...');
                if (!validateAllInputs()) {
                    e.preventDefault();
                    alert('Please correct all highlighted fields.');
                    return;
                }

                // Prepare data object
                const seedlingTypeId = {{ $preparation->preparation_data['seedling_type']['id'] }};
                const preparationData = {
                    seedling_type_id: seedlingTypeId,
                    selected_seedlings: []
                };

                // Collect seedling details
                document.querySelectorAll('.seedling-row').forEach(row => {
                    const id = row.getAttribute('data-seedling-id');
                    preparationData.selected_seedlings.push({
                        seedling_id: parseInt(id),
                        hanga_qophii: parseFloat(row.querySelector('.hanga-dhaabbii')
                            ?.value || 0),
                        nama_hirmaate_qophii: parseFloat(row.querySelector(
                            '.nama-hirmaate-qophii')?.value || 0),
                        baayina_guyyaa_qophii: parseFloat(row.querySelector(
                            '.baayina-guyyaa-qophii')?.value || 0),
                    });
                });

                console.log('Collected data:', preparationData);

                // Remove old hidden input if any
                document.querySelector('input[name="preparation_data"]')?.remove();

                // ✅ Create correct hidden input
                const preparationInput = document.createElement('input');
                preparationInput.type = 'hidden';
                preparationInput.name = 'preparation_data';
                preparationInput.value = JSON.stringify(preparationData);

                this.appendChild(preparationInput);
            });
        });
    </script>


    <style>
        .seedling-row.border-success {
            border-color: #198754 !important;
        }

        .seedling-row.border-danger {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #198754 !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .planting-details {
            border-left: 3px solid #198754;
            padding-left: 15px;
        }
    </style>
</x-layout>
