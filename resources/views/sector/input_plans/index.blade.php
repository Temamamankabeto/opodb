<x-layout>
    <div class="container py-4">
        <h3 class="fw-bold text-center mb-3">Input Plans</h3>

        {{-- ✅ Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ✅ Add Button --}}
        <div class="mb-3 text-end">
            <a href="{{ route('sector.input-plans.create') }}" class="btn btn-success">+ Add Input Plan</a>
        </div>

        {{-- ✅ Input Plans Table --}}
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Year</th>
                    <th>Crop Type</th>
                    <th>Crop</th>
                    <th>Season</th>
                    <th>Seed (kg)</th>
                    <th>Fertilizers</th> {{-- ✅ Added --}}
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inputPlans as $plan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $plan->year }}</td>
                        <td>{{ $plan->cropType->name ?? '-' }}</td>
                        <td>{{ $plan->crop->name ?? '-' }}</td>
                        <td>{{ ucfirst($plan->season) }}</td>
                        <td class="text-end">{{ $plan->seed_required }}</td>

                        {{-- ✅ Fertilizer Display --}}
                        <td>
                            @php
                                $fertilizerDetails = $plan->fertilizer_details ?? [];
                            @endphp

                            @if (!empty($fertilizerDetails) && is_array($fertilizerDetails))
                                <ul class="mb-0 ps-3">
                                    @foreach ($fertilizerDetails as $detail)
                                        @php
                                            $fertType = \App\Models\FertilizerType::find($detail['fertilizer_type_id']);
                                            $fert = \App\Models\Fertilizer::find($detail['fertilizer_id']);
                                        @endphp
                                        <li>
                                            <strong>{{ $fertType->name ?? 'Type?' }}:</strong>
                                            {{ $fert->name ?? 'Unknown' }} —
                                            <span class="text-muted">
                                                {{ $detail['amount'] ?? '?' }} {{ $detail['unit'] ?? '' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">No fertilizers</span>
                            @endif
                        </td>

                        {{-- ✅ Action Buttons --}}
                        <td class="text-center">
                            <a href="{{ route('sector.input-plans.edit', $plan->id) }}"
                                class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('sector.input-plans.destroy', $plan->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Delete this plan?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">No input plans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
