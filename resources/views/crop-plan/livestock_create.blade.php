<x-layout>
    <div class="container mt-3">
        <div class="card shadow rounded">
            <div class="card-header text-center">
                <h2>Karoora Misooma Beeyladaa</h2>
            </div>

            <div class="card-body">
                    <div class="container py-5">
                        <h3 class="mb-4 text-primary">Livestock Plan Achievement Input Form</h3>
                        <form action="{{route('livestock.store')}}" method="POST">
                            <!--  for Laravel -->
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
                            <table class="table table-bordered bg-white shadow-sm">
                                <thead class="table-primary">
                                <tr>
                                    <th>Indicator</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="category">Loon “AI” argatee</td>
                                    <td><input name="loon_ai_argatee" type="text" step="0.01" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="category">Cuccii guyyaa tokkoo [mil]</td>
                                    <td><input name="cuccii_guyyaa_tokkoo" type="text" step="0.01" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="category">Lukkuu sookee [mil]</td>
                                    <td><input name="lukkuu_sookkee" type="text" step="0.01" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="category">Cuucii qurxummii [mil]</td>
                                    <td><input name="cuucii_qurxummii" type="text" step="0.01" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="category">Gaagura ammayaa [mil]</td>
                                    <td><input name="gaagura_ammayaa" type="text" step="0.01" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="category">Oomishaa nyaata beeyladaa [he mil]</td>
                                    <td><input name="oomishaa_nyaata_beeylada" type="text" step="0.01" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td class="category">Bara</td>
                                    <td>
                                        <input type="text" name="plan_year" id="yearPicker" class="form-control" value="{{ date('Y') }}" required>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
                            </div>
                        </form>
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

        $(function () {
            $('#yearPicker, #yearPicker1').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
        });

        function calculateValues() {
            var gannaArea = parseFloat(document.getElementById('summury_land_area_plan').value) || 0;
            var bonaaArea = parseFloat(document.getElementById('spring_land_area_plan').value) || 0;
            var arfaasaaArea = parseFloat(document.getElementById('autumn_land_area_plan').value) || 0;

            var gannaProd = parseFloat(document.getElementsByName('summury_productivity_plan')[0].value) || 0;
            var bonaaProd = parseFloat(document.getElementsByName('spring_productivity_plan')[0].value) || 0;
            var arfaasaaProd = parseFloat(document.getElementsByName('autumn_productivity_plan')[0].value) || 0;

            var gannaTotal = gannaArea * gannaProd;
            var bonaaTotal = bonaaArea * bonaaProd;
            var arfaasaaTotal = arfaasaaArea * arfaasaaProd;

            document.getElementById('summury_production_plan').value = gannaTotal.toFixed(2);
            document.getElementById('spring_production_plan').value = bonaaTotal.toFixed(2);
            document.getElementById('autumn_production_plan').value = arfaasaaTotal.toFixed(2);

            document.getElementsByName('total_land_area_plan')[0].value = (gannaArea + bonaaArea + arfaasaaArea).toFixed(2);
            document.getElementsByName('total_production_plan')[0].value = (gannaTotal + bonaaTotal + arfaasaaTotal).toFixed(2);
        }
    </script>
</x-layout>
