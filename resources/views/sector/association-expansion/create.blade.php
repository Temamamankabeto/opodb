<x-layout>
<div class="container py-5">
    <h2 class="mb-4">
        <i class="bi bi-graph-up-arrow text-success me-2"></i>
        Create Association Expansion Plan
    </h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sector.association-expansion-plans.store') }}" method="POST" id="mainForm">
        @csrf

        <!-- Year Selection -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Year Selection</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
                        <select name="year" class="form-select" id="yearSelect" required>
                            <option value="">-- Select Year --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
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

        <!-- Association Expansion Data -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Association Expansion Data</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info py-2 mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Select association types and enter their expansion data. At least one association type must be selected.
                </div>

                <div class="row g-3" id="associationsContainer">
                    @foreach ($associations as $association)
                        <div class="col-md-6">
                            <div class="card border association-item">
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input association-checkbox" 
                                               type="checkbox"
                                               value="{{ $association->id }}" 
                                               id="association-{{ $association->id }}"
                                               data-association-name="{{ $association->name }}"
                                               data-association-type="{{ $association->name }}"
                                               data-association-type-id="{{ $association->id }}">
                                        <label class="form-check-label fw-semibold" for="association-{{ $association->id }}">
                                            {{ $association->name }}
                                        </label>
                                        @if($association->description)
                                            <div class="form-text small text-muted">{{ $association->description }}</div>
                                        @endif
                                    </div>

                                    <div class="association-fields" style="display: none;">
                                        <div class="row g-2">
                                            <!-- For association type ID 1: Show ALL fields -->
                                            <div class="col-12 type-1-fields" style="display: none;">
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small">Number of Number <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control association-number-of-number"
                                                            placeholder="0" min="0" disabled>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Number of Members <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control association-members"
                                                            placeholder="0" min="0" disabled>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Total Capital (ETB) <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control association-capital"
                                                            placeholder="0.00" min="0" disabled>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Fertilizer Loans Taken (ETB) <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control association-loans-taken"
                                                            placeholder="0.00" min="0" disabled>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Fertilizer Loan Repayment (ETB) <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" class="form-control association-loan-repayment"
                                                            placeholder="0.00" min="0" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- For other association types: Show ONLY Number of Number -->
                                            <div class="col-12 other-type-fields" style="display: none;">
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label small">Number of Number <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control association-number-of-number"
                                                            placeholder="0" min="0" disabled>
                                                    </div>
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
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No association types found.
                    </div>
                @endif

                @error('expansion_data')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
                <input type="hidden" name="expansion_data" id="expansion_data">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 border-top pt-4">
            <a href="{{ route('sector.association-expansion-plans.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-success px-4" id="submitBtn" disabled>
                <i class="bi bi-save me-2"></i>Save Expansion Plan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const yearSelect = document.getElementById('yearSelect');
    const submitBtn = document.getElementById('submitBtn');
    const expansionInput = document.getElementById('expansion_data');
    const selected = new Map();

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
                
                console.log('Association Type ID:', associationTypeId);
                
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
                        numberInput2.value = '';
                    }
                    
                    selected.set(associationId, {
                        association_type_id: associationTypeId,
                        association_name: associationName,
                        association_type: associationType,
                        number_of_number: '',
                        number_of_members: '',
                        total_capital: '',
                        fertilizer_loans_taken: '',
                        fertilizer_loan_repayment: ''
                    });
                } else {
                    // Other types: Show only number of number
                    if (type1Fields) type1Fields.style.display = 'none';
                    if (otherTypeFields) otherTypeFields.style.display = 'block';
                    
                    // Enable only number input for other types
                    if (numberInput2) numberInput2.disabled = false;
                    
                    // Disable and clear type 1 fields
                    if (numberInput1) {
                        numberInput1.disabled = true;
                        numberInput1.value = '';
                    }
                    if (membersInput) {
                        membersInput.disabled = true;
                        membersInput.value = '';
                    }
                    if (capitalInput) {
                        capitalInput.disabled = true;
                        capitalInput.value = '';
                    }
                    if (loansTakenInput) {
                        loansTakenInput.disabled = true;
                        loansTakenInput.value = '';
                    }
                    if (loanRepaymentInput) {
                        loanRepaymentInput.disabled = true;
                        loanRepaymentInput.value = '';
                    }
                    
                    selected.set(associationId, {
                        association_type_id: associationTypeId,
                        association_name: associationName,
                        association_type: associationType,
                        number_of_number: '',
                        number_of_members: 0,
                        total_capital: 0,
                        fertilizer_loans_taken: 0,
                        fertilizer_loan_repayment: 0
                    });
                }
            } else {
                fields.style.display = 'none';
                
                // Disable all inputs
                if (numberInput1) numberInput1.disabled = true;
                if (membersInput) membersInput.disabled = true;
                if (capitalInput) capitalInput.disabled = true;
                if (loansTakenInput) loansTakenInput.disabled = true;
                if (loanRepaymentInput) loanRepaymentInput.disabled = true;
                if (numberInput2) numberInput2.disabled = true;
                
                // Clear all inputs
                if (numberInput1) numberInput1.value = '';
                if (membersInput) membersInput.value = '';
                if (capitalInput) capitalInput.value = '';
                if (loansTakenInput) loansTakenInput.value = '';
                if (loanRepaymentInput) loanRepaymentInput.value = '';
                if (numberInput2) numberInput2.value = '';
                
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
        const data = Array.from(selected.values()).map(assoc => {
            if (assoc.association_type_id === '1') {
                // Type 1: Validate ALL fields
                return assoc.number_of_number && 
                       assoc.number_of_members && 
                       assoc.total_capital && 
                       assoc.fertilizer_loans_taken && 
                       assoc.fertilizer_loan_repayment ? assoc : null;
            } else {
                // Other types: Validate only number_of_number
                return assoc.number_of_number ? assoc : null;
            }
        }).filter(assoc => assoc !== null);
        
        expansionInput.value = JSON.stringify(data);
        submitBtn.disabled = !(yearSelect.value && data.length > 0);
        
        // Visual feedback
        if (submitBtn.disabled) {
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        } else {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        }

        // Add validation styling
        document.querySelectorAll('.association-item').forEach(item => {
            const checkbox = item.querySelector('.association-checkbox');
            const associationId = checkbox.value;
            const associationTypeId = checkbox.getAttribute('data-association-type-id');
            
            if (checkbox.checked && selected.has(associationId)) {
                if (associationTypeId === '1') {
                    // Type 1: Validate ALL fields in type-1-fields section
                    const type1Fields = item.querySelector('.type-1-fields');
                    if (type1Fields) {
                        const inputs = type1Fields.querySelectorAll('input[type="number"]');
                        inputs.forEach(input => {
                            if (input.value) {
                                input.classList.remove('is-invalid');
                                input.classList.add('is-valid');
                            } else {
                                input.classList.remove('is-valid');
                                input.classList.add('is-invalid');
                            }
                        });
                    }
                } else {
                    // Other types: Validate only number of number in other-type-fields section
                    const otherTypeFields = item.querySelector('.other-type-fields');
                    if (otherTypeFields) {
                        const numberInput = otherTypeFields.querySelector('.association-number-of-number');
                        if (numberInput.value) {
                            numberInput.classList.remove('is-invalid');
                            numberInput.classList.add('is-valid');
                        } else {
                            numberInput.classList.remove('is-valid');
                            numberInput.classList.add('is-invalid');
                        }
                    }
                }
            } else {
                // Remove validation styling for unchecked items
                const inputs = item.querySelectorAll('input[type="number"]');
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
            }
        });
    }

    yearSelect.addEventListener('change', updateFormState);

    // Initialize form with old values if validation failed
    function initializeOldValues() {
        @if(old('expansion_data'))
            try {
                const oldData = @json(json_decode(old('expansion_data'), true));
                if (Array.isArray(oldData)) {
                    oldData.forEach(item => {
                        const checkbox = document.querySelector(`.association-checkbox[value="${item.association_type_id}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            checkbox.dispatchEvent(new Event('change'));
                            
                            const parent = checkbox.closest('.association-item');
                            const associationTypeId = checkbox.getAttribute('data-association-type-id');
                            
                            if (associationTypeId === '1') {
                                // Type 1: Set all fields in type-1-fields section
                                const type1Fields = parent.querySelector('.type-1-fields');
                                if (type1Fields) {
                                    type1Fields.querySelector('.association-number-of-number').value = item.number_of_number || '';
                                    type1Fields.querySelector('.association-members').value = item.number_of_members || '';
                                    type1Fields.querySelector('.association-capital').value = item.total_capital || '';
                                    type1Fields.querySelector('.association-loans-taken').value = item.fertilizer_loans_taken || '';
                                    type1Fields.querySelector('.association-loan-repayment').value = item.fertilizer_loan_repayment || '';
                                }
                            } else {
                                // Other types: Set only number of number in other-type-fields section
                                const otherTypeFields = parent.querySelector('.other-type-fields');
                                if (otherTypeFields) {
                                    otherTypeFields.querySelector('.association-number-of-number').value = item.number_of_number || '';
                                }
                            }
                            
                            selected.set(item.association_type_id, item);
                        }
                    });
                }
            } catch (e) {
                console.error('Error initializing old values:', e);
            }
        @endif
        updateFormState();
    }

    initializeOldValues();
});
</script>

<style>
.association-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.association-item:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
}

.association-item.checked {
    border-color: #198754;
    background-color: #f8fff9;
}

.association-fields {
    border-left: 3px solid #0dcaf0;
    padding-left: 10px;
}

.form-check-input:checked ~ .form-check-label {
    color: #198754;
    font-weight: bold;
}

.is-valid {
    border-color: #198754 !important;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.btn:disabled {
    cursor: not-allowed;
}
</style>
</x-layout>