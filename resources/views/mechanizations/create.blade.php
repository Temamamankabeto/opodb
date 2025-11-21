<x-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-tractor text-success me-2"></i>Add Mechanization</h3>
            <div class="badge bg-light text-dark fs-6">
                <i class="bi bi-info-circle me-1"></i>
                All fields marked with <span class="text-danger">*</span> are required
            </div>
        </div>

        <form action="{{ route('sector.mechanizations.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <!-- Basic Information Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i> Basic Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Name Dropdown -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-tag text-muted"></i>
                                </span>
                                <select name="name" class="form-select border-start-0 ps-0" required>
                                    <option value="">-- Select Name --</option>
                                    <option value="Tiraaktera" {{ old('name', $mechanization->name ?? '') == 'Tiraaktera' ? 'selected' : '' }}>Tiraaktera</option>
                                    <option value="Kombaayinara" {{ old('name', $mechanization->name ?? '') == 'Kombaayinara' ? 'selected' : '' }}>Kombaayinara</option>
                                    <option value="Paampii" {{ old('name', $mechanization->name ?? '') == 'Paampii' ? 'selected' : '' }}>Paampii</option>
                                </select>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mechanization Type Dropdown -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Mechanization Type</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-gear text-muted"></i>
                                </span>
                                <select name="mechanization_type" class="form-select border-start-0 ps-0">
                                    <option value="">-- Select Type --</option>
                                    <option value="dhuunfaa" {{ old('mechanization_type', $mechanization->mechanization_type ?? '') == 'dhuunfaa' ? 'selected' : '' }}>Dhuunfaa</option>
                                    <option value="waldaa" {{ old('mechanization_type', $mechanization->mechanization_type ?? '') == 'waldaa' ? 'selected' : '' }}>Waldaa</option>
                                </select>
                            </div>
                            @error('mechanization_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-123 text-muted"></i>
                                </span>
                                <input type="number" step="0.01" name="amount"
                                    class="form-control border-start-0 ps-0"
                                    value="{{ old('amount', $mechanization->amount ?? '') }}" placeholder="0.00">
                            </div>
                            @error('amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
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
                                            {{ old('year', $mechanization->year ?? $currentEthiopianYear) == $year ? 'selected' : '' }}>
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
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin me-2"></i> Loan Information 
                        <span class="badge bg-light text-dark ms-2">Optional</span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Note:</strong> Liqii hafe = Liqii waliigala - Liqii deebi
                    </div>

                    <div id="loanContainer">
                        <!-- Liqii waliigalaa -->
                        <div class="loan-item border rounded p-3 mb-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Loan Type</label>
                                    <input type="text" class="form-control bg-light" value="Liqii waliigalaa" readonly>
                                    <input type="hidden" name="loan_type[]" value="Liqii waliigalaa">
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Amount</label>
                                    <input type="number" step="0.01" name="loan_amount[]" class="form-control loan-amount" 
                                        placeholder="0.00" value="{{ old('loan_amount.0') }}" data-type="waliigala">
                                </div>
                                
                                
                            </div>
                        </div>

                        <!-- Liqii deebi'e -->
                        <div class="loan-item border rounded p-3 mb-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Loan Type</label>
                                    <input type="text" class="form-control bg-light" value="Liqii deebi'e" readonly>
                                    <input type="hidden" name="loan_type[]" value="Liqii deebi'e">
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Amount</label>
                                    <input type="number" step="0.01" name="loan_amount[]" class="form-control loan-amount" 
                                        placeholder="0.00" value="{{ old('loan_amount.1') }}" data-type="deebi">
                                </div>
                               
                                
                            </div>
                        </div>

                        <!-- Liqii hafe (Auto-calculated) -->
                        <div class="loan-item border rounded p-3 mb-3 bg-light">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Loan Type</label>
                                    <input type="text" class="form-control bg-light" value="Liqii hafe" readonly>
                                    <input type="hidden" name="loan_type[]" value="Liqii hafe">
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Amount</label>
                                    <input type="number" step="0.01" name="loan_amount[]" class="form-control bg-light" 
                                        id="liqiiHafeAmount" value="0.00" readonly>
                                </div>
                                 
                                
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-calculator me-1"></i>
                                    Auto-calculated: Liqii waliigala - Liqii deebi
                                </small>
                            </div>
                        </div>

                        <!-- Additional Loans (Qusannaa and others) -->
                        @php
                            $additionalLoans = old('loan_type', []);
                            // Remove the first 3 fixed loans from additional loans count
                            $additionalLoanCount = max(0, count($additionalLoans) - 3);
                        @endphp

                        @for($i = 3; $i < $additionalLoanCount + 3; $i++)
                            <div class="loan-item border rounded p-3 mb-3 additional-loan">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Loan Type</label>
                                        <select name="loan_type[]" class="form-select">
                                            <option value="">-- Select Loan Type --</option>
                                            <option value="Qusannaa" {{ old('loan_type.'.$i) == 'Qusannaa' ? 'selected' : '' }}>Qusannaa</option>
                                            <option value="Other" {{ old('loan_type.'.$i) == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                     
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Amount</label>
                                        <input type="number" step="0.01" name="loan_amount[]" class="form-control" 
                                            placeholder="0.00" value="{{ old('loan_amount.'.$i) }}">
                                    </div>
                                   
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-loan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <button type="button" id="addLoanBtn" class="btn btn-outline-success btn-sm mt-2">
                        <i class="bi bi-plus-circle me-1"></i>Add Additional Loan
                    </button>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-3 justify-content-end border-top pt-4">
                <a href="{{ route('sector.mechanizations.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-circle me-2"></i>Save Mechanization
                </button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate Liqii hafe when loan amounts change
            function calculateLiqiiHafe() {
                const waliigalaInput = document.querySelector('input[data-type="waliigala"]');
                const deebiInput = document.querySelector('input[data-type="deebi"]');
                const hafeInput = document.getElementById('liqiiHafeAmount');
                
                const waliigala = parseFloat(waliigalaInput?.value) || 0;
                const deebi = parseFloat(deebiInput?.value) || 0;
                const hafe = Math.max(0, waliigala - deebi);
                
                hafeInput.value = hafe.toFixed(2);
            }

            // Add event listeners to loan amount inputs
            document.querySelectorAll('.loan-amount').forEach(input => {
                input.addEventListener('input', calculateLiqiiHafe);
                input.addEventListener('change', calculateLiqiiHafe);
            });

            // Initial calculation
            calculateLiqiiHafe();

            // Add additional loan row
            document.getElementById('addLoanBtn').addEventListener('click', function() {
                const container = document.getElementById('loanContainer');
                const row = document.createElement('div');
                row.classList.add('loan-item', 'border', 'rounded', 'p-3', 'mb-3', 'additional-loan');
                row.innerHTML = `
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Loan Type</label>
                            <select name="loan_type[]" class="form-select">
                                <option value="">-- Select Loan Type --</option>
                                <option value="Qusannaa">Qusannaa</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                         
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Amount</label>
                            <input type="number" step="0.01" name="loan_amount[]" class="form-control" placeholder="0.00">
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

            // Remove loan row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-loan') || e.target.closest('.remove-loan')) {
                    const button = e.target.classList.contains('remove-loan') ? e.target : e.target.closest('.remove-loan');
                    const loanItem = button.closest('.additional-loan');
                    if (loanItem) {
                        loanItem.remove();
                    }
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
        });
    </script>

    <style>
        .loan-item {
            background: #f8f9fa;
            border-left: 4px solid #198754 !important;
        }
        
        .loan-item.bg-light {
            border-left: 4px solid #6c757d !important;
        }
        
        .form-control:read-only {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        .additional-loan {
            border-left: 4px solid #0dcaf0 !important;
        }
    </style>
</x-layout>