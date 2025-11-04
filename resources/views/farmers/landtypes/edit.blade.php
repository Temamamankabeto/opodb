<x-layout>
<div class="container py-5">
    <h3 class="mb-4">Edit Land Type Details for {{ $farmer->farmer_name }}</h3>

    <form action="{{ route('farmers.updateLand', $farmer->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Select Land Type</label>
            <select name="land_type_id" id="land_type_id" class="form-select" required>
                <option value="">-- Select --</option>
                @foreach($landTypes as $ld)
                    <option value="{{ $ld['land_type_id'] }}" data-total="{{ $ld['total_size'] }}" data-parcels='@json($ld["parcels"])'>
                        {{ $ld['name'] }} (Total: {{ $ld['total_size'] }} ha)
                    </option>
                @endforeach
            </select>
        </div>

        <div id="parcel-container"></div>

        <div class="mb-3">
            <button type="button" class="btn btn-success" id="add-parcel">Add Parcel</button>
        </div>

        <button type="submit" class="btn btn-primary">Save Land Details</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    let totalAllowed = 0;

    function renderParcels(parcels) {
        $('#parcel-container').html(''); // reset
        parcels.forEach((p, index) => {
            let html = `
                <div class="row mb-2 parcel-item border p-2 rounded">
                    <div class="col-md-3">
                        <label>Parcel Size</label>
                        <input type="number" step="0.01" name="parcel_size[]" class="form-control parcel-size" value="${p.size}" required>
                    </div>
                    <div class="col-md-3">
                        <label>GPS X</label>
                        <input type="text" name="parcel_gps_x[]" class="form-control" value="${p.gps_x}">
                    </div>
                    <div class="col-md-3">
                        <label>GPS Y</label>
                        <input type="text" name="parcel_gps_y[]" class="form-control" value="${p.gps_y}">
                    </div>
                    <div class="col-md-2">
                        <label>Soil Type</label>
                        <select name="parcel_soil_type[]" class="form-select">
                            <option value="Clay" ${p.soil_type=='Clay'?'selected':''}>Clay</option>
                            <option value="Loam" ${p.soil_type=='Loam'?'selected':''}>Loam</option>
                            <option value="Sandy" ${p.soil_type=='Sandy'?'selected':''}>Sandy</option>
                            <option value="Other" ${p.soil_type=='Other'?'selected':''}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-parcel">X</button>
                    </div>
                </div>
            `;
            $('#parcel-container').append(html);
        });
    }

    // When land type changes
    $('#land_type_id').change(function(){
        const selected = $(this).find(':selected');
        totalAllowed = parseFloat(selected.data('total')) || 0;
        const parcels = selected.data('parcels') || [];
        renderParcels(parcels);
    });

    // Add new parcel
    $('#add-parcel').click(function(){
        let currentTotal = 0;
        $('.parcel-size').each(function(){ currentTotal += parseFloat($(this).val()) || 0; });

        if(currentTotal >= totalAllowed){
            alert("Cannot exceed total land size!");
            return;
        }

        let remaining = totalAllowed - currentTotal;
        let html = `
            <div class="row mb-2 parcel-item border p-2 rounded">
                <div class="col-md-3">
                    <label>Parcel Size</label>
                    <input type="number" step="0.01" name="parcel_size[]" class="form-control parcel-size" max="${remaining}" required>
                </div>
                <div class="col-md-3">
                    <label>GPS X</label>
                    <input type="text" name="parcel_gps_x[]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>GPS Y</label>
                    <input type="text" name="parcel_gps_y[]" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Soil Type</label>
                    <select name="parcel_soil_type[]" class="form-select">
                        <option value="Clay">Clay</option>
                        <option value="Loam">Loam</option>
                        <option value="Sandy">Sandy</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-parcel">X</button>
                </div>
            </div>
        `;
        $('#parcel-container').append(html);
    });

    // Remove parcel
    $(document).on('click', '.remove-parcel', function(){
        $(this).closest('.parcel-item').remove();
    });

});
</script>
</x-layout>
