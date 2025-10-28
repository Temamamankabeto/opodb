<x-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-briefcase"></i> Kebele Economic Sector Yearly Plans</h2>
            <a href="{{ route('sector.economic.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Plan
            </a>
        </div>

        {{-- ✅ Success Message --}}
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ✅ Check if plans exist --}}
        @if($plans->isEmpty())
        <div class="alert alert-info text-center">No economic plans found.</div>
        @else
        @php
        // Group plans by year
        $groupedPlans = $plans->groupBy('year');
        @endphp

        {{-- ✅ Loop through years --}}
        @foreach($groupedPlans as $year => $yearPlans)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    📅 Economic Sector Plans for <strong>{{ $year }}</strong>
                </h5>
                <span class="badge bg-light text-dark">{{ $yearPlans->count() }} Plans</span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Activity</th>
                                <th>Target Group</th>
                                <th>Timeline</th>
                                <th>Budget (ETB)</th>
                                <th>Responsible Staff</th>
                                <th>Expected Outcome</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearPlans as $index => $plan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $plan->activity }}</td>
                                <td>{{ $plan->target_group }}</td>
                                <td>{{ $plan->timeline }}</td>
                                <td>{{ number_format($plan->budget ?? 0, 2) }}</td>
                                <td>{{ $plan->responsible_staff ?? '-' }}</td>
                                <td>{{ $plan->expected_outcome ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('sector.economic.edit', $plan->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('sector.economic.destroy', $plan->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</x-layout>