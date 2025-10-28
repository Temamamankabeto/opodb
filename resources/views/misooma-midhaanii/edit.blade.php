<x-layout>
    <title>Edit Yearly Crop Plan</title>

    <div class="container mt-5">
        <div class="card shadow rounded">
            <div class="card-header bg-shadow text-dark">
                <h5 class="mb-0">Edit Yearly Crop Plan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('yearly_crop_plans.update', $plan->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-header bg-light">
                                <strong>Baraa Fi Gosa Midhaanii</strong>
                            </div>
                            <div class="card-body row">
                                <div class="mb-3 col-md-3">
                                    <label for="plan_year" class="form-label">Plan Year</label>
                                    <input type="text" name="plan_year" id="plan_year" class="form-control" value="{{ old('plan_year', $plan->plan_year) }}">
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label for="production_year" class="form-label">Production Year</label>
                                    <select name="production_year" id="production_year" class="form-select">
                                        <option value="">-- Select Production Year --</option>
                                        @foreach ($plans as $year)
                                            <option value="{{ $year }}" {{ old('production_year', $plan->production_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label for="croptype_id" class="form-label">Crop Type</label>
                                    <select name="croptype_id" id="croptype_id" class="form-select">
                                        <option value="">-- Select Crop Type --</option>
                                        @foreach ($cropTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('croptype_id', $plan->croptype_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label for="crop_id" class="form-label">Crop</label>
                                    <select name="crop_id" id="crop_id" class="form-select">
                                        <option value="{{ $plan->crop_id }}">{{ $plan->crop->name ?? 'Selected Crop' }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Bal’ina Lafaa -->
                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-header bg-light">
                                <strong>Bal’ina Lafaa</strong>
                            </div>
                            <div class="card-body row">
                                <div class="mb-3 col-md-3">
                                    <label>Bal'ina Lafaa Gannaa</label>
                                    <input type="text" name="summury_land_area_plan" id="summury_land_area_plan" class="form-control" value="{{ old('summury_land_area_plan', $plan->summury_land_area_plan) }}" oninput="calculateValues()">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label>Bal'ina Lafaa Bonaa</label>
                                    <input type="text" name="spring_land_area_plan" id="spring_land_area_plan" class="form-control" value="{{ old('spring_land_area_plan', $plan->spring_land_area_plan) }}" oninput="calculateValues()">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label>Bal'ina Lafaa Arfaasaa</label>
                                    <input type="text" name="autumn_land_area_plan" id="autumn_land_area_plan" class="form-control" value="{{ old('autumn_land_area_plan', $plan->autumn_land_area_plan) }}" oninput="calculateValues()">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label>Bal'ina Waliigalaa</label>
                                    <input type="text" name="total_land_area_plan" class="form-control" value="{{ old('total_land_area_plan', $plan->total_land_area_plan) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Productivity -->
                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-header bg-light">
                                <strong>Oomishtummaa Bara darbee</strong>
                            </div>
                            <div class="card-body row">
                                <div class="mb-3 col-md-4">
                                    <label>Omishtummaa Gannaa</label>
                                    <input type="text" name="summury_productivity_plan" class="form-control" value="{{ old('summury_productivity_plan', $plan->summury_productivity_plan) }}" oninput="calculateValues()">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label>Omishtummaa Bonaa</label>
                                    <input type="text" name="spring_productivity_plan" class="form-control" value="{{ old('spring_productivity_plan', $plan->spring_productivity_plan) }}" oninput="calculateValues()">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label>Omishtummaa Arfaasaa</label>
                                    <input type="text" name="autumn_productivity_plan" class="form-control" value="{{ old('autumn_productivity_plan', $plan->autumn_productivity_plan) }}" oninput="calculateValues()">
                                </div>
                            </div>
                        </div>

                        <!-- Oomisha bara kanaa -->
                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-header bg-light">
                                <strong>Oomisha bara kanaa</strong>
                            </div>
                            <div class="card-body row">
                                <div class="mb-3 col-md-3">
                                    <label>Oomisha Gannaa</label>
                                    <input type="text" name="summury_production_plan" id="summury_production_plan" class="form-control" value="{{ old('summury_production_plan', $plan->summury_production_plan) }}" readonly>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label>Oomisha Bonaa</label>
                                    <input type="text" name="spring_production_plan" id="spring_production_plan" class="form-control" value="{{ old('spring_production_plan', $plan->spring_production_plan) }}" readonly>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label>Oomisha Arfaasaa</label>
                                    <input type="text" name="autumn_production_plan" id="autumn_production_plan" class="form-control" value="{{ old('autumn_production_plan', $plan->autumn_production_plan) }}" readonly>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label>Oomisha Waliigalaa</label>
                                    <input type="text" name="total_production_plan" class="form-control" value="{{ old('total_production_plan', $plan->total_production_plan) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('yearly_crop_plans.index') }}" class="btn btn-secondary">Back</a>
                            <button class="btn btn-primary">Update Plan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function calculateValues() {
            let gannaArea = parseFloat(document.getElementById('summury_land_area_plan').value) || 0;
            let bonaaArea = parseFloat(document.getElementById('spring_land_area_plan').value) || 0;
            let arfaasaaArea = parseFloat(document.getElementById('autumn_land_area_plan').value) || 0;

            let gannaProd = parseFloat(document.getElementsByName('summury_productivity_plan')[0].value) || 0;
            let bonaaProd = parseFloat(document.getElementsByName('spring_productivity_plan')[0].value) || 0;
            let arfaasaaProd = parseFloat(document.getElementsByName('autumn_productivity_plan')[0].value) || 0;

            let gannaTotal = gannaArea * gannaProd;
            let bonaaTotal = bonaaArea * bonaaProd;
            let arfaasaaTotal = arfaasaaArea * arfaasaaProd;

            document.getElementById('summury_production_plan').value = gannaTotal.toFixed(2);
            document.getElementById('spring_production_plan').value = bonaaTotal.toFixed(2);
            document.getElementById('autumn_production_plan').value = arfaasaaTotal.toFixed(2);

            document.getElementsByName('total_land_area_plan')[0].value = (gannaArea + bonaaArea + arfaasaaArea).toFixed(2);
            document.getElementsByName('total_production_plan')[0].value = (gannaTotal + bonaaTotal + arfaasaaTotal).toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cropTypeSelect = document.getElementById('croptype_id');
            const cropSelect = document.getElementById('crop_id');
            const productionYearSelect = document.getElementById('production_year');
            const planYearInput = document.getElementById('plan_year');

            cropTypeSelect.addEventListener('change', function () {
                if (this.value) {
                    fetch(`/crops/by-type/${this.value}`)
                        .then(res => res.json())
                        .then(data => {
                            cropSelect.innerHTML = '<option value="">-- Select --</option>';
                            data.forEach(crop => {
                                const option = document.createElement('option');
                                option.value = crop.id;
                                option.textContent = crop.name;
                                cropSelect.appendChild(option);
                            });
                        });
                } else {
                    cropSelect.innerHTML = '<option value="">-- Select --</option>';
                }
            });

            productionYearSelect.addEventListener('change', function () {
                const selectedYear = this.value;
                if (selectedYear) {
                    fetch(`/plan-year/${selectedYear}`)
                        .then(res => res.json())
                        .then(data => {
                            planYearInput.value = data.plan_year || '';
                        })
                        .catch(() => {
                            planYearInput.value = '';
                        });
                } else {
                    planYearInput.value = '';
                }
            });
        });
    </script>
</x-layout>
