
<x-layout>
<div class="container">
    <h1>Compost Type Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $compostType->id }}</h5>
            <p class="card-text">Name: {{ $compostType->name }}</p>
            <p class="card-text">Created At: {{ $compostType->created_at }}</p>
            <p class="card-text">Updated At: {{ $compostType->updated_at }}</p>
        </div>
    </div>

    <a href="{{ route('compost-types.index') }}" class="btn btn-primary mt-3">Back to List</a>
</div>
</x-layout>