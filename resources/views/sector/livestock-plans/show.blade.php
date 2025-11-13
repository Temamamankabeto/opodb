<x-layout>
<div class="container py-4">
    <h3 class="mb-3 text-info">Livestock Plan Details</h3>

    <table class="table table-bordered">
        <tr><th>Year</th><td>{{ $plan->year }}</td></tr>
        <tr><th>Kebele</th><td>{{ $plan->kebele->name ?? '-' }}</td></tr>
        <tr><th>Sector</th><td>{{ $plan->sector->name ?? '-' }}</td></tr>
        <tr><th>Supply Data</th><td><pre>{{ json_encode($plan->supply_data, JSON_PRETTY_PRINT) }}</pre></td></tr>
        <tr><th>Product Data</th><td><pre>{{ json_encode($plan->product_data, JSON_PRETTY_PRINT) }}</pre></td></tr>
    </table>

    <a href="{{ route('livestock_plans.index') }}" class="btn btn-secondary">Back</a>
</div>
</x-layout>
