<x-layout>
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Update Land Type Details for {{ $farmer->farmer_name }}</h4>
            <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-light btn-sm">← Back to Farmer</a>
        </div>

        <div class="card-body">
            @php
                $landDetails = json_decode($farmer->land_details, true) ?? [];
                $landTypeDetails = json_decode($farmer->land_type_details, true) ?? [];
            @endphp

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('farmers.updateLandTypeDetails', $farmer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Select Land Type</label>
                    <select name="land_type_id" id="land_type_id" class="form-select" required>
                        <option value="">-- Select --</option>
                        @foreach($landDetails as $land)
                            <option value="{{ $land['land_type_id'] }}" data-total="{{ $land['size'] }}">
                                {{ \App\Models\LandType::find($land['land_type_id'])->name ?? '—' }}
                                (Total: {{ $land['size'] }} ha)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="parcels-container"></div>

                <button type="button" id="add-parcel" class="btn btn-success mb-3">Add Parcel</button>
                <br>
                <button type="submit" class="btn btn-primary">Update Land Type Details</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    const soilTypes = @json($soilTypes);
    const landDetails = @json($landDetails);
    const landTypeDetails = @json($landTypeDetails);

    function createSoilSelect(selectedId = '') {
        let options = '<option value="">Select Soil Type</option>';
        soilTypes.forEach(st => {
            options += `<option value="${st.id}" ${selectedId == st.id ? 'selected' : ''}>${st.name}</option>`;
        });
        return `<select name="soil_type[]" class="form-select" required>${options}</select>`;
    }

    function createParcelRow(index, size='', gpsX='', gpsY='', soilId='') {
        return `
        <div class="row mb-2 parcel-row">
            <div class="col-md-3">
                <input type="number" step="0.01" name="landholding_size[]" class="form-control" placeholder="Size (ha)" value="${size}" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="gps_x[]" class="form-control" placeholder="GPS X" value="${gpsX}" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="gps_y[]" class="form-control" placeholder="GPS Y" value="${gpsY}" required>
            </div>
            <div class="col-md-2">
                ${createSoilSelect(soilId)}
            </div>
            <div class="col-md-1 d-flex">
                <button type="button" class="btn btn-danger remove-parcel">X</button>
            </div>
        </div>`;
    }

    function resetParcels() {
        $('#parcels-container').html('');
    }

    $('#land_type_id').change(function(){
        resetParcels();
        let selectedId = $(this).val();
        if(!selectedId) return;

        let typeDetail = landTypeDetails.find(l => l.land_type_id == selectedId);

        if(typeDetail && typeDetail.parcels) {
            typeDetail.parcels.forEach((p, i) => {
                $('#parcels-container').append(createParcelRow(i, p.size, p.gps_x, p.gps_y, p.soil_type));
            });
        } else {
            $('#parcels-container').append(createParcelRow(0));
        }
    });

    $('#add-parcel').click(function(){
        let selectedId = $('#land_type_id').val();
        if(!selectedId) { alert('Select a land type first'); return; }

        let land = landDetails.find(l => l.land_type_id == selectedId);
        let totalSize = parseFloat(land.size);
        let sum = 0;
        $('input[name="landholding_size[]"]').each(function(){ sum += parseFloat($(this).val()) || 0; });

        if(sum >= totalSize) { alert('Cannot exceed total land size!'); return; }

        $('#parcels-container').append(createParcelRow($('.parcel-row').length));
    });

    $(document).on('click', '.remove-parcel', function(){
        $(this).closest('.parcel-row').remove();
    });
});
</script>
</x-layout>
