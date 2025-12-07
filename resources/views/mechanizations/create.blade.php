<x-layout>
    <div class="content-header p-3">
        <div class="container-fluid">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-tractor text-success mr-2"></i> Add Mechanization
                            </h1>
                            <div class="mt-1">
                                <span class="badge badge-light text-dark">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    All fields marked with <span class="text-danger">*</span> are required
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('sector.mechanizations.index') }}">Mechanizations</a></li>
                                <li class="breadcrumb-item active">Add Mechanization</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <form action="{{ route('sector.mechanizations.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-info-circle mr-2"></i> Basic Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Name Dropdown -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-tag"></i>
                                                    </span>
                                                </div>
                                                <select name="name" class="form-control" required>
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
                                    </div>

                                    <!-- Mechanization Type Dropdown -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Mechanization Type</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-gear"></i>
                                                    </span>
                                                </div>
                                                <select name="mechanization_type" class="form-control">
                                                    <option value="">-- Select Type --</option>
                                                    <option value="dhuunfaa" {{ old('mechanization_type', $mechanization->mechanization_type ?? '') == 'dhuunfaa' ? 'selected' : '' }}>Dhuunfaa</option>
                                                    <option value="waldaa" {{ old('mechanization_type', $mechanization->mechanization_type ?? '') == 'waldaa' ? 'selected' : '' }}>Waldaa</option>
                                                </select>
                                            </div>
                                            @error('mechanization_type')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-hashtag"></i>
                                                    </span>
                                                </div>
                                                <input type="number" step="0.01" name="amount"
                                                    class="form-control"
                                                    value="{{ old('amount', $mechanization->amount ?? '') }}" placeholder="0.00">
                                            </div>
                                            @error('amount')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Year <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <select name="year" class="form-control" required>
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
                                            <small class="text-muted mt-1">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Current: {{ $currentEthiopianYear }} EC • Gregorian: {{ date('Y') }}
                                            </small>
                                            @error('year')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Information Section -->
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-cash-register mr-2"></i> Loan Information
                                    <span class="badge badge-light ml-2">Optional</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> Note:</h5>
                                    Liqii hafe = Liqii waliigala - Liqii deebi
                                </div>

                                <div id="loanContainer">
                                    <!-- Liqii waliigalaa -->
                                    <div class="loan-item border rounded p-3 mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Loan Type</label>
                                                    <input type="text" class="form-control bg-light" value="Liqii waliigalaa" readonly>
                                                    <input type="hidden" name="loan_type[]" value="Liqii waliigalaa">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" step="0.01" name="loan_amount[]" class="form-control loan-amount" 
                                                        placeholder="0.00" value="{{ old('loan_amount.0') }}" data-type="waliigala">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Liqii deebi'e -->
                                    <div class="loan-item border rounded p-3 mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Loan Type</label>
                                                    <input type="text" class="form-control bg-light" value="Liqii deebi'e" readonly>
                                                    <input type="hidden" name="loan_type[]" value="Liqii deebi'e">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" step="0.01" name="loan_amount[]" class="form-control loan-amount" 
                                                        placeholder="0.00" value="{{ old('loan_amount.1') }}" data-type="deebi">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Liqii hafe (Auto-calculated) -->
                                    <div class="loan-item border rounded p-3 mb-3 bg-light">
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Loan Type</label>
                                                    <input type="text" class="form-control bg-light" value="Liqii hafe" readonly>
                                                    <input type="hidden" name="loan_type[]" value="Liqii hafe">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" step="0.01" name="loan_amount[]" class="form-control bg-light" 
                                                        id="liqiiHafeAmount" value="0.00" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calculator mr-1"></i>
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
                                            <div class="row align-items-end">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Loan Type</label>
                                                        <select name="loan_type[]" class="form-control">
                                                            <option value="">-- Select Loan Type --</option>
                                                            <option value="Qusannaa" {{ old('loan_type.'.$i) == 'Qusannaa' ? 'selected' : '' }}>Qusannaa</option>
                                                            <option value="Other" {{ old('loan_type.'.$i) == 'Other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="number" step="0.01" name="loan_amount[]" class="form-control" 
                                                            placeholder="0.00" value="{{ old('loan_amount.'.$i) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="button" class="btn btn-danger btn-block remove-loan">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <button type="button" id="addLoanBtn" class="btn btn-outline-success btn-sm mt-2">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Additional Loan
                                </button>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="card">
                            <div class="card-footer text-right">
                                <a href="{{ route('sector.mechanizations.index') }}" class="btn btn-default mr-2">
                                    <i class="fas fa-arrow-left mr-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle mr-2"></i> Save Mechanization
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                
                if (hafeInput) {
                    hafeInput.value = hafe.toFixed(2);
                }
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
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Loan Type</label>
                                <select name="loan_type[]" class="form-control">
                                    <option value="">-- Select Loan Type --</option>
                                    <option value="Qusannaa">Qusannaa</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="loan_amount[]" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block remove-loan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
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
            border-left: 4px solid #28a745 !important;
        }
        
        .loan-item.bg-light {
            border-left: 4px solid #6c757d !important;
        }
        
        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        .additional-loan {
            border-left: 4px solid #17a2b8 !important;
        }
        
        .card .card-header {
            border-bottom: 0;
            color: #fff;
        }
        
        .card-primary .card-header {
            background-color: #007bff;
        }
        
        .card-success .card-header {
            background-color: #28a745;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-group label {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
    </style>
</x-layout>