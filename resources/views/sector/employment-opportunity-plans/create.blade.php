<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid py-3">
                <h2 class="mb-4">
                    <i class="fas fa-briefcase text-primary mr-2"></i>
                    Create Employment Opportunity Plan
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

                <form action="{{ route('sector.employment-opportunity-plans.store') }}" method="POST" id="mainForm">
                    @csrf

                    <!-- Year Selection -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i>Year Selection</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">Year <span class="text-danger">*</span></label>
                                    <select name="year" class="form-control" id="yearSelect" required>
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

                    <!-- Existing Plans Alert -->
                    <div class="alert alert-warning" id="existingPlansAlert" style="display: none;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="existingPlansMessage"></span>
                    </div>

                    <!-- Job Opportunities -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="mb-0"><i class="fas fa-briefcase mr-2"></i>Job Opportunities & Enterprise Works</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info py-2 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Select job opportunities and assign enterprise works to them. Fill in the employment details for each enterprise work.
                            </div>

                            <div id="opportunitiesContainer">
                                @foreach ($jobOpportunities as $jobOpportunity)
                                    <div class="card border mb-3 opportunity-item">
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input opportunity-checkbox" type="checkbox" 
                                                    value="{{ $jobOpportunity->id }}" 
                                                    id="opportunity-{{ $jobOpportunity->id }}"
                                                    data-opportunity-name="{{ $jobOpportunity->name }}">
                                                <label class="form-check-label font-weight-bold" for="opportunity-{{ $jobOpportunity->id }}">
                                                    {{ $jobOpportunity->name }}
                                                </label>
                                                @if ($jobOpportunity->description)
                                                    <div class="form-text small text-muted">{{ $jobOpportunity->description }}</div>
                                                @endif
                                            </div>

                                            <!-- Enterprise Works Selection -->
                                            <div class="enterprise-works-container" style="display: none;">
                                                <label class="form-label font-weight-bold mb-2">Select Enterprise Works for this Opportunity:</label>
                                                @foreach ($enterpriseWorks as $enterpriseWork)
                                                    <div class="card border mt-2 enterprise-work-item">
                                                        <div class="card-body">
                                                            <div class="form-check mb-3">
                                                                <input class="form-check-input enterprise-work-checkbox" type="checkbox" 
                                                                    value="{{ $enterpriseWork->id }}"
                                                                    id="work-{{ $jobOpportunity->id }}-{{ $enterpriseWork->id }}"
                                                                    data-work-name="{{ $enterpriseWork->name }}"
                                                                    data-opportunity-id="{{ $jobOpportunity->id }}">
                                                                <label class="form-check-label font-weight-bold" for="work-{{ $jobOpportunity->id }}-{{ $enterpriseWork->id }}">
                                                                    {{ $enterpriseWork->name }}
                                                                </label>
                                                                @if ($enterpriseWork->description)
                                                                    <div class="form-text small text-muted">{{ $enterpriseWork->description }}</div>
                                                                @endif
                                                            </div>

                                                            <!-- Input Fields -->
                                                            <div class="enterprise-work-fields" style="display: none;">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <label class="form-label small">Number of Enterprises <span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control enterprise-number" 
                                                                            placeholder="0" min="0" disabled>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label small">Male Members <span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control male-members" 
                                                                            placeholder="0" min="0" disabled>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label small">Female Members <span class="text-danger">*</span></label>
                                                                        <input type="number" class="form-control female-members" 
                                                                            placeholder="0" min="0" disabled>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="form-label small">Total Members</label>
                                                                        <input type="text" class="form-control total-members" 
                                                                            placeholder="0" readonly style="background-color: #f8f9fa;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($jobOpportunities->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No job opportunities found.
                                </div>
                            @endif

                            @if ($enterpriseWorks->isEmpty())
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No enterprise works found.
                                </div>
                            @endif

                            <input type="hidden" name="opportunity_data" id="opportunity_data">
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white py-3">
                            <h5 class="mb-0"><i class="fas fa-calculator mr-2"></i>Employment Summary</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-primary mb-1">Total Enterprises</h6>
                                        <h4 class="text-primary mb-0" id="totalEnterprises">0</h4>
                                        <small class="text-muted">across all works</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-success mb-1">Male Members</h6>
                                        <h4 class="text-success mb-0" id="totalMaleMembers">0</h4>
                                        <small class="text-muted">employed</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-warning mb-1">Female Members</h6>
                                        <h4 class="text-warning mb-0" id="totalFemaleMembers">0</h4>
                                        <small class="text-muted">employed</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <h6 class="text-info mb-1">Total Members</h6>
                                        <h4 class="text-info mb-0" id="grandTotalMembers">0</h4>
                                        <small class="text-muted">employed</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-4">
                        <a href="{{ route('sector.employment-opportunity-plans.index') }}" class="btn btn-outline-secondary mr-3">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                            <i class="fas fa-save mr-2"></i>Save Employment Plan
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const yearSelect = document.getElementById('yearSelect');
            const submitBtn = document.getElementById('submitBtn');
            const opportunityDataInput = document.getElementById('opportunity_data');
            const existingPlansAlert = document.getElementById('existingPlansAlert');
            const existingPlansMessage = document.getElementById('existingPlansMessage');

            // Store data for opportunities
            const opportunitiesData = new Map();

            // Check for existing plans when year changes
            yearSelect.addEventListener('change', async function() {
                const year = this.value;

                if (!year) {
                    hideExistingPlansAlert();
                    enableAllOpportunities();
                    updateFormState();
                    return;
                }

                try {
                    const response = await fetch(`/sector/employment-opportunity-plans/check-existing?year=${year}`);
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                    const data = await response.json();
                    
                    if (data.exists) {
                        showExistingPlansAlert(data.plans);
                        disableOpportunitiesWithExistingData(data.plans);
                    } else {
                        hideExistingPlansAlert();
                        enableAllOpportunities();
                    }
                } catch (error) {
                    console.error('Error checking existing plans:', error);
                    hideExistingPlansAlert();
                    enableAllOpportunities();
                }

                updateFormState();
            });

            function showExistingPlansAlert(plans) {
                const opportunityNames = plans.map(plan => plan.job_opportunity.name).join(', ');
                existingPlansMessage.textContent = `Plans already exist for ${yearSelect.value} EC for opportunities: ${opportunityNames}. These opportunities are disabled.`;
                existingPlansAlert.style.display = 'block';
            }

            function hideExistingPlansAlert() {
                existingPlansAlert.style.display = 'none';
            }

            function disableOpportunitiesWithExistingData(plans) {
                const existingOpportunityIds = plans.map(plan => plan.job_opportunity_id.toString());
                
                document.querySelectorAll('.opportunity-checkbox').forEach(checkbox => {
                    const opportunityId = checkbox.value;
                    if (existingOpportunityIds.includes(opportunityId)) {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        checkbox.parentElement.style.opacity = '0.5';
                        
                        const worksContainer = checkbox.closest('.opportunity-item').querySelector('.enterprise-works-container');
                        if (worksContainer) {
                            worksContainer.style.display = 'none';
                        }

                        // Remove from data stores
                        opportunitiesData.delete(opportunityId);
                    } else {
                        checkbox.disabled = false;
                        checkbox.parentElement.style.opacity = '1';
                    }
                });

                updateFormState();
                updateFinancialSummary();
            }

            function enableAllOpportunities() {
                document.querySelectorAll('.opportunity-checkbox').forEach(checkbox => {
                    checkbox.disabled = false;
                    checkbox.checked = false;
                    checkbox.parentElement.style.opacity = '1';

                    const worksContainer = checkbox.closest('.opportunity-item').querySelector('.enterprise-works-container');
                    if (worksContainer) {
                        worksContainer.style.display = 'none';
                    }
                });

                document.querySelectorAll('.enterprise-work-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                    const fields = checkbox.closest('.enterprise-work-item').querySelector('.enterprise-work-fields');
                    if (fields) {
                        fields.style.display = 'none';
                        enableEnterpriseWorkInputs(checkbox.closest('.enterprise-work-item'), false);
                    }
                });

                opportunitiesData.clear();
                updateFinancialSummary();
            }

            // Opportunity checkbox event
            document.querySelectorAll('.opportunity-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.disabled) return;

                    const worksContainer = this.closest('.opportunity-item').querySelector('.enterprise-works-container');
                    const opportunityId = this.value;
                    const opportunityName = this.getAttribute('data-opportunity-name');

                    if (this.checked) {
                        worksContainer.style.display = 'block';
                        opportunitiesData.set(opportunityId, {
                            name: opportunityName,
                            enterprise_works: new Map()
                        });
                    } else {
                        worksContainer.style.display = 'none';
                        
                        // Uncheck all enterprise works and clear their data
                        worksContainer.querySelectorAll('.enterprise-work-checkbox').forEach(workCb => {
                            workCb.checked = false;
                            const workFields = workCb.closest('.enterprise-work-item').querySelector('.enterprise-work-fields');
                            if (workFields) {
                                workFields.style.display = 'none';
                                enableEnterpriseWorkInputs(workCb.closest('.enterprise-work-item'), false);
                            }
                        });

                        opportunitiesData.delete(opportunityId);
                    }
                    updateFormState();
                    updateFinancialSummary();
                });
            });

            // Enterprise work checkbox event
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('enterprise-work-checkbox')) {
                    const workItem = e.target.closest('.enterprise-work-item');
                    const workId = e.target.value;
                    const workName = e.target.getAttribute('data-work-name');
                    const opportunityId = e.target.getAttribute('data-opportunity-id');
                    const fields = workItem.querySelector('.enterprise-work-fields');

                    if (e.target.checked) {
                        fields.style.display = 'block';
                        enableEnterpriseWorkInputs(workItem, true);
                        
                        if (opportunitiesData.has(opportunityId)) {
                            opportunitiesData.get(opportunityId).enterprise_works.set(workId, {
                                name: workName,
                                number_of_enterprises: '',
                                male_members: '',
                                female_members: '',
                                total_members: 0
                            });
                        }
                    } else {
                        fields.style.display = 'none';
                        enableEnterpriseWorkInputs(workItem, false);
                        
                        if (opportunitiesData.has(opportunityId)) {
                            opportunitiesData.get(opportunityId).enterprise_works.delete(workId);
                        }
                    }
                    updateFormState();
                    updateFinancialSummary();
                }
            });

            function enableEnterpriseWorkInputs(workItem, enable) {
                const inputs = workItem.querySelectorAll('.enterprise-number, .male-members, .female-members');
                inputs.forEach(input => {
                    input.disabled = !enable;
                    if (!enable) {
                        input.value = '';
                        input.classList.remove('is-valid', 'is-invalid');
                    }
                });

                if (!enable) {
                    workItem.querySelector('.total-members').value = '0';
                } else {
                    updateEnterpriseWorkCalculations(workItem);
                }
            }

            // Update calculations when inputs change
            document.addEventListener('input', (e) => {
                if (e.target.classList.contains('enterprise-number') || 
                    e.target.classList.contains('male-members') || 
                    e.target.classList.contains('female-members')) {
                    
                    const workItem = e.target.closest('.enterprise-work-item');
                    updateEnterpriseWorkCalculations(workItem);

                    const workId = workItem.querySelector('.enterprise-work-checkbox').value;
                    const workName = workItem.querySelector('.enterprise-work-checkbox').getAttribute('data-work-name');
                    const opportunityId = workItem.querySelector('.enterprise-work-checkbox').getAttribute('data-opportunity-id');

                    if (opportunitiesData.has(opportunityId)) {
                        const number = workItem.querySelector('.enterprise-number').value;
                        const male = workItem.querySelector('.male-members').value;
                        const female = workItem.querySelector('.female-members').value;
                        const total = parseInt(male || 0) + parseInt(female || 0);

                        opportunitiesData.get(opportunityId).enterprise_works.set(workId, {
                            name: workName,
                            number_of_enterprises: number,
                            male_members: male,
                            female_members: female,
                            total_members: total
                        });

                        // Validation styling
                        validateEnterpriseWorkInputs(workItem, number, male, female);
                    }
                    updateFormState();
                    updateFinancialSummary();
                }
            });

            function updateEnterpriseWorkCalculations(workItem) {
                const male = parseInt(workItem.querySelector('.male-members').value) || 0;
                const female = parseInt(workItem.querySelector('.female-members').value) || 0;
                const total = male + female;

                workItem.querySelector('.total-members').value = total;
            }

            function validateEnterpriseWorkInputs(workItem, number, male, female) {
                const numberInput = workItem.querySelector('.enterprise-number');
                const maleInput = workItem.querySelector('.male-members');
                const femaleInput = workItem.querySelector('.female-members');

                const isValid = number && male && female;

                [numberInput, maleInput, femaleInput].forEach(input => {
                    if (isValid) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        if (!input.value) {
                            input.classList.add('is-invalid');
                        }
                    }
                });
            }

            function updateFinancialSummary() {
                let totalEnterprises = 0;
                let totalMaleMembers = 0;
                let totalFemaleMembers = 0;
                let grandTotalMembers = 0;

                opportunitiesData.forEach((opportunity, opportunityId) => {
                    opportunity.enterprise_works.forEach((work, workId) => {
                        if (work.number_of_enterprises && work.male_members && work.female_members) {
                            totalEnterprises += parseInt(work.number_of_enterprises);
                            totalMaleMembers += parseInt(work.male_members);
                            totalFemaleMembers += parseInt(work.female_members);
                            grandTotalMembers += parseInt(work.male_members) + parseInt(work.female_members);
                        }
                    });
                });

                document.getElementById('totalEnterprises').textContent = totalEnterprises.toLocaleString();
                document.getElementById('totalMaleMembers').textContent = totalMaleMembers.toLocaleString();
                document.getElementById('totalFemaleMembers').textContent = totalFemaleMembers.toLocaleString();
                document.getElementById('grandTotalMembers').textContent = grandTotalMembers.toLocaleString();
            }

            function updateFormState() {
                const hasYear = yearSelect.value;

                // Check if at least one enterprise work has all required fields
                let hasValidData = false;

                opportunitiesData.forEach((opportunity, opportunityId) => {
                    opportunity.enterprise_works.forEach((work, workId) => {
                        if (work.number_of_enterprises && work.male_members && work.female_members) {
                            hasValidData = true;
                        }
                    });
                });

                submitBtn.disabled = !(hasYear && hasValidData);

                // Visual feedback
                if (submitBtn.disabled) {
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                } else {
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                }
            }

            // Form submission
            document.getElementById('mainForm').addEventListener('submit', () => {
                const allOpportunities = [];

                opportunitiesData.forEach((opportunity, opportunityId) => {
                    const enterpriseWorks = [];
                    
                    opportunity.enterprise_works.forEach((work, workId) => {
                        if (work.number_of_enterprises && work.male_members && work.female_members) {
                            enterpriseWorks.push({
                                enterprise_work_id: workId,
                                number_of_enterprises: parseInt(work.number_of_enterprises),
                                male_members: parseInt(work.male_members),
                                female_members: parseInt(work.female_members),
                                total_members: parseInt(work.male_members) + parseInt(work.female_members)
                            });
                        }
                    });

                    if (enterpriseWorks.length > 0) {
                        allOpportunities.push({
                            job_opportunity_id: opportunityId,
                            enterprise_works: enterpriseWorks
                        });
                    }
                });

                opportunityDataInput.value = JSON.stringify(allOpportunities);
            });

            // Initialize with old values if validation failed
            function initializeOldValues() {
                @if (old('opportunity_data'))
                    try {
                        const oldOpportunities = @json(json_decode(old('opportunity_data'), true) ?? []);
                        // Implementation for initializing old values would go here
                    } catch (e) {
                        console.error('Error initializing old opportunity values:', e);
                    }
                @endif

                updateFormState();
                updateFinancialSummary();
            }

            initializeOldValues();
        });
    </script>

    <style>
        .opportunity-item, .enterprise-work-item {
            transition: all 0.3s ease;
        }

        .opportunity-item:hover, .enterprise-work-item:hover {
            background: #f8f9fa;
        }

        .form-check-input:checked ~ .form-check-label {
            font-weight: bold;
        }

        .opportunity-checkbox:checked ~ .form-check-label {
            color: #0dcaf0;
        }

        .enterprise-work-checkbox:checked ~ .form-check-label {
            color: #198754;
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

        .enterprise-work-fields {
            border-left: 3px solid #198754;
            padding-left: 12px;
        }
    </style>
</x-layout>