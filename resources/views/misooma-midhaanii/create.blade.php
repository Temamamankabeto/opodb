<x-layout>
    <title>Create Yearly Crop Plan</title>

    <div class="container mt-5">
        <div class="card shadow rounded">
            <div class="card-header bg-shadow text-dark">
                <h5 class="mb-0">Create New Yearly Crop Plan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('yearly_crop_plans.store') }}" method="POST">
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
                    <!-- Plan Year & Crop Type Section -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Baraa Fi Gosa Midhaanii</strong>
                        </div>
                        <div class="card-body row">
                            <!-- <div class="mb-3 col-md-3">
                                <label for="plan_year" class="form-label">Plan Year</label>
                                <input type="text" name="plan_year" id="plan_year" class="form-control" value="{{ old('plan_year') }}">
                            </div> -->



                            <div class="mb-3 col-md-3">
                                <label>Bara Karooraa</label>
                                <input type="text" name="plan_year" id="plan_year" class="form-control" value="{{ date('Y') }}" required>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Bara Baajataa</label>
                                <input type="text" name="production_year" id="production_year" class="form-control" value="{{ date('Y') }}" required>
                            </div>

                            <!-- <div class="mb-3 col-md-3">
                                <label for="production_year" class="form-label">Production Year</label>

                                <select name="production_year" id="production_year" class="form-select">
                                    <option value="">-- Select Production Year --</option>
                                    @foreach ($plans as $year)
                                        <option value="{{ $year }}" {{ old('production_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> -->

                            <div class="mb-3 col-md-3">
                                <label for="croptype_id" class="form-label">Crop Type</label>
                                <select name="croptype_id" id="croptype_id" class="form-select">
                                    <option value="">-- Select Crop Type --</option>
                                    @foreach ($cropTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label for="crop_id" class="form-label">Crop</label>
                                <select name="crop_id" id="crop_id" class="form-select">
                                    <option value="">-- Select Crop --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Land Area Section -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Bal’ina Lafaa</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-3">
                                <label>Bal'ina Lafaa Gannaa</label>
                                <input type="text" name="summury_land_area_plan" id="summury_land_area_plan" class="form-control" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Bal'ina Lafaa Bonaa</label>
                                <input type="text" name="spring_land_area_plan" id="spring_land_area_plan" class="form-control" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Bal'ina Lafaa Arfaasaa</label>
                                <input type="text" name="autumn_land_area_plan" id="autumn_land_area_plan" class="form-control" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Bal'ina Waliigalaa</label>
                                <input type="text" name="total_land_area_plan" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Productivity Section -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Oomishtummaa Bara darbee</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label>Omishtummaa Gannaa</label>
                                <input type="text" name="summury_productivity_plan" class="form-control" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label>Omishtummaa Bonaa</label>
                                <input type="text" name="spring_productivity_plan" class="form-control" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label>Omishtummaa Arfaasaa</label>
                                <input type="text" name="autumn_productivity_plan" class="form-control" oninput="calculateValues()">
                            </div>
                        </div>
                    </div>

                    <!-- Production Section -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Oomisha bara kanaa</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-3">
                                <label>Oomisha Gannaa</label>
                                <input type="text" name="summury_production_plan" id="summury_production_plan" class="form-control" readonly>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Oomisha Bonaa</label>
                                <input type="text" name="spring_production_plan" id="spring_production_plan" class="form-control" readonly>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Oomisha Arfaasaa</label>
                                <input type="text" name="autumn_production_plan" id="autumn_production_plan" class="form-control" readonly>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Oomisha Waliigalaa</label>
                                <input type="text" name="total_production_plan" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cropTypeSelect = document.getElementById('croptype_id');
            const cropSelect = document.getElementById('crop_id');
            const productionYearSelect = document.getElementById('production_year');
            const planYearInput = document.getElementById('plan_year');
            const yearPicker = document.getElementById('production_year');


            cropTypeSelect.addEventListener('change', function () {
                if (this.value) {
                    fetch(`/crops/by-type/${this.value}`)
                        .then(response => response.json())
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
                planYearInput.value = this.value || '';
            });
        });

        $(function () {
            $('#yearPicker, #plan_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
        });
        $(function () {
            $('#yearPicker, #production_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
        });

        function calculateValues() {
            const gannaArea = parseFloat(document.getElementById('summury_land_area_plan').value) || 0;
            const bonaaArea = parseFloat(document.getElementById('spring_land_area_plan').value) || 0;
            const arfaasaaArea = parseFloat(document.getElementById('autumn_land_area_plan').value) || 0;

            const gannaProd = parseFloat(document.getElementsByName('summury_productivity_plan')[0].value) || 0;
            const bonaaProd = parseFloat(document.getElementsByName('spring_productivity_plan')[0].value) || 0;
            const arfaasaaProd = parseFloat(document.getElementsByName('autumn_productivity_plan')[0].value) || 0;

            const gannaTotal = gannaArea * gannaProd;
            const bonaaTotal = bonaaArea * bonaaProd;
            const arfaasaaTotal = arfaasaaArea * arfaasaaProd;

            document.getElementById('summury_production_plan').value = gannaTotal.toFixed(2);
            document.getElementById('spring_production_plan').value = bonaaTotal.toFixed(2);
            document.getElementById('autumn_production_plan').value = arfaasaaTotal.toFixed(2);

            document.getElementsByName('total_land_area_plan')[0].value = (gannaArea + bonaaArea + arfaasaaArea).toFixed(2);
            document.getElementsByName('total_production_plan')[0].value = (gannaTotal + bonaaTotal + arfaasaaTotal).toFixed(2);
        }
    </script>
</x-layout>
