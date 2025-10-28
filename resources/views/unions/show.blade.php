<x-layout>
<div class="container py-5">
    <h2 class="fw-bold text-success mb-4"><i class="bi bi-eye"></i> Union Details</h2>

    <div class="card shadow-sm border-0 rounded-3 p-4">
        <p><strong>ID:</strong> {{ $union->id }}</p>
        <p><strong>Union Name:</strong> {{ $union->name }}</p>
        <p><strong>Federation:</strong> {{ $union->federation->name }}</p>
        <p><strong>Created:</strong> {{ $union->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="mt-3">
        <a href="{{ route('unions.edit', $union->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('unions.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
</x-layout>
