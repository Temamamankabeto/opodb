<x-layout>
<div class="container py-5">
    <h2 class="mb-4">Edit Farmer</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('farmers.update', $farmer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Farmer Basic Info -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Farmer Name</label>
                <input type="text" name="farmer_name" class="form-control" value="{{ $farmer->farmer_name }}" required>
            </div>
            <div class="col-md-4">
                <label>Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="Male" {{ $farmer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $farmer->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ $farmer->phone }}">
            </div>
        </div>

        <!-- Location -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Zone</label>
                <select name="zone_id" id="zone_id" class="form-select" required>
                    <option value="">-- Select Zone --</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ $farmer->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Woreda</label>
                <select name="woreda_id" id="woreda_id" class="form-select" required>
                    <option value="">-- Select Woreda --</option>
                    @foreach($woredas as $woreda)
                        <option value="{{ $woreda->id }}" {{ $farmer->woreda_id == $woreda->id ? 'selected' : '' }}>{{ $woreda->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Kebele</label>
                <select name="kebele_id" id="kebele_id" class="form-select" required>
                    <option value="">-- Select Kebele --</option>
                    @foreach($kebeles as $kebele)
                        <option value="{{ $kebele->id }}" {{ $farmer->kebele_id == $kebele->id ? 'selected' : '' }}>{{ $kebele->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Union</label>
                <select name="union_id" id="union_id" class="form-select" required>
                    <option value="">-- Select Union --</option>
                    @foreach($unions as $union)
                        <option value="{{ $union->id }}" {{ $farmer->union_id == $union->id ? 'selected' : '' }}>{{ $union->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Association</label>
                <select name="association_id" id="association_id" class="form-select" required>
                    <option value="">-- Select Association --</option>
                    @foreach($associations as $association)
                        <option value="{{ $association->id }}" {{ $farmer->association_id == $association->id ? 'selected' : '' }}>{{ $association->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>House Number</label>
                <input type="text" name="house_number" class="form-control" value="{{ $farmer->house_number }}">
            </div>
        </div>

        <hr>

        <!-- Land Details -->
        <h4>Land Details</h4>
        <div id="land-container">
            @php
                $landDetails = json_decode($farmer->land_details, true) ?? [];
            @endphp
            @foreach($landDetails as $land)
                <div class="row border p-3 rounded mb-3 land-item">
                    <div class="col-md-2">
                        <label>Land Type</label>
                        <select name="land_type_id[]" class="form-select land-type-select" required>
                            <option value="">-- Select --</option>
                            @foreach($landTypes as $type)
                                <option value="{{ $type->id }}" {{ $type->id == ($land['land_type_id'] ?? '') ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Size</label>
                        <input type="number" step="0.01" name="landholding_size[]" class="form-control" value="{{ $land['size'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label>GPS X</label>
                        <input type="text" name="gps_x[]" class="form-control" value="{{ $land['gps_x'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label>GPS Y</label>
                        <input type="text" name="gps_y[]" class="form-control" value="{{ $land['gps_y'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label>Soil Type</label>
                        <select name="soil_type_id[]" class="form-select" required>
                            <option value="">-- Select Soil Type --</option>
                            @foreach($soilTypes as $soil)
                                <option value="{{ $soil->id }}" {{ isset($land['soil_type_id']) && $land['soil_type_id'] == $soil->id ? 'selected' : '' }}>
                                    {{ $soil->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Irrigation Type</label>
                        <select name="irrigation_type[]" class="form-select">
                            <option value="Rain-fed" {{ ($land['irrigation_type'] ?? '')=='Rain-fed' ? 'selected':'' }}>Rain-fed</option>
                            <option value="Irrigated" {{ ($land['irrigation_type'] ?? '')=='Irrigated' ? 'selected':'' }}>Irrigated</option>
                            <option value="Both" {{ ($land['irrigation_type'] ?? '')=='Both' ? 'selected':'' }}>Both</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-land">X</button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-success mb-3" id="add-land">Add More Land</button>

        <hr>
        <button type="submit" class="btn btn-primary">Update Farmer</button>
    </form>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    const allLandTypes = @json($landTypes);
    const allSoilTypes = @json($soilTypes);

    function getAvailableLandTypes() {
        let selected = $('.land-type-select').map(function(){ return $(this).val(); }).get();
        return allLandTypes.filter(l => !selected.includes(l.id.toString()));
    }

    // Add more land
    $('#add-land').click(function(){
        let available = getAvailableLandTypes();
        if(available.length === 0){
            alert('All land types are already used!');
            return;
        }

        let html = `
        <div class="row border p-3 rounded mb-3 land-item">
            <div class="col-md-2">
                <label>Land Type</label>
                <select name="land_type_id[]" class="form-select land-type-select" required>
                    <option value="">-- Select --</option>
                    ${available.map(l => `<option value="${l.id}">${l.name}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-2">
                <label>Size</label>
                <input type="number" step="0.01" name="landholding_size[]" class="form-control">
            </div>
            <div class="col-md-2">
                <label>GPS X</label>
                <input type="text" name="gps_x[]" class="form-control">
            </div>
            <div class="col-md-2">
                <label>GPS Y</label>
                <input type="text" name="gps_y[]" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Soil Type</label>
                <select name="soil_type_id[]" class="form-select" required>
                    <option value="">-- Select Soil Type --</option>
                    ${allSoilTypes.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
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
        $('#land-container').append(html);
    });

    // Remove land
    $(document).on('click', '.remove-land', function(){
        $(this).closest('.land-item').remove();
    });

});
</script>
</x-layout>
