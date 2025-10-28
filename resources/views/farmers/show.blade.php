<x-layout>
<div class="container py-5">
    <h2 class="fw-bold text-success mb-4">Farmer Details</h2>

    <div class="card p-4 shadow-sm border-0 rounded-3">
        <p><strong>Name:</strong> {{ $farmer->farmer_name }}</p>
        <p><strong>Gender:</strong> {{ $farmer->gender }}</p>
        <p><strong>Phone:</strong> {{ $farmer->phone_number }}</p>
        <p><strong>Region:</strong> {{ $farmer->region }}</p>
        <p><strong>Zone:</strong> {{ $farmer->zone->name ?? '-' }}</p>
        <p><strong>Woreda:</strong> {{ $farmer->woreda->name ?? '-' }}</p>
        <p><strong>Kebele:</strong> {{ $farmer->kebele->name ?? '-' }}</p>
        <p><strong>Union:</strong> {{ $farmer->union->name ?? '-' }}</p>
        <p><strong>Association:</strong> {{ $farmer->association->name ?? '-' }}</p>
        <p><strong>House Number:</strong> {{ $farmer->house_number }}</p>
        <p><strong>Field Description:</strong> {{ $farmer->field_description }}</p>
        <p><strong>Landholding Size:</strong> {{ $farmer->landholding_size }}</p>
        <p><strong>GPS Coordinates:</strong> {{ $farmer->gps_coordinates }}</p>
        <p><strong>Irrigation Type:</strong> {{ $farmer->irrigation_type }}</p>
        <p><strong>Soil Type:</strong> {{ $farmer->soil_type }}</p>

        <a href="{{ route('farmers.edit', $farmer->id) }}" class="btn btn-warning mt-3">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('farmers.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</div>
</x-layout>
