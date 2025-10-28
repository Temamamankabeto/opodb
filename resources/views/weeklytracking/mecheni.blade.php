 <x-layout>
<div class="container mt-3">
    <div class="card shadow rounded">
        <div class="card-header text-center bg-primary text-white">
            <h2>Guca Karoora woggaa Misooma Midhaanii Kan Gandaa</h2>
        </div>

        <div class="card-body">
        

            <form action="{{ route('yearly_crop_plans.store') }}" method="POST" id="crop-plan-form" >
                @csrf
                <input type="hidden" name="baseline_year" id="hidden_baseline_year">
                <input type="hidden" name="plan_year" id="hidden_plan_year">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <!-- <th rowspan="2">Gosa Midhaanii</th> -->
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
                                <tr class="bg-info text-white font-weight-bold">
                                    <td colspan="2" class="text-right">Expected Production:</td>
                                    <td><input type="number" name="production_plan_ganna" id="production_plan_ganna" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                    <td><input type="number" name="production_plan_bona" id="production_plan_bona" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                    <td><input type="number" name="production_plan_arfaasaa" id="production_plan_arfaasaa" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                    <td><input type="number" name="production_plan_idaama" id="production_plan_idaama" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                </tr>

                                <!-- Baseline Productivity -->
                                <tr class="bg-warning text-white font-weight-bold">
                                    <td colspan="2" class="text-right">Baseline Productivity:</td>
                                    <td><input type="number" name="productivity_baseline_gana" id="productivity_baseline_gana" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                    <td><input type="number" name="productivity_baseline_bona" id="productivity_baseline_bona" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                    <td><input type="number" name="productivity_baseline_arfaasaa" id="productivity_baseline_arfaasaa" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                    <td><input type="number" name="productivity_baseline_idaama" id="productivity_baseline_idaama" class="form-control" readonly style="background-color:rgb(144, 145, 147); color: black;"></td>
                                </tr>

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
</x-layout>
