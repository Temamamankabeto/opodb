<x-layout>
<div class="container py-5">
    <h2 class="mb-4">Association Details</h2>

    <div class="card shadow-sm border-0 rounded-3 p-4">
        <div class="mb-3">
            <strong>Name:</strong>
            <p>{{ $association->name }}</p>
        </div>

        <div class="mb-3">
            <strong>Federation:</strong>
            <p>{{ $association->federation->name ?? '-' }}</p>
        </div>

        <div class="mb-3">
            <strong>Union:</strong>
            <p>{{ $association->union->name ?? '-' }}</p>
        </div>

        <div class="mb-3">
            <strong>Zone:</strong>
            <p>{{ $association->zone->name ?? '-' }}</p>
        </div>

        <div class="mb-3">
            <strong>Woreda:</strong>
            <p>{{ $association->woreda->name ?? '-' }}</p>
        </div>

        <div class="mb-3">
            <strong>Kebele:</strong>
            <p>{{ $association->kebele->name ?? '-' }}</p>
        </div>

        <div class="mt-4">
            <a href="{{ route('associations.edit', $association->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('associations.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</x-layout>
