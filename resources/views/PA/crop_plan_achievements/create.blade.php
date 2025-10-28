<x-layout>
    <div class="container mt-3">
        <div class="card shadow rounded">
            <div class="card-header text-center">
                <h2>Karoora Misooma Midhaanii</h2>
            </div>

            <div class="card-body">
                <form action="{{ route('crop_plan_achievements.store') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <!-- Crop Type -->
                        <div class="mb-3 col-md-4">
                            <label>Gosa Midhaanii</label>
                            <select name="crop_type_id" id="crop_type_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($cropTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Crop Name -->
                        <div class="mb-3 col-md-4">
                            <label>Maqaa Midhaanii</label>
                            <select name="maqaa_midhaanii" id="maqaa_midhaanii" class="form-control" required>
                                <option value="">-- Select --</option>
                            </select>
                        </div>

                        <!-- Year -->
                        <div class="mb-3 col-md-4">
                            <label>Bara</label>
                            <input type="text" name="year" id="yearPicker" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>

                    <hr>
                    <h4 class="text-center">Bal’ina Lafaa</h4>
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label>Bal'ina Lafaa Gannaa</label>
                            <input type="text" name="kblg" id="kblg" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label>Bal'ina Lafaa Bonaa</label>
                            <input type="text" name="kblb" id="kblb" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label>Bal'ina Lafaa Arfaasaa</label>
                            <input type="text" name="kbla" id="kbla" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label>Bal'ina Waliigalaa</label>
                            <input type="text" name="ikbl" class="form-control" readonly>
                        </div>
                    </div>
<hr>
<h4 class="text-center">Oomishtummaa</h4>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label>Omishtummaa Gannaa</label>
                            <input type="text" name="this_year_gana_productivity" class="form-control">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label>Omishtummaa Bonaa</label>
                            <input type="text" name="this_year_bona_productivity" class="form-control">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label>Omishtummaa Arfaasaa</label>
                            <input type="text" name="this_year_arfasa_productivity" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <h4 class="text-center">Oomisha</h4>
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label>Oomisha Gannaa</label>
                            <input type="text" name="kog" id="kog" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label>Oomisha Bonaa</label>
                            <input type="text" name="kob" id="kob" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label>Oomisha Arfaasaa</label>
                            <input type="text" name="koa" id="koa" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label>Oomisha Waliigalaa</label>
                            <input type="text" name="iKo" class="form-control" readonly>
                        </div>
                    </div>

                    <hr>
                    

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('crop_plan_achievements.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cropTypeSelect = document.getElementById('crop_type_id');
            const maqaaSelect = document.getElementById('maqaa_midhaanii');
            const yearPicker = document.getElementById('yearPicker');

            const fields = ['kblg', 'kblb', 'kbla', 'kog', 'kob', 'koa'];
            const ikbl = document.querySelector('input[name="ikbl"]');
            const iKo = document.querySelector('input[name="iKo"]');
       
            function calculateTotals() {
                let totalLand = 0;
                let totalProd = 0;
                fields.forEach(field => {
                    const val = parseFloat(document.getElementById(field)?.value) || 0;
                    if (['kblg', 'kblb', 'kbla'].includes(field)) {
                        totalLand += val;
                    } else {
                        totalProd += val;
                    }
                });
                ikbl.value = totalLand.toFixed(2);
                iKo.value = totalProd.toFixed(2);
            }


            fields.forEach(field => {
                const el = document.getElementById(field);
                if (el) el.addEventListener('input', calculateTotals);
            });

            function fetchCrops(typeId) {
                maqaaSelect.innerHTML = '<option value="">-- Loading --</option>';
                fetch(`/get-crops-by-type/${typeId}`)
                    .then(response => response.json())
                    .then(data => {
                        maqaaSelect.innerHTML = '<option value="">-- Select --</option>';
                        data.forEach(crop => {
                            const option = document.createElement('option');
                            option.value = crop.id;
                            option.textContent = crop.name;
                            maqaaSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching crops:', error);
                        maqaaSelect.innerHTML = '<option value="">-- Error Loading --</option>';
                    });
            }

            function fetchPreviousYearData() {
                const cropTypeId = cropTypeSelect.value;
                const cropId = maqaaSelect.value;
                const year = parseInt(yearPicker.value);
                const prevYear = year - 1;

                if (cropTypeId && cropId && prevYear) {
                    fetch(`/previous-year-crop-data?crop_type_id=${cropTypeId}&crop_id=${cropId}&year=${prevYear}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data) {
                                fields.forEach(field => {
                                    const input = document.getElementById(field);
                                    if (input) input.value = data[field] ?? 0;
                                });
                                calculateTotals();
                            }
                        })
                        .catch(err => {
                            console.error('Error loading previous year data:', err);
                        });
                }
            }

            cropTypeSelect.addEventListener('change', function () {
                if (this.value) {
                    fetchCrops(this.value);
                } else {
                    maqaaSelect.innerHTML = '<option value="">-- Select --</option>';
                }
            });

            maqaaSelect.addEventListener('change', fetchPreviousYearData);
            yearPicker.addEventListener('change', fetchPreviousYearData);
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#yearPicker').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
        });
    </script>
  <script>
    function calculateValues() {
        // Get input values
        var kblg = parseFloat(document.getElementById('kblg').value) || 0;
        var kblb = parseFloat(document.getElementById('kblb').value) || 0;
        var kbla = parseFloat(document.getElementById('kbla').value) || 0;

        var ganaProd = parseFloat(document.getElementsByName('this_year_gana_productivity')[0].value) || 0;
        var bonaProd = parseFloat(document.getElementsByName('this_year_bona_productivity')[0].value) || 0;
        var arfasaProd = parseFloat(document.getElementsByName('this_year_arfasa_productivity')[0].value) || 0;

        // Calculate productions
        var kog = kblg * ganaProd;
        var kob = kblb * bonaProd;
        var koa = kbla * arfasaProd;

        // Set calculated values
        document.getElementById('kog').value = kog.toFixed(2);
        document.getElementById('kob').value = kob.toFixed(2);
        document.getElementById('koa').value = koa.toFixed(2);

        // Total land area
        var ikbl = kblg + kblb + kbla;
        document.getElementsByName('ikbl')[0].value = ikbl.toFixed(2);

        // Total production
        var iKo = kog + kob + koa;
        document.getElementsByName('iKo')[0].value = iKo.toFixed(2);
    }

    // Run on input
    document.addEventListener('DOMContentLoaded', function () {
        const inputIds = ['kblg', 'kblb', 'kbla'];
        const prodNames = [
            'this_year_gana_productivity',
            'this_year_bona_productivity',
            'this_year_arfasa_productivity'
        ];

        inputIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', calculateValues);
        });

        prodNames.forEach(name => {
            const el = document.getElementsByName(name)[0];
            if (el) el.addEventListener('input', calculateValues);
        });
    });
</script>

</x-layout>
