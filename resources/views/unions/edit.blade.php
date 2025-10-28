<x-layout>
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark fw-bold">Edit Union</div>
            <div class="card-body">
                <form action="{{ route('unions.update', $union->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Union Name</label>
                        <input type="text" name="name" value="{{ $union->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Federation</label>
                        <select name="federation_id" class="form-select" required>
                            @foreach ($federations as $federation)
                                <option value="{{ $federation->id }}" @selected($union->federation_id == $federation->id)>
                                    {{ $federation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Zone</label>
                        <select name="zone_id" class="form-select" required>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" @selected($union->zone_id == $zone->id)>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <button class="btn btn-warning">Update</button>
                    <a href="{{ route('unions.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-layout>
