<x-layout>
    <div class="container py-4">
        <h3 class="text-success fw-bold mb-3">
            Crop Plan Details for {{ $plan->farmer->farmer_name }}
        </h3>

        <table class="table table-bordered">
            <tr><th>Year</th><td>{{ $plan->year }}</td></tr>
            <tr><th>Crop Type</th><td>{{ $plan->cropType->name }}</td></tr>
            <tr><th>Crop</th><td>{{ $plan->crop->name }}</td></tr>
            <tr><th>Season</th><td>{{ $plan->season }}</td></tr>
            <tr><th>Area (ha)</th><td>{{ $plan->area }}</td></tr>
            <tr><th>Productivity (qt/ha)</th><td>{{ $plan->productivity_per_ha }}</td></tr>
            <tr><th>Expected Yield (qt)</th><td>{{ $plan->expected_yield }}</td></tr>
        </table>

        @if(!empty($plan->fertilizer_recommendations))
            <h5 class="mt-4 text-success fw-bold">Fertilizer Recommendations</h5>
            <table class="table table-bordered mt-2">
                <thead>
                    <tr>
                        <th>Fertilizer</th>
                        <th>Required (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plan->fertilizer_recommendations as $rec)
                        <tr>
                            <td>{{ $rec['fertilizer'] }}</td>
                            <td>{{ $rec['required_kg'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('farmers.show', $plan->farmer->id) }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</x-layout>
