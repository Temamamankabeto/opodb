<x-layout>
    <div class="container py-5">
        <h2 class="mb-4">
            <i class="bi bi-pencil-square text-primary me-2"></i>
            Edit Employment Opportunity Plan
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

        <form action="{{ route('sector.employment-opportunity-plans.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Year and Basic Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Plan Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Year, Job Opportunity, and Enterprise Work cannot be changed for existing plans.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="text" class="form-control" value="{{ $plan->year }} EC" readonly 
                                   style="background-color: #f8f9fa;">
                            <input type="hidden" name="year" value="{{ $plan->year }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Job Opportunity</label>
                            <input type="text" class="form-control" 
                                   value="{{ $plan->jobOpportunity->name ?? 'N/A' }}" readonly 
                                   style="background-color: #f8f9fa;">
                            <input type="hidden" name="job_opportunity_id" value="{{ $plan->job_opportunity_id }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Enterprise Work</label>
                            <input type="text" class="form-control" 
                                   value="{{ $plan->enterpriseWork->name ?? 'N/A' }}" readonly 
                                   style="background-color: #f8f9fa;">
                            <input type="hidden" name="enterprise_work_id" value="{{ $plan->enterprise_work_id }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Data -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Employment Data</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $data = $plan->opportunity_data;
                    @endphp
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Number of Enterprises <span class="text-danger">*</span></label>
                            <input type="number" name="number_of_enterprises" class="form-control" 
                                   value="{{ old('number_of_enterprises', $data['number_of_enterprises'] ?? '') }}" 
                                   placeholder="0" min="0" required>
                            @error('number_of_enterprises')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Male Members <span class="text-danger">*</span></label>
                            <input type="number" name="male_members" class="form-control" 
                                   value="{{ old('male_members', $data['male_members'] ?? '') }}" 
                                   placeholder="0" min="0" required>
                            @error('male_members')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Female Members <span class="text-danger">*</span></label>
                            <input type="number" name="female_members" class="form-control" 
                                   value="{{ old('female_members', $data['female_members'] ?? '') }}" 
                                   placeholder="0" min="0" required>
                            @error('female_members')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Total Members</label>
                            <input type="text" class="form-control" 
                                   value="{{ (old('male_members', $data['male_members'] ?? 0) + old('female_members', $data['female_members'] ?? 0)) }}" 
                                   readonly style="background-color: #f8f9fa;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <a href="{{ route('sector.employment-opportunity-plans.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i>Update Employment Plan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate total members when male or female members change
            const maleInput = document.querySelector('input[name="male_members"]');
            const femaleInput = document.querySelector('input[name="female_members"]');
            const totalInput = document.querySelector('input[readonly]');

            function updateTotal() {
                const male = parseInt(maleInput.value) || 0;
                const female = parseInt(femaleInput.value) || 0;
                totalInput.value = male + female;
            }

            maleInput.addEventListener('input', updateTotal);
            femaleInput.addEventListener('input', updateTotal);
        });
    </script>
</x-layout>