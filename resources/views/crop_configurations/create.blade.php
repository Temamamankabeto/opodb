<x-layout>
    <div class="container-fluid py-4 bg-light min-vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1 text-success fw-bold">Configure Crop by Kebele</h4>
                        <p class="text-muted mb-0">Set up crop-specific configurations including seed rates,
                            productivity, and fertilizer requirements</p>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 rounded">
                        <i class="bi bi-gear-fill text-success fs-4"></i>
                    </div>
                </div>

                <!-- Success Alert -->
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div class="fw-medium">{{ session('success') }}</div>
                    </div>
                @endif

                @if ($errors->has('duplicate'))
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div class="fw-medium">{{ $errors->first('duplicate') }}</div>
                    </div>
                @endif

                <!-- Configuration Form -->
                <form method="POST" action="{{ route('crop-configurations.store') }}"
                    class="card shadow border-0 overflow-hidden">
                    @csrf

                    <!-- Card Header -->
                    <div class="card-header bg-white py-4 border-bottom">
                        <h5 class="mb-0 text-dark"><i class="bi bi-pencil-square text-success me-2"></i>Basic
                            Configuration</h5>
                    </div>

                    <div class="card-body p-4">
                        <!-- Year Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="bi bi-calendar3 text-muted"></i></span>
                                    <select name="year" class="form-select border-start-0 ps-0" required>
                                        <option value="">-- Select Year --</option>
                                        @php
                                            // Get current Ethiopian year
                                            $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                            $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                                        @endphp
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                {{ $year == $currentEthiopianYear ? 'selected' : '' }}>
                                                {{ $year }} EC (Ethiopian Calendar)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted mt-1">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Current: {{ $currentEthiopianYear }} EC •
                                    Gregorian: {{ date('Y') }}
                                </small>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-calendar-check text-success"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Ethiopian Calendar</small>
                                                <strong id="current-ethiopian-date"
                                                    class="text-success">Loading...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Crop Type & Crop Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Select Crop Type <span
                                        class="text-danger">*</span></label>
                                <select name="croptype_id" id="croptype_id" class="form-select select-enhanced"
                                    required>
                                    <option value="">-- Select Crop Type --</option>
                                    @foreach ($croptypes as $croptype)
                                        <option value="{{ $croptype->id }}">{{ $croptype->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Select Crop <span
                                        class="text-danger">*</span></label>
                                <select name="crop_id" id="crop_id" class="form-select select-enhanced" required
                                    disabled>
                                    <option value="">-- Select Crop Type First --</option>
                                </select>
                                <div id="crop_loading" class="d-none">
                                    <small class="text-muted">Loading crops...</small>
                                </div>
                            </div>
                        </div>

                        

                        <!-- Seed Rate & Productivity -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Seed Rate (kg/ha)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="seed_rate_kg_per_ha" class="form-control"
                                        placeholder="e.g., 25.5">
                                    <span class="input-group-text bg-white">kg/ha</span>
                                </div>
                                <small class="text-muted">Recommended seed amount per hectare</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Productivity (qt/ha)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="productivity_per_ha" class="form-control"
                                        placeholder="e.g., 35.2">
                                    <span class="input-group-text bg-white">qt/ha</span>
                                </div>
                                <small class="text-muted">Expected yield per hectare</small>
                            </div>
                        </div>

                        <!-- Soil Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Select Soil Type <span
                                        class="text-danger">*</span></label>
                                <select name="soil_type_id" class="form-select select-enhanced" required>
                                    <option value="">-- Select Soil Type --</option>
                                    @foreach ($soilTypes as $soil)
                                        <option value="{{ $soil->id }}">{{ $soil->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Fertilizer Requirements Section -->
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 text-dark"><i class="bi bi-flower1 text-success me-2"></i>Fertilizer
                                    Requirements (kg/ha)</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info border-0 bg-info bg-opacity-10 small mb-4">
                                    <div class="d-flex">
                                        <i class="bi bi-info-circle text-info me-2 mt-1"></i>
                                        <div>Enter the recommended fertilizer application rates per hectare for this
                                            crop and soil combination.</div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="60%">Fertilizer Name</th>
                                                <th width="40%">Rate (kg/ha)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fertilizers as $fertilizer)
                                                <tr>
                                                    <td class="fw-medium">{{ $fertilizer->name }}</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="fertilizers[{{ $fertilizer->id }}]"
                                                                placeholder="0.00">
                                                            <span class="input-group-text bg-white">kg/ha</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mt-4">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Add any additional notes or recommendations for this configuration..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-check-lg me-1"></i> Save Configuration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Toast (Hidden by default) -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i> Configuration saved successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <style>
        .select-enhanced {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .card {
            border-radius: 12px;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
            border-color: #198754;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }

        .table td {
            vertical-align: middle;
        }

        .input-group-text {
            border-radius: 0 8px 8px 0;
        }

        .input-group .form-control:first-child {
            border-radius: 8px 0 0 8px;
        }

        .alert {
            border-radius: 10px;
        }

        .ethiopian-calendar-badge {
            background: linear-gradient(135deg, #198754, #146c43);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>

    <script>
        // Dynamic crop loading based on crop type selection
        document.getElementById('croptype_id').addEventListener('change', function() {
            const croptypeId = this.value;
            const cropSelect = document.getElementById('crop_id');
            const cropLoading = document.getElementById('crop_loading');

            if (croptypeId) {
                // Show loading state
                cropSelect.disabled = true;
                cropSelect.innerHTML = '<option value="">Loading crops...</option>';
                cropLoading.classList.remove('d-none');

                // Fetch crops for the selected crop type
                fetch(`/kebele/crop-configurations/get-crops-by-type/${croptypeId}`)
                    .then(response => response.json())
                    .then(data => {
                        cropSelect.innerHTML = '<option value="">-- Select Crop --</option>';
                        data.forEach(crop => {
                            cropSelect.innerHTML += `<option value="${crop.id}">${crop.name}</option>`;
                        });
                        cropSelect.disabled = false;
                        cropLoading.classList.add('d-none');
                    })
                    .catch(error => {
                        console.error('Error loading crops:', error);
                        cropSelect.innerHTML = '<option value="">Error loading crops</option>';
                        cropSelect.disabled = false;
                        cropLoading.classList.add('d-none');
                    });
            } else {
                cropSelect.innerHTML = '<option value="">-- Select Crop Type First --</option>';
                cropSelect.disabled = true;
            }
        });

        // Ethiopian Date Converter
        function gregorianToEthiopian(date) {
            const gregorianDate = new Date(date);
            const ethiopianEpoch = 1723856;
            const gregorianEpoch = 1721426;

            const julianDay = Math.floor(gregorianDate.getTime() / 86400000) + 2440588;
            const diff = julianDay - ethiopianEpoch;

            const year = Math.floor((diff + 365) / 1461) * 4 + Math.floor((diff % 1461) / 365);
            const month = Math.floor((diff % 365) / 30) + 1;
            const day = (diff % 365) % 30 + 1;

            return {
                year: year + 1,
                month: month,
                day: day
            };
        }

        function getEthiopianMonthName(month) {
            const months = [
                'Meskerem', 'Tikimit', 'Hidar', 'Tahesas', 'Tir', 'Yekatit',
                'Megabit', 'Miazia', 'Genbot', 'Sene', 'Hamle', 'Nehase', 'Pagume'
            ];
            return months[month - 1] || '';
        }

        function getEthiopianDayName(day) {
            const days = ['Ehud', 'Segno', 'Maksegno', 'Irob', 'Hamus', 'Arb', 'Kidame'];
            return days[day % 7] || '';
        }

        function updateEthiopianDate() {
            const now = new Date();
            const ethiopian = gregorianToEthiopian(now);
            const monthName = getEthiopianMonthName(ethiopian.month);
            const dayName = getEthiopianDayName(ethiopian.day);

            const ethiopianDateElement = document.getElementById('current-ethiopian-date');
            if (ethiopianDateElement) {
                ethiopianDateElement.textContent =
                    `${dayName}, ${ethiopian.day} ${monthName} ${ethiopian.year}`;
            }
        }

        // Form submission feedback
        document.querySelector('form').addEventListener('submit', function(e) {
            // Show success toast
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        });

        // Add subtle animations to form elements
        document.addEventListener('DOMContentLoaded', function() {
            const formControls = document.querySelectorAll('.form-control, .form-select');

            formControls.forEach(control => {
                control.addEventListener('focus', function() {
                    this.parentElement.classList.add('focus-shadow');
                });

                control.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focus-shadow');
                });
            });

            // Initialize Ethiopian date
            updateEthiopianDate();
        });
    </script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
