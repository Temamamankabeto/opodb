<x-layout>
    @section('title', 'Edit Association Expansion Plan')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-primary">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Association Expansion Plan
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sector.association-expansion-plans.update', $plan->id) }}" method="POST" id="mainForm">
                    @csrf
                    @method('PUT')

                    <!-- Year Selection -->
                    <div class="card card-success mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Year Selection
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label font-weight-semibold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control" id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" 
                                                    {{ old('year', $plan->year) == $year ? 'selected' : '' }}>
                                                    {{ $year }} EC
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('year')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Association Expansion Data -->
                    <div class="card card-success mb-4">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users mr-2"></i>
                                Association Expansion Data
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="callout callout-info mb-4">
                                <h5><i class="fas fa-info-circle mr-2"></i> Required Information</h5>
                                <p class="mb-0">Select association types and enter their expansion data. At least one association type must be selected.</p>
                            </div>

                            <div class="row" id="associationsContainer">
                                @foreach ($associations as $association)
                                    @php
                                        // Check if this association was previously selected
                                        $existingData = null;
                                        if (is_array($plan->expansion_data)) {
                                            $existingData = collect($plan->expansion_data)->firstWhere('association_type_id', $association->id);
                                        }
                                        $isChecked = $existingData !== null;
                                        $numberOfNumber = $isChecked ? $existingData['number_of_number'] : '';
                                        
                                        // For type 1, get all field values
                                        if ($isChecked && $association->id == 1) {
                                            $members = $existingData['number_of_members'] ?? '';
                                            $capital = $existingData['total_capital'] ?? '';
                                            $loansTaken = $existingData['fertilizer_loans_taken'] ?? '';
                                            $loanRepayment = $existingData['fertilizer_loan_repayment'] ?? '';
                                        } else {
                                            $members = '';
                                            $capital = '';
                                            $loansTaken = '';
                                            $loanRepayment = '';
                                        }
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="card association-item {{ $isChecked ? 'checked' : '' }}">
                                            <div class="card-body">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input association-checkbox" 
                                                           type="checkbox"
                                                           value="{{ $association->id }}" 
                                                           id="association-{{ $association->id }}"
                                                           data-association-name="{{ $association->name }}"
                                                           data-association-type="{{ $association->name }}"
                                                           data-association-type-id="{{ $association->id }}"
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-semibold" for="association-{{ $association->id }}">
                                                        {{ $association->name }}
                                                    </label>
                                                    @if($association->description)
                                                        <div class="form-text small text-muted">{{ $association->description }}</div>
                                                    @endif
                                                </div>

                                                <div class="association-fields" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                                    <div class="row">
                                                        <!-- For association type ID 1: Show ALL fields -->
                                                        <div class="col-12 type-1-fields" style="display: {{ $association->id == 1 ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label class="form-label">Woldaa <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control association-number-of-number"
                                                                    placeholder="0" min="0" 
                                                                    value="{{ $numberOfNumber }}"
                                                                    {{ $isChecked && $association->id == 1 ? '' : 'disabled' }}>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Miseensa <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control association-members"
                                                                    placeholder="0" min="0" 
                                                                    value="{{ $members }}"
                                                                    {{ $isChecked && $association->id == 1 ? '' : 'disabled' }}>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Kaappitaala (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control association-capital"
                                                                    placeholder="0.00" min="0" 
                                                                    value="{{ $capital }}"
                                                                    {{ $isChecked && $association->id == 1 ? '' : 'disabled' }}>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Liqii Xaaóo Fudhatame (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control association-loans-taken"
                                                                    placeholder="0.00" min="0" 
                                                                    value="{{ $loansTaken }}"
                                                                    {{ $isChecked && $association->id == 1 ? '' : 'disabled' }}>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">Liqii Xaaóo kaaffalame (ETB) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control association-loan-repayment"
                                                                    placeholder="0.00" min="0" 
                                                                    value="{{ $loanRepayment }}"
                                                                    {{ $isChecked && $association->id == 1 ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- For other association types: Show ONLY Number of Number -->
                                                        <div class="col-12 other-type-fields" style="display: {{ $association->id != 1 ? 'block' : 'none' }};">
                                                            <div class="form-group">
                                                                <label class="form-label">Woldaalee <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control association-number-of-number"
                                                                    placeholder="0" min="0" 
                                                                    value="{{ $numberOfNumber }}"
                                                                    {{ $isChecked && $association->id != 1 ? '' : 'disabled' }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($associations->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No association types found.
                                </div>
                            @endif

                            @error('expansion_data')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                            <input type="hidden" name="expansion_data" id="expansion_data" value="{{ old('expansion_data') }}">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <a href="{{ route('sector.association-expansion-plans.index') }}" class="btn btn-default mr-2">
                                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                            <i class="fas fa-save mr-2"></i>Update Expansion Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const yearSelect = document.getElementById('yearSelect');
        const submitBtn = document.getElementById('submitBtn');
        const expansionInput = document.getElementById('expansion_data');
        const selected = new Map();

        // Initialize with existing data
        function initializeExistingData() {
            @if(is_array($plan->expansion_data))
                @foreach($plan->expansion_data as $association)
                    selected.set('{{ $association['association_type_id'] }}', {
                        association_type_id: '{{ $association['association_type_id'] }}',
                        association_name: '{{ $association['association_name'] }}',
                        association_type: '{{ $association['association_type'] }}',
                        number_of_number: '{{ $association['number_of_number'] }}',
                        number_of_members: '{{ $association['number_of_members'] ?? 0 }}',
                        total_capital: '{{ $association['total_capital'] ?? 0 }}',
                        fertilizer_loans_taken: '{{ $association['fertilizer_loans_taken'] ?? 0 }}',
                        fertilizer_loan_repayment: '{{ $association['fertilizer_loan_repayment'] ?? 0 }}'
                    });
                @endforeach
                updateFormState();
            @endif
        }

        document.querySelectorAll('.association-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const parent = this.closest('.association-item');
                const associationId = this.value;
                const associationName = this.getAttribute('data-association-name');
                const associationType = this.getAttribute('data-association-type');
                const associationTypeId = this.getAttribute('data-association-type-id');
                const fields = parent.querySelector('.association-fields');
                const type1Fields = parent.querySelector('.type-1-fields');
                const otherTypeFields = parent.querySelector('.other-type-fields');
                
                // Get ALL input fields for both sections
                const numberInput1 = type1Fields ? type1Fields.querySelector('.association-number-of-number') : null;
                const membersInput = type1Fields ? type1Fields.querySelector('.association-members') : null;
                const capitalInput = type1Fields ? type1Fields.querySelector('.association-capital') : null;
                const loansTakenInput = type1Fields ? type1Fields.querySelector('.association-loans-taken') : null;
                const loanRepaymentInput = type1Fields ? type1Fields.querySelector('.association-loan-repayment') : null;
                
                const numberInput2 = otherTypeFields ? otherTypeFields.querySelector('.association-number-of-number') : null;

                if (this.checked) {
                    fields.style.display = 'block';
                    parent.classList.add('checked');
                    
                    // Show different fields based on association type ID
                    if (associationTypeId === '1') {
                        // Type 1: Show ALL fields
                        if (type1Fields) type1Fields.style.display = 'block';
                        if (otherTypeFields) otherTypeFields.style.display = 'none';
                        
                        // Enable all inputs for type 1
                        if (numberInput1) numberInput1.disabled = false;
                        if (membersInput) membersInput.disabled = false;
                        if (capitalInput) capitalInput.disabled = false;
                        if (loansTakenInput) loansTakenInput.disabled = false;
                        if (loanRepaymentInput) loanRepaymentInput.disabled = false;
                        
                        // Disable and clear other type fields
                        if (numberInput2) {
                            numberInput2.disabled = true;
                        }
                        
                        // If not already in selected, add it with current values
                        if (!selected.has(associationId)) {
                            selected.set(associationId, {
                                association_type_id: associationTypeId,
                                association_name: associationName,
                                association_type: associationType,
                                number_of_number: numberInput1 ? numberInput1.value : '',
                                number_of_members: membersInput ? membersInput.value : '',
                                total_capital: capitalInput ? capitalInput.value : '',
                                fertilizer_loans_taken: loansTakenInput ? loansTakenInput.value : '',
                                fertilizer_loan_repayment: loanRepaymentInput ? loanRepaymentInput.value : ''
                            });
                        }
                    } else {
                        // Other types: Show only number of number
                        if (type1Fields) type1Fields.style.display = 'none';
                        if (otherTypeFields) otherTypeFields.style.display = 'block';
                        
                        // Enable only number input for other types
                        if (numberInput2) numberInput2.disabled = false;
                        
                        // Disable type 1 fields
                        if (numberInput1) numberInput1.disabled = true;
                        if (membersInput) membersInput.disabled = true;
                        if (capitalInput) capitalInput.disabled = true;
                        if (loansTakenInput) loansTakenInput.disabled = true;
                        if (loanRepaymentInput) loanRepaymentInput.disabled = true;
                        
                        // If not already in selected, add it with current values
                        if (!selected.has(associationId)) {
                            selected.set(associationId, {
                                association_type_id: associationTypeId,
                                association_name: associationName,
                                association_type: associationType,
                                number_of_number: numberInput2 ? numberInput2.value : '',
                                number_of_members: 0,
                                total_capital: 0,
                                fertilizer_loans_taken: 0,
                                fertilizer_loan_repayment: 0
                            });
                        }
                    }
                } else {
                    fields.style.display = 'none';
                    parent.classList.remove('checked');
                    
                    // Disable all inputs
                    if (numberInput1) numberInput1.disabled = true;
                    if (membersInput) membersInput.disabled = true;
                    if (capitalInput) capitalInput.disabled = true;
                    if (loansTakenInput) loansTakenInput.disabled = true;
                    if (loanRepaymentInput) loanRepaymentInput.disabled = true;
                    if (numberInput2) numberInput2.disabled = true;
                    
                    selected.delete(associationId);
                }
                updateFormState();
            });
        });

        document.addEventListener('input', e => {
            if (e.target.classList.contains('association-number-of-number') || 
                e.target.classList.contains('association-members') || 
                e.target.classList.contains('association-capital') ||
                e.target.classList.contains('association-loans-taken') ||
                e.target.classList.contains('association-loan-repayment')) {
                
                const item = e.target.closest('.association-item');
                const associationId = item.querySelector('.association-checkbox').value;
                const associationName = item.querySelector('.association-checkbox').getAttribute('data-association-name');
                const associationType = item.querySelector('.association-checkbox').getAttribute('data-association-type');
                const associationTypeId = item.querySelector('.association-checkbox').getAttribute('data-association-type-id');
                
                if (selected.has(associationId)) {
                    const data = {
                        association_type_id: associationTypeId,
                        association_name: associationName,
                        association_type: associationType
                    };
                    
                    if (associationTypeId === '1') {
                        // Type 1: Get values from type 1 fields
                        const type1Fields = item.querySelector('.type-1-fields');
                        if (type1Fields) {
                            data.number_of_number = type1Fields.querySelector('.association-number-of-number').value || '';
                            data.number_of_members = type1Fields.querySelector('.association-members').value || '';
                            data.total_capital = type1Fields.querySelector('.association-capital').value || '';
                            data.fertilizer_loans_taken = type1Fields.querySelector('.association-loans-taken').value || '';
                            data.fertilizer_loan_repayment = type1Fields.querySelector('.association-loan-repayment').value || '';
                        }
                    } else {
                        // Other types: Get values from other type fields
                        const otherTypeFields = item.querySelector('.other-type-fields');
                        if (otherTypeFields) {
                            data.number_of_number = otherTypeFields.querySelector('.association-number-of-number').value || '';
                            data.number_of_members = 0;
                            data.total_capital = 0;
                            data.fertilizer_loans_taken = 0;
                            data.fertilizer_loan_repayment = 0;
                        }
                    }
                    
                    selected.set(associationId, data);
                }
                updateFormState();
            }
        });

        function updateFormState() {
            // Check if at least one association is selected
            const hasSelectedAssociations = selected.size > 0;
            
            if (!hasSelectedAssociations) {
                // No associations selected, still allow form submission with empty data
                expansionInput.value = JSON.stringify([]);
                submitBtn.disabled = !yearSelect.value;
                
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                }
                return;
            }
            
            // If associations are selected, validate them
            const data = Array.from(selected.values()).map(assoc => {
                if (assoc.association_type_id === '1') {
                    // Type 1: Include data even if some fields are empty
                    return {
                        association_type_id: assoc.association_type_id,
                        association_name: assoc.association_name,
                        association_type: assoc.association_type,
                        number_of_number: assoc.number_of_number || 0,
                        number_of_members: assoc.number_of_members || 0,
                        total_capital: assoc.total_capital || 0,
                        fertilizer_loans_taken: assoc.fertilizer_loans_taken || 0,
                        fertilizer_loan_repayment: assoc.fertilizer_loan_repayment || 0
                    };
                } else {
                    // Other types
                    return {
                        association_type_id: assoc.association_type_id,
                        association_name: assoc.association_name,
                        association_type: assoc.association_type,
                        number_of_number: assoc.number_of_number || 0,
                        number_of_members: 0,
                        total_capital: 0,
                        fertilizer_loans_taken: 0,
                        fertilizer_loan_repayment: 0
                    };
                }
            });
            
            expansionInput.value = JSON.stringify(data);
            submitBtn.disabled = !yearSelect.value;
            
            // Visual feedback
            if (submitBtn.disabled) {
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-secondary');
            } else {
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-primary');
            }
        }

        yearSelect.addEventListener('change', updateFormState);

        // Handle form submission to ensure data is valid
        document.getElementById('mainForm').addEventListener('submit', function(e) {
            updateFormState();
            const data = Array.from(selected.values()).map(assoc => {
                if (assoc.association_type_id === '1') {
                    return {
                        association_type_id: assoc.association_type_id,
                        association_name: assoc.association_name,
                        association_type: assoc.association_type,
                        number_of_number: assoc.number_of_number || 0,
                        number_of_members: assoc.number_of_members || 0,
                        total_capital: assoc.total_capital || 0,
                        fertilizer_loans_taken: assoc.fertilizer_loans_taken || 0,
                        fertilizer_loan_repayment: assoc.fertilizer_loan_repayment || 0
                    };
                } else {
                    return {
                        association_type_id: assoc.association_type_id,
                        association_name: assoc.association_name,
                        association_type: assoc.association_type,
                        number_of_number: assoc.number_of_number || 0,
                        number_of_members: 0,
                        total_capital: 0,
                        fertilizer_loans_taken: 0,
                        fertilizer_loan_repayment: 0
                    };
                }
            });
            
            if (!yearSelect.value) {
                e.preventDefault();
                alert('Please select a year before submitting.');
                return;
            }
            
            // Always allow submission even with empty data
            expansionInput.value = JSON.stringify(data);
        });

        // Initialize
        initializeExistingData();
        updateFormState();
    });
    </script>

    <style>
    .association-item {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #dee2e6;
    }

    .association-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .association-item.checked {
        border-color: #28a745;
        background-color: #f8fff9;
    }

    .association-fields {
        border-left: 3px solid #17a2b8;
        padding-left: 15px;
        margin-left: -5px;
    }

    .custom-control-input:checked ~ .custom-control-label {
        color: #28a745;
        font-weight: bold;
    }

    .is-valid {
        border-color: #28a745 !important;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }
    
    /* AdminLTE card header custom styling */
    .card-success:not(.card-outline) > .card-header {
        background-color: #28a745;
        color: white;
    }
    
    .card-success:not(.card-outline) > .card-header .card-title {
        color: white;
    }
    
    /* Ensure all card headers are green */
    .card > .card-header {
        background-color: #28a745 !important;
        color: white !important;
    }
    
    .card > .card-header .card-title {
        color: white !important;
    }
    
    /* Form group spacing */
    .form-group {
        margin-bottom: 1rem;
    }
    
    /* Custom checkbox styling */
    .custom-checkbox .custom-control-label::before {
        border-radius: .25rem;
    }
    
    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745;
        border-color: #28a745;
    }
    </style>
</x-layout>