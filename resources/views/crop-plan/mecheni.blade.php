<x-layout>
<div class="container mt-3">
    <div class="card shadow rounded">
        <div class="card-header text-center bg-primary text-white">
            <h2>Guca Karoora woggaa Misooma Midhaanii Kan Gandaa</h2>
        </div>
 @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        <div class="card-body">
            <form id="year-selection-form" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <label for="baseline_year" class="form-label">Select Baseline Year</label>
                        <select id="baseline_year" class="form-control" required>
                            <option value="">-- Select Baseline Year --</option>
                            @foreach ($planYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="plan_year" class="form-label">Enter Plan Year</label>
                        <input type="number" id="plan_year" class="form-control" placeholder="e.g., 2026" min="2000" max="2100" required>
                    </div>
                </div>
            </form>
           
            <form action="{{ route('yearly_crop_plans.store') }}" method="POST" id="crop-plan-form" style="display: none;">
                @csrf
                <input type="hidden" name="baseline_year" id="hidden_baseline_year">
                <input type="hidden" name="plan_year" id="hidden_plan_year">

              

                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th rowspan="2">Gosa Midhaanii</th>
                                <th rowspan="2">Maqaa Midhaanii</th>
                                <th colspan="2" id="baseline_year_label">Baseline Bara</th>
                                <th colspan="2" id="plan_year_label">Plan Bara</th>
                            </tr>
                            <tr>
                                <th>Bal'ina Lafaa [ha]</th>
                                <th>Oomisha [Kuntaala]</th>
                                <th>Bal'ina Lafaa [ha]</th>
                                <th>Oomishtummaa [Kuntaala/ha]</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td rowspan="4">
                                    <select name="croptype_id" id="crop_type_id" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach($cropTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td rowspan="4">
                                    <select name="crop_id" id="maqaa_midhaanii" class="form-control" required>
                                        <option value="">-- Select --</option>
                                    </select>
                                </td>

                                <!-- Ganna -->
                                <td><input type="number" name="land_area_baseline_ganna" class="form-control calc-input" placeholder="Ganna"></td>
                                <td><input type="number" name="production_baseline_ganna" class="form-control calc-input" placeholder="Ganna"></td>
                                <td><input type="number" name="land_area_plan_ganna" class="form-control calc-input" placeholder="Ganna"></td>
                                <td><input type="number" name="productivity_plan_ganna" class="form-control calc-input" placeholder="Ganna"></td>
                            </tr>

                            <!-- Bona -->
                            <tr>
                                <td><input type="number" name="land_area_baseline_bona" class="form-control calc-input" placeholder="Bona/Jallisii"></td>
                                <td><input type="number" name="production_baseline_bona" class="form-control calc-input" placeholder="Bona/Jallisii"></td>
                                <td><input type="number" name="land_area_plan_bona" class="form-control calc-input" placeholder="Bona/Jallisii"></td>
                                <td><input type="number" name="productivity_plan_bona" class="form-control calc-input" placeholder="Bona/Jallisii"></td>
                            </tr>

                            <!-- Arfaasaa -->
                            <tr>
                                <td><input type="number" name="land_area_baseline_arfaasaa" class="form-control calc-input" placeholder="Arfaasaa"></td>
                                <td><input type="number" name="production_baseline_arfaasaa" class="form-control calc-input" placeholder="Arfaasaa"></td>
                                <td><input type="number" name="land_area_plan_arfaasaa" class="form-control calc-input" placeholder="Arfaasaa"></td>
                                <td><input type="number" name="productivity_plan_arfaasaa" class="form-control calc-input" placeholder="Arfaasaa"></td>
                            </tr>

                            <!-- Ida'ama -->
                            <tr>
                                <td><input type="number" name="land_area_baseline_idaama" class="form-control" id="land_area_baseline_idaama" readonly  style="background-color:rgb(144, 145, 147); color: black;"></td>
                                <td><input type="number" name="production_baseline_idaama" class="form-control" id="production_baseline_idaama" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                <td><input type="number" name="land_area_plan_idaama" class="form-control" id="land_area_plan_idaama" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                <td><input type="number" name="productivity_plan_idaama" class="form-control" id="productivity_plan_idaama" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                            </tr>

                            <!-- Calculated Expected Production -->
                                <!-- <tr class="bg-info text-white font-weight-bold"> -->
                                    <!-- <td colspan="2" class="text-right">Expected Production:</td> -->
                                    <input type="hidden" name="production_plan_ganna" id="production_plan_ganna" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;">
                                     <input type="hidden" name="production_plan_bona" id="production_plan_bona" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                     <input type="hidden" name="production_plan_arfaasaa" id="production_plan_arfaasaa" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                     <input type="hidden" name="production_plan_idaama" id="production_plan_idaama" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                <!-- </tr> -->

                                <!-- Baseline Productivity -->
                                <!-- <tr class="bg-warning text-white font-weight-bold"> -->
                                    <!-- <td colspan="2" class="text-right">Baseline Productivity:  -->
                                     <input type="hidden" name="productivity_baseline_gana" id="productivity_baseline_gana" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                     <input type="hidden" name="productivity_baseline_bona" id="productivity_baseline_bona" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                     <input type="hidden" name="productivity_baseline_arfaasaa" id="productivity_baseline_arfaasaa" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                     <input type="hidden" name="productivity_baseline_idaama" id="productivity_baseline_idaama" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"> 
                                <!-- </tr> -->
                                <input type="hidden" name="kebele_id" id="kebele_id"
    value="{{ Auth::user()->kebele_id ?? '' }}">



                                <<input type="hidden" name="created_by" id="created_by"
    class="form-control" readonly
    value="{{ Auth::user()->id}}"
    style="background-color:rgb(144, 145, 147); color: black;">


                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-success px-4">Save Data</button>
                    <a href="{{ route('yearly_crop_plans.index') }}" class="btn btn-secondary px-4">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const baselineYearSelect = document.getElementById('baseline_year');
    const planYearInput = document.getElementById('baseline_year');
    const cropPlanForm = document.getElementById('crop-plan-form');
    const baselineLabel = document.getElementById('baseline_year_label');
    const planLabel = document.getElementById('plan_year_label');

    const hiddenBaselineYear = document.getElementById('hidden_baseline_year');
    const hiddenPlanYear = document.getElementById('hidden_plan_year');

    const cropTypeSelect = document.getElementById('crop_type_id');
    const maqaaSelect = document.getElementById('maqaa_midhaanii');

    function showFormIfYearsSelected() {
        if (baselineYearSelect.value && planYearInput.value) {
            cropPlanForm.style.display = 'block';
            baselineLabel.innerHTML = `Baseline Bara ${baselineYearSelect.value}`;
            planLabel.innerHTML = `Plan Bara ${planYearInput.value}`;
            hiddenBaselineYear.value = baselineYearSelect.value;
            hiddenPlanYear.value = planYearInput.value;
        } else {
            cropPlanForm.style.display = 'none';
        }
    }

    baselineYearSelect.addEventListener('change', showFormIfYearsSelected);
    planYearInput.addEventListener('input', showFormIfYearsSelected);

    cropTypeSelect.addEventListener('change', function() {
        const typeId = this.value;
        if (typeId) {
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
                });
        } else {
            maqaaSelect.innerHTML = '<option value="">-- Select --</option>';
        }
    });

    maqaaSelect.addEventListener('change', function() {
        const baselineYear = baselineYearSelect.value;
        const cropTypeId = cropTypeSelect.value;
        const cropId = maqaaSelect.value;
        if (baselineYear && cropTypeId && cropId) {
            fetch(`/fetch-baseline-data?year=${baselineYear}&crop_type_id=${cropTypeId}&crop_id=${cropId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.querySelector('[name="land_area_baseline_ganna"]').value = data.land_area_ganna || '';
                        document.querySelector('[name="production_baseline_ganna"]').value = data.production_ganna || '';
                        document.querySelector('[name="land_area_baseline_bona"]').value = data.land_area_bona || '';
                        document.querySelector('[name="production_baseline_bona"]').value = data.production_bona || '';
                        document.querySelector('[name="land_area_baseline_arfaasaa"]').value = data.land_area_arfaasaa || '';
                        document.querySelector('[name="production_baseline_arfaasaa"]').value = data.production_arfaasaa || '';
                        calculateIdaama();
                    }
                });
        }
    });

    // calculate total idaama and production
    const inputs = document.querySelectorAll('.calc-input');

    inputs.forEach(input => {
        input.addEventListener('input', calculateIdaama);
    });

    function calculateIdaama() {
        const land_area_baseline_ganna = parseFloat(document.querySelector('[name="land_area_baseline_ganna"]').value) || 0;
        const land_area_baseline_bona = parseFloat(document.querySelector('[name="land_area_baseline_bona"]').value) || 0;
        const land_area_baseline_arfaasaa = parseFloat(document.querySelector('[name="land_area_baseline_arfaasaa"]').value) || 0;

        const production_baseline_ganna = parseFloat(document.querySelector('[name="production_baseline_ganna"]').value) || 0;
        const production_baseline_bona = parseFloat(document.querySelector('[name="production_baseline_bona"]').value) || 0;
        const production_baseline_arfaasaa = parseFloat(document.querySelector('[name="production_baseline_arfaasaa"]').value) || 0;

        const land_area_plan_ganna = parseFloat(document.querySelector('[name="land_area_plan_ganna"]').value) || 0;
        const land_area_plan_bona = parseFloat(document.querySelector('[name="land_area_plan_bona"]').value) || 0;
        const land_area_plan_arfaasaa = parseFloat(document.querySelector('[name="land_area_plan_arfaasaa"]').value) || 0;

        const productivity_plan_ganna = parseFloat(document.querySelector('[name="productivity_plan_ganna"]').value) || 0;
        const productivity_plan_bona = parseFloat(document.querySelector('[name="productivity_plan_bona"]').value) || 0;
        const productivity_plan_arfaasaa = parseFloat(document.querySelector('[name="productivity_plan_arfaasaa"]').value) || 0;

        document.getElementById('land_area_baseline_idaama').value = (land_area_baseline_ganna + land_area_baseline_bona + land_area_baseline_arfaasaa).toFixed(2);
        document.getElementById('production_baseline_idaama').value = (production_baseline_ganna + production_baseline_bona + production_baseline_arfaasaa).toFixed(2);
        document.getElementById('land_area_plan_idaama').value = (land_area_plan_ganna + land_area_plan_bona + land_area_plan_arfaasaa).toFixed(2);

        const land_area_idaama = parseFloat(document.getElementById('land_area_plan_idaama').value) || 0;
        const productivity_plan_idaama = (productivity_plan_ganna + productivity_plan_bona + productivity_plan_arfaasaa) / 3;

        document.getElementById('productivity_plan_idaama').value = productivity_plan_idaama.toFixed(2);

        // final calculation: production plan
        document.getElementById('production_plan_ganna').value = (land_area_plan_ganna * productivity_plan_ganna).toFixed(2);
        document.getElementById('production_plan_bona').value = (land_area_plan_bona * productivity_plan_bona).toFixed(2);
        document.getElementById('production_plan_arfaasaa').value = (land_area_plan_arfaasaa * productivity_plan_arfaasaa).toFixed(2);
        document.getElementById('production_plan_idaama').value = (land_area_idaama * productivity_plan_idaama).toFixed(2);



    }
    function calculateIdaama() {
    // Existing calculations
    const land_area_baseline_ganna = parseFloat(document.querySelector('[name="land_area_baseline_ganna"]').value) || 0;
    const land_area_baseline_bona = parseFloat(document.querySelector('[name="land_area_baseline_bona"]').value) || 0;
    const land_area_baseline_arfaasaa = parseFloat(document.querySelector('[name="land_area_baseline_arfaasaa"]').value) || 0;

    const production_baseline_ganna = parseFloat(document.querySelector('[name="production_baseline_ganna"]').value) || 0;
    const production_baseline_bona = parseFloat(document.querySelector('[name="production_baseline_bona"]').value) || 0;
    const production_baseline_arfaasaa = parseFloat(document.querySelector('[name="production_baseline_arfaasaa"]').value) || 0;

    const land_area_plan_ganna = parseFloat(document.querySelector('[name="land_area_plan_ganna"]').value) || 0;
    const land_area_plan_bona = parseFloat(document.querySelector('[name="land_area_plan_bona"]').value) || 0;
    const land_area_plan_arfaasaa = parseFloat(document.querySelector('[name="land_area_plan_arfaasaa"]').value) || 0;

    const productivity_plan_ganna = parseFloat(document.querySelector('[name="productivity_plan_ganna"]').value) || 0;
    const productivity_plan_bona = parseFloat(document.querySelector('[name="productivity_plan_bona"]').value) || 0;
    const productivity_plan_arfaasaa = parseFloat(document.querySelector('[name="productivity_plan_arfaasaa"]').value) || 0;

    // Calculate Baseline Productivity
    const productivity_baseline_gana = land_area_baseline_ganna > 0 ? production_baseline_ganna / land_area_baseline_ganna : 0;
    const productivity_baseline_bona = land_area_baseline_bona > 0 ? production_baseline_bona / land_area_baseline_bona : 0;
    const productivity_baseline_arfaasaa = land_area_baseline_arfaasaa > 0 ? production_baseline_arfaasaa / land_area_baseline_arfaasaa : 0;
    const productivity_baseline_idaama = (productivity_baseline_gana + productivity_baseline_bona + productivity_baseline_arfaasaa) / 3;

    // Set Baseline Productivity values
    document.getElementById('productivity_baseline_gana').value = productivity_baseline_gana.toFixed(2);
    document.getElementById('productivity_baseline_bona').value = productivity_baseline_bona.toFixed(2);
    document.getElementById('productivity_baseline_arfaasaa').value = productivity_baseline_arfaasaa.toFixed(2);
    document.getElementById('productivity_baseline_idaama').value = productivity_baseline_idaama.toFixed(2);

    // Rest of your existing calculations
    document.getElementById('land_area_baseline_idaama').value = (land_area_baseline_ganna + land_area_baseline_bona + land_area_baseline_arfaasaa).toFixed(2);
    document.getElementById('production_baseline_idaama').value = (production_baseline_ganna + production_baseline_bona + production_baseline_arfaasaa).toFixed(2);
    document.getElementById('land_area_plan_idaama').value = (land_area_plan_ganna + land_area_plan_bona + land_area_plan_arfaasaa).toFixed(2);

    const land_area_idaama = parseFloat(document.getElementById('land_area_plan_idaama').value) || 0;
    const productivity_plan_idaama = (productivity_plan_ganna + productivity_plan_bona + productivity_plan_arfaasaa) / 3;

    document.getElementById('productivity_plan_idaama').value = productivity_plan_idaama.toFixed(2);

    // Final calculation: production plan
    document.getElementById('production_plan_ganna').value = (land_area_plan_ganna * productivity_plan_ganna).toFixed(2);
    document.getElementById('production_plan_bona').value = (land_area_plan_bona * productivity_plan_bona).toFixed(2);
    document.getElementById('production_plan_arfaasaa').value = (land_area_plan_arfaasaa * productivity_plan_arfaasaa).toFixed(2);
    document.getElementById('production_plan_idaama').value = (land_area_idaama * productivity_plan_idaama).toFixed(2);
}

    
});
</script>
</x-layout>