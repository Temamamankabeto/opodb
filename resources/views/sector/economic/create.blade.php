<x-layout>
    <div class="container py-5">
        <div class="col-lg-8 mx-auto">
            {{-- Page Title --}}
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-graph-up-arrow me-2"></i> Add Kebele Economic Sector Yearly Plan
                </h2>
                <p class="text-muted mb-0">Fill in the details below to create a new yearly plan.</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="alert alert-success shadow-sm border-0">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Error Messages --}}
            @if($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <h6 class="mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please correct the following errors:</h6>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Card Form --}}
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('sector.economic.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            {{-- Year --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Year</label>
                                <input type="number" name="year" class="form-control form-control-lg rounded-3"
                                    value="{{ old('year', date('Y')) }}" required>
                            </div>

                            {{-- Activity --}}
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Activity</label>
                                <input type="text" name="activity" class="form-control form-control-lg rounded-3"
                                    value="{{ old('activity') }}" placeholder="Enter activity name" required>
                            </div>

                            {{-- Target Group --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Target Group</label>
                                <input type="text" name="target_group" class="form-control rounded-3"
                                    value="{{ old('target_group') }}" placeholder="e.g., Farmers, Youth" required>
                            </div>

                            {{-- Timeline --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Timeline</label>
                                <input type="text" name="timeline" class="form-control rounded-3"
                                    value="{{ old('timeline') }}" placeholder="e.g., Jan–Mar" required>
                            </div>

                            {{-- Budget --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Budget (ETB)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">ETB</span>
                                    <input type="number" step="0.01" name="budget" class="form-control rounded-end-3"
                                        value="{{ old('budget') }}" placeholder="Enter budget amount">
                                </div>
                            </div>

                            {{-- Responsible Staff --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Responsible Staff</label>
                                <input type="text" name="responsible_staff" class="form-control rounded-3"
                                    value="{{ old('responsible_staff') }}" placeholder="Enter staff name">
                            </div>

                            {{-- Expected Outcome --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Expected Outcome</label>
                                <textarea name="expected_outcome" rows="3" class="form-control rounded-3"
                                    placeholder="Describe expected results">{{ old('expected_outcome') }}</textarea>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('sector.economic.index') }}" class="btn btn-outline-secondary btn-lg rounded-3">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm">
                                <i class="bi bi-save me-2"></i> Save Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>