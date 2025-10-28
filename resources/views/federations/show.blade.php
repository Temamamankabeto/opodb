<x-layout>
<div class="container py-5">
    <h2>Federation Details</h2>

    <div class="card mt-3 p-4 shadow">
        <p><strong>ID:</strong> {{ $federation->id }}</p>
        <p><strong>Name:</strong> {{ $federation->name }}</p>
        <p><strong>Created:</strong> {{ $federation->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="mt-3">
        <a href="{{ route('federations.edit', $federation->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('federations.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
</x-layout>
