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

                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Hojiilee</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-3">
                                <label>Gosa Midhaanii</label>
                                <select name="croptype_id" id="crop_type_id" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    @foreach($cropTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label>Maqaa Midhaanii</label>
                                <select name="crop_id" id="maqaa_midhaanii" class="form-control" required>
                                    <option value="">-- Select --</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Bara Darbe</label>
                                <select name="plan_year" id="plan_year" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    @foreach($planYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label>Bara Kana</label>
                                <input type="text" name="production_year" id="yearPicker" class="form-control" value="{{ date('Y') }}" required readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Bal'ina Lafaa -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Bal'ina Lafaa</strong>
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
                                <input type="text" name="total_land_area_plan" id="total_land_area_plan" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Productivity -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Oomishtummaa Karoorfame</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label>Omishtummaa Gannaa</label>
                                <input type="text" name="summury_productivity_plan" id="summury_productivity_plan" class="form-control" oninput="calculateValues()">
                                <div class="mt-2">
                                    <small>Previous Year:</small>
                                    <input type="text" name="previews_summury_productivity_plan" id="previews_summury_productivity_plan" class="form-control" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3 col-md-4">
                                <label>Omishtummaa Bonaa</label>
                                <input type="text" name="spring_productivity_plan" id="spring_productivity_plan" class="form-control" oninput="calculateValues()">
                                <div class="mt-2">
                                    <small>Previous Year:</small>
                                    <input type="text" name="previews_spring_productivity_plan" id="previews_spring_productivity_plan" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label>Omishtummaa Arfaasaa</label>
                                <input type="text" name="autumn_productivity_plan" id="autumn_productivity_plan" class="form-control" oninput="calculateValues()">
                                <div class="mt-2">
                                    <small>Previous Year:</small>
                                    <input type="text" name="previews_autumn_productivity_plan" id="previews_autumn_productivity_plan" class="form-control" readonly>
                                </div>
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
                                <input type="text" name="total_production_plan" id="total_production_plan" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('crop_plan_achievements.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            // Initialize year picker
            $('#yearPicker').datepicker({
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
                yearRange: "c-20:c+0",
                onClose: function(dateText, inst) {
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).val(year);
                }
            });

            // Show only year dropdown when focused
            $('#yearPicker').focus(function() {
                $(".ui-datepicker-month").hide();
                $(".ui-datepicker-calendar").hide();
                $(".ui-datepicker-current").hide();
                $(".ui-datepicker-prev").hide();
                $(".ui-datepicker-next").hide();
                $(".ui-datepicker-title").css("text-align", "center");
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cropTypeSelect = document.getElementById('crop_type_id');
            const maqaaSelect = document.getElementById('maqaa_midhaanii');
            const planYearSelect = document.getElementById('plan_year');

            // Fetch crops when crop type changes
            cropTypeSelect.addEventListener('change', function() {
                if (this.value) {
                    fetchCrops(this.value);
                } else {
                    maqaaSelect.innerHTML = '<option value="">-- Select --</option>';
                }
            });

            // Fetch previous year data when plan year or crop changes
            planYearSelect.addEventListener('change', fetchPreviousYearData);
            maqaaSelect.addEventListener('change', fetchPreviousYearData);

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
                const year = planYearSelect.value;
                const cropId = maqaaSelect.value;
                
                if (year && cropId) {
                    fetch(`/get-previous-year-data?year=${year}&crop_id=${cropId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                document.getElementById('previews_summury_productivity_plan').value = data.summury_productivity_plan || '';
                                document.getElementById('previews_spring_productivity_plan').value = data.spring_productivity_plan || '';
                                document.getElementById('previews_autumn_productivity_plan').value = data.autumn_productivity_plan || '';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching previous year data:', error);
                        });
                }
            }
        });

        function calculateValues() {
            // Land area calculations
            var gannaArea = parseFloat(document.getElementById('summury_land_area_plan').value) || 0;
            var bonaaArea = parseFloat(document.getElementById('spring_land_area_plan').value) || 0;
            var arfaasaaArea = parseFloat(document.getElementById('autumn_land_area_plan').value) || 0;
            
            // Productivity calculations
            var gannaProd = parseFloat(document.getElementById('summury_productivity_plan').value) || 0;
            var bonaaProd = parseFloat(document.getElementById('spring_productivity_plan').value) || 0;
            var arfaasaaProd = parseFloat(document.getElementById('autumn_productivity_plan').value) || 0;

            // Production calculations
            var gannaTotal = gannaArea * gannaProd;
            var bonaaTotal = bonaaArea * bonaaProd;
            var arfaasaaTotal = arfaasaaArea * arfaasaaProd;

            // Update production fields
            document.getElementById('summury_production_plan').value = gannaTotal.toFixed(2);
            document.getElementById('spring_production_plan').value = bonaaTotal.toFixed(2);
            document.getElementById('autumn_production_plan').value = arfaasaaTotal.toFixed(2);

            // Update totals
            document.getElementById('total_land_area_plan').value = (gannaArea + bonaaArea + arfaasaaArea).toFixed(2);
            document.getElementById('total_production_plan').value = (gannaTotal + bonaaTotal + arfaasaaTotal).toFixed(2);
        }
    </script>
</x-layout>