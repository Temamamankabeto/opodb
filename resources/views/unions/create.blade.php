<x-layout>
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white fw-bold">Add New Union</div>
            <div class="card-body">
                <form action="{{ route('unions.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Union Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Federation</label>
                        <select name="federation_id" class="form-select" required>
                            <option value="">-- Select Federation --</option>
                            @foreach ($federations as $federation)
                                <option value="{{ $federation->id }}">{{ $federation->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Zone</label>
                        <select name="zone_id" class="form-select" required>
                            <option value="">-- Select Zone --</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <button class="btn btn-success">Save</button>
                    <a href="{{ route('unions.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-layout>
