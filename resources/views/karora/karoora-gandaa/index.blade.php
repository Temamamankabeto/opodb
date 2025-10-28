<x-layout>
    <div class="container mt-4">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <h2 class="mb-4 text-primary">Land Farming Plans</h2>

        <!-- Success Message -->
        @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        </script>
        @endif

        <!-- No Data Message -->
        @if($groupedData->isEmpty())
        <div class="alert alert-info">No land farming plans found.</div>
        @else
        @foreach ($groupedData as $year => $seasons)
        <div class="year-group mb-5">
            <h3 class="bg-dark text-white p-2 rounded">{{ $year }}</h3>

            @foreach ($seasons as $season => $cropTypes)
            <div class="season-group ms-3 mb-4">
                <h4 class="bg-secondary text-white p-2 rounded">{{ ucfirst($season) }}</h4>

                @foreach ($cropTypes as $cropTypeKey => $crops)
                @php
                [$cropTypeId, $cropTypeName] = explode('-', $cropTypeKey, 2);
                @endphp
                <div class="crop-type-group ms-4 mb-3">
                    <h5 class="bg-light p-2 border-start border-3 border-primary">{{ $cropTypeName }}</h5>

                    @foreach ($crops as $cropKey => $plans)
                    @php
                    [$cropId, $cropName] = explode('-', $cropKey, 2);
                    @endphp
                    <div class="crop-group ms-5 mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 border rounded">
                            <h6 class="mb-0">{{ $cropName }}</h6>
                            <span class="badge bg-primary">{{ $plans->count() }} plans</span>
                        </div>

                        <table class="table table-sm table-bordered mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>Land (ha)</th>
                                    <th>Production (Qt)</th>
                                    <th>Productivity (Qt/ha)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $plan)
                                <tr>
                                    <td>{{ number_format($plan->land_hectares, 2) }}</td>
                                    <td>{{ number_format($plan->production, 2) }}</td>
                                    <td>{{ number_format($plan->productivity, 2) }}</td>
                                    <td>
                                        <a href="{{ route('land-plans.edit', $plan->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>

                                        <form action="{{ route('land-plans.destroy', $plan->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this plan?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach

                                <!-- Totals -->
                                <tr class="table-secondary fw-bold">
                                    <td>{{ number_format($plans->sum('land_hectares'), 2) }}</td>
                                    <td>{{ number_format($plans->sum('production'), 2) }}</td>
                                    <td>
                                        {{ $plans->sum('land_hectares') > 0
                                                                ? number_format($plans->sum('production') / $plans->sum('land_hectares'), 2)
                                                                : '0.00' }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $landPlans->links() }}
        </div>
        @endif

        <!-- Add New Button -->
        <div class="text-end mt-3">
            <a href="{{ route('land-plans.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Plan
            </a>
        </div>
    </div>
</x-layout>