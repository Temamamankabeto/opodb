
<x-layout>
<div class="container">
    <h1>Create New Compost Type</h1>

    <form action="{{ route('compost-types.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('compost-types.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-layout>