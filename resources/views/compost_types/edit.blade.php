
<x-layout>
<div class="container">
    <h1>Edit Compost Type</h1>

    <form action="{{ route('compost-types.update', $compostType->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $compostType->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('compost-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>