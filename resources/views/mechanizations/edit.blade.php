<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Mechanization</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        <form action="{{ route('sector.mechanizations.update', $mechanization->id) }}" method="POST"
            class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Basic Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Name Dropdown -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag text-muted"></i>
                                </span>
                                <select name="name" class="form-select border-start-0 ps-0" required>
                                    <option value="">-- Select Name --</option>
                                    <option value="Tiraaktera"
                                        {{ old('name', $mechanization->name) == 'Tiraaktera' ? 'selected' : '' }}>
                                        Tiraaktera</option>
                                    <option value="Kombaayinara"
                                        {{ old('name', $mechanization->name) == 'Kombaayinara' ? 'selected' : '' }}>
                                        Kombaayinara</option>
                                    <option value="Paampii"
                                        {{ old('name', $mechanization->name) == 'Paampii' ? 'selected' : '' }}>Paampii
                                    </option>
                                </select>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mechanization Type Dropdown -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mechanization Type</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-gear text-muted"></i>
                                </span>
                                <select name="mechanization_type" class="form-select border-start-0 ps-0">
                                    <option value="">-- Select Type --</option>
                                    <option value="dhuunfaa"
                                        {{ old('mechanization_type', $mechanization->mechanization_type) == 'dhuunfaa' ? 'selected' : '' }}>
                                        Dhuunfaa</option>
                                    <option value="waldaa"
                                        {{ old('mechanization_type', $mechanization->mechanization_type) == 'waldaa' ? 'selected' : '' }}>
                                        Waldaa</option>
                                </select>
                            </div>
                            @error('mechanization_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-123 text-muted"></i>
                                </span>
                                <input type="number" step="0.01" name="amount"
                                    class="form-control border-start-0 ps-0"
                                    value="{{ old('amount', $mechanization->amount) }}" placeholder="0.00">
                            </div>
                            @error('amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="col-md-4">
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
                                            {{ old('year', $mechanization->year) == $year ? 'selected' : '' }}>
                                            {{ $year }} EC
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Current: {{ $currentEthiopianYear }} EC • Gregorian: {{ date('Y') }}
                            </small>
                            @error('year')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Information Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-dollar me-2"></i>
                        Loan Information
                        <span class="badge bg-light text-dark ms-2">Optional</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        // Get loan data from loan_details JSON or individual columns
                        $loans = [];
                        if ($mechanization->loan_details) {
                            $loans = json_decode($mechanization->loan_details, true);
                        } elseif ($mechanization->loan_name || $mechanization->loan_type) {
                            $loans = [
                                [
                                    'loan_name' => $mechanization->loan_name,
                                    'loan_type' => $mechanization->loan_type,
                                    'loan_amount' => $mechanization->loan_amount,
                                    'loan_duration' => $mechanization->loan_duration,
                                    'loan_interest' => $mechanization->loan_interest,
                                ],
                            ];
                        }

                        // Handle old input for validation errors
                        $loanCount = count(old('loan_type', [])) ?: (count($loans) ?: 1);
                    @endphp

                    <div id="loanContainer">
                        @for ($i = 0; $i < $loanCount; $i++)
                            <div class="loan-item border rounded p-3 mb-3">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Loan Type</label>
                                        <select name="loan_type[]" class="form-select">
                                            <option value="">-- Select Loan Type --</option>
                                            <option value="Liqii waliigalaa"
                                                {{ old('loan_type.' . $i, $loans[$i]['loan_type'] ?? '') == 'Liqii waliigalaa' ? 'selected' : '' }}>
                                                Liqii waliigalaa</option>
                                            <option value="Liqii deebi'e"
                                                {{ old('loan_type.' . $i, $loans[$i]['loan_type'] ?? '') == 'Liqii deebi\'e' ? 'selected' : '' }}>
                                                Liqii deebi'e</option>
                                            <option value="Liqii hafe"
                                                {{ old('loan_type.' . $i, $loans[$i]['loan_type'] ?? '') == 'Liqii hafe' ? 'selected' : '' }}>
                                                Liqii hafe</option>
                                            <option value="Qusannaa"
                                                {{ old('loan_type.' . $i, $loans[$i]['loan_type'] ?? '') == 'Qusannaa' ? 'selected' : '' }}>
                                                Qusannaa</option>
                                        </select>
                                        @error('loan_type.' . $i)
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Amount</label>
                                        <input type="number" step="0.01" name="loan_amount[]" class="form-control"
                                            placeholder="0.00"
                                            value="{{ old('loan_amount.' . $i, $loans[$i]['loan_amount'] ?? '') }}">
                                        @error('loan_amount.' . $i)
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                     
                                    @if ($i > 0)
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-loan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>

                    <button type="button" id="addLoanBtn" class="btn btn-outline-warning btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i>Add Another Loan
                    </button>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end border-top pt-4">
                <a href="{{ route('sector.mechanizations.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-2"></i>Update Mechanization
                </button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        // Add/remove dynamic loan rows
        document.getElementById('addLoanBtn').addEventListener('click', function() {
            const container = document.getElementById('loanContainer');
            const row = document.createElement('div');
            row.classList.add('loan-item', 'border', 'rounded', 'p-3', 'mb-3');
            row.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Loan Name</label>
                        <input type="text" name="loan_name[]" class="form-control" placeholder="e.g., Commercial Bank Loan">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Loan Type</label>
                        <select name="loan_type[]" class="form-select">
                            <option value="">-- Select Loan Type --</option>
                            <option value="Liqii waliigalaa">Liqii waliigalaa</option>
                            <option value="Liqii deebi'e">Liqii deebi'e</option>
                            <option value="Liqii hafe">Liqii hafe</option>
                            <option value="Qusannaa">Qusannaa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="number" step="0.01" name="loan_amount[]" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Duration (months)</label>
                        <input type="number" name="loan_duration[]" class="form-control" placeholder="Months">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Interest (%)</label>
                        <input type="number" step="0.01" name="loan_interest[]" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-loan">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(row);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-loan') || e.target.closest('.remove-loan')) {
                const button = e.target.classList.contains('remove-loan') ? e.target : e.target.closest(
                    '.remove-loan');
                button.closest('.loan-item').remove();
            }
        });

        // Bootstrap form validation
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
</x-layout>
