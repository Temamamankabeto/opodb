<x-layout>
<div class="container py-4">
    <h2 class="fw-bold text-center mb-4">Add New Farmer</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('farmers.store') }}">
        @csrf

        <div class="card shadow p-4">
            <div class="row">
                <!-- Farmer Basic Info -->
                <div class="col-md-6 mb-3">
                    <label>Farmer Name</label>
                    <input type="text" name="farmer_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Region</label>
                    <input type="text" name="region" class="form-control">
                </div>

                <!-- Dependent Dropdowns -->
                <div class="col-md-6 mb-3">
                    <label>Zone</label>
                    <select name="zone_id" id="zone" class="form-select" required>
                        <option value="">-- Select Zone --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Woreda</label>
                    <select name="woreda_id" id="woreda" class="form-select" required>
                        <option value="">-- Select Woreda --</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Kebele</label>
                    <select name="kebele_id" id="kebele" class="form-select" required>
                        <option value="">-- Select Kebele --</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Union</label>
                    <select name="union_id" id="union" class="form-select" required>
                        <option value="">-- Select Union --</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Association</label>
                    <select name="association_id" id="association" class="form-select" required>
                        <option value="">-- Select Association --</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>House Number</label>
                    <input type="text" name="house_number" class="form-control">
                </div>
            </div>

            <!-- Land Details Section -->
            <h5 class="fw-bold mt-4 mb-2">Landholding Details</h5>
            <div id="land-container">
                <div class="row border p-3 rounded mb-3 land-item">
                    <div class="col-md-3">
                        <label>Land Type</label>
                        <select name="land_type_id[]" class="form-select land-type-select" required>
                            <option value="">-- Select --</option>
                            @foreach($landTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Size</label>
                        <input type="text" name="landholding_size[]" class="form-control" placeholder="e.g. 2.5 ha">
                    </div>
                    <div class="col-md-2">
                        <label>GPS X</label>
                        <input type="text" name="gps_x[]" class="form-control" placeholder="x coordinate">
                    </div>
                    <div class="col-md-2">
                        <label>GPS Y</label>
                        <input type="text" name="gps_y[]" class="form-control" placeholder="y coordinate">
                    </div>
                    <div class="col-md-2">
                        <label>Soil Type</label>
                        <select name="soil_type_id[]" class="form-select" required>
                            <option value="">-- Select Soil Type --</option>
                            @foreach($soilTypes as $soil)
                                <option value="{{ $soil->id }}">{{ $soil->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Irrigation Type</label>
                        <select name="irrigation_type[]" class="form-select">
                            <option value="Rain-fed">Rain-fed</option>
                            <option value="Irrigated">Irrigated</option>
                            <option value="Both">Both</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-land">X</button>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="button" id="add-land" class="btn btn-outline-success btn-sm">+ Add More Land</button>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Save Farmer</button>
            <a href="{{ route('farmers.index') }}" class="btn btn-secondary mt-4">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allLandTypes = @json($landTypes);
    const soilTypes = @json($soilTypes);

    function getAvailableLandTypes() {
        const selected = Array.from(document.querySelectorAll('.land-type-select'))
            .map(sel => sel.value)
            .filter(v => v !== '');
        return allLandTypes.filter(l => !selected.includes(l.id.toString()));
    }

    // Add More Land
    document.getElementById('add-land').addEventListener('click', function() {
        const available = getAvailableLandTypes();
        if (available.length === 0) {
            alert('All land types are already used!');
            return;
        }

        const container = document.getElementById('land-container');
        const html = `
            <div class="row border p-3 rounded mb-3 land-item">
                <div class="col-md-3">
                    <label>Land Type</label>
                    <select name="land_type_id[]" class="form-select land-type-select" required>
                        <option value="">-- Select --</option>
                        ${available.map(l => `<option value="${l.id}">${l.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Size</label>
                    <input type="text" name="landholding_size[]" class="form-control" placeholder="e.g. 2.5 ha">
                </div>
                <div class="col-md-2">
                    <label>GPS X</label>
                    <input type="text" name="gps_x[]" class="form-control" placeholder="x coordinate">
                </div>
                <div class="col-md-2">
                    <label>GPS Y</label>
                    <input type="text" name="gps_y[]" class="form-control" placeholder="y coordinate">
                </div>
                <div class="col-md-2">
                    <label>Soil Type</label>
                    <select name="soil_type_id[]" class="form-select" required>
                        <option value="">-- Select Soil Type --</option>
                        ${soilTypes.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Irrigation Type</label>
                    <select name="irrigation_type[]" class="form-select">
                        <option value="Rain-fed">Rain-fed</option>
                        <option value="Irrigated">Irrigated</option>
                        <option value="Both">Both</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-land">X</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    // Remove land item
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-land')) {
            e.target.closest('.land-item').remove();
        }
    });

    // Dependent dropdowns
    const zone = document.getElementById('zone');
    const woreda = document.getElementById('woreda');
    const kebele = document.getElementById('kebele');
    const union = document.getElementById('union');
    const association = document.getElementById('association');

    zone.addEventListener('change', function() {
        fetch(`/get-woredas/${this.value}`).then(res => res.json()).then(data => {
            woreda.innerHTML = '<option value="">-- Select Woreda --</option>';
            data.forEach(d => woreda.innerHTML += `<option value="${d.id}">${d.name}</option>`);
        });
        fetch(`/get-unions/${this.value}`).then(res => res.json()).then(data => {
            union.innerHTML = '<option value="">-- Select Union --</option>';
            data.forEach(d => union.innerHTML += `<option value="${d.id}">${d.name}</option>`);
        });
    });

    woreda.addEventListener('change', function() {
        fetch(`/get-kebeles/${this.value}`).then(res => res.json()).then(data => {
            kebele.innerHTML = '<option value="">-- Select Kebele --</option>';
            data.forEach(d => kebele.innerHTML += `<option value="${d.id}">${d.name}</option>`);
        });
    });

    union.addEventListener('change', function() {
        fetch(`/get-associations/${this.value}`).then(res => res.json()).then(data => {
            association.innerHTML = '<option value="">-- Select Association --</option>';
            data.forEach(d => association.innerHTML += `<option value="${d.id}">${d.name}</option>`);
        });
    });
});
</script>
</x-layout>
