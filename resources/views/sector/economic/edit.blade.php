<x-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4">Edit Economic Plan ({{ $plan->year }})</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sector.economic.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control" value="{{ old('year', $plan->year) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Activity</label>
                <input type="text" name="activity" class="form-control" value="{{ old('activity', $plan->activity) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Target Group</label>
                <input type="text" name="target_group" class="form-control" value="{{ old('target_group', $plan->target_group) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Timeline</label>
                <input type="text" name="timeline" class="form-control" value="{{ old('timeline', $plan->timeline) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Budget (ETB)</label>
                <input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget', $plan->budget) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Responsible Staff</label>
                <input type="text" name="responsible_staff" class="form-control" value="{{ old('responsible_staff', $plan->responsible_staff) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Expected Outcome</label>
                <textarea name="expected_outcome" rows="3" class="form-control">{{ old('expected_outcome', $plan->expected_outcome) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Plan</button>
                <a href="{{ route('sector.economic.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>
