<x-layout>
    <title>Construction and Maintenance Data Entry</title>
    <style>
        /* --- Your existing styles --- */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #333;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .form-container {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--white);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 25px;
        }
        
        .year-selection {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            background: var(--light-gray);
            padding: 15px;
            border-radius: 5px;
        }
        
        .year-selection div {
            flex: 1;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        th {
            background-color: var(--primary-color);
            color: var(--white);
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        
        tr:nth-child(even) {
            background-color: var(--light-gray);
        }
        
        tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            font-size: 14px;
        }
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .calculated-field {
            background-color: #f0f8ff;
        }
        
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        button {
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .submit-btn {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .reset-btn {
            background-color: var(--medium-gray);
            color: var(--dark-gray);
        }
        
        .reset-btn:hover {
            background-color: #d0d0d0;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }
            
            .year-selection {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
    <div class="form-container">
        <h2>Construction and Maintenance Data Entry</h2>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="year-selection">
            <div>
                <label for="baseline_year">Select Baseline Year</label>
                <select id="baseline_year" class="form-control" required>
                    <option value="">-- Select Baseline Year --</option>
                    @foreach ($planYears as $year)
                        <option value="{{ $year }}" {{ $year == 2017 ? 'selected' : '' }}>{{ $year }} EFY</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="plan_year">Enter Plan Year</label>
                <input type="number" id="plan_year" class="form-control" placeholder="e.g., 2018" min="2000" max="2100" value="2018" required>
            </div>
        </div>

        <form id="data-form" method="POST" action="{{ route('construction_maintenance.store') }}">
            @csrf
            <input type="hidden" name="baseline_year" id="baseline_year_hidden" value="2017">
            <input type="hidden" name="plan_year" id="plan_year_hidden" value="2018">

            <table>
                <thead>
                    <tr>
                        <th>Construction and maintenance</th>
                        <th id="baseline_header">Baseline Year (<span id="baseline_year_display">2017</span> EFY achievement)</th>
                        <th id="plan_header">Plan of <span id="plan_year_display">2018</span> EFY</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Construction of solar center -->
                    <tr>
                        <td>Construction of solar center</td>
                        <td><input type="number" name="solar_center_baseline" class="input-field" placeholder="value"></td>
                        <td><input type="number" name="solar_center_plan" class="input-field" placeholder="value"></td>
                    </tr>
                    
                    <!-- Sources of biogas construction -->
                    <tr>
                        <td>Sources of biogas construction</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Family</td>
                        <td><input type="number" name="biogas_family_baseline" class="input-field" placeholder="value"></td>
                        <td><input type="number" name="biogas_family_plan" class="input-field" placeholder="value"></td>
                    </tr>
                    <tr>
                        <td>Community</td>
                        <td><input type="number" name="biogas_community_baseline" class="input-field" placeholder="value"></td>
                        <td><input type="number" name="biogas_community_plan" class="input-field" placeholder="value"></td>
                    </tr>
                    <tr>
                        <td>Cost</td>
                        <td><input type="number" name="biogas_cost_baseline" class="input-field" placeholder="cost" step="0.01"></td>
                        <td><input type="number" name="biogas_cost_plan" class="input-field" placeholder="cost" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Ida'ama</td>
                        <td><input type="number" name="biogas_idaama_baseline" class="input-field" placeholder="value"></td>
                        <td><input type="number" name="biogas_idaama_plan" class="input-field" placeholder="value"></td>
                    </tr>
                    
                    <!-- Expanding Electric lines [KM] -->
                    <tr>
                        <td>Expanding Electric lines [KM]</td>
                        <td><input type="number" name="electric_lines_baseline" class="input-field" placeholder="kilometers"></td>
                        <td><input type="number" name="electric_lines_plan" class="input-field" placeholder="kilometers"></td>
                    </tr>
                    
                    <!-- Rainfall water harvesting [m³] -->
                    <tr>
                        <td>Rainfall water harvesting [m³]</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Family</td>
                        <td><input type="number" name="rainfall_family_baseline" class="input-field" placeholder="m³"></td>
                        <td><input type="number" name="rainfall_family_plan" class="input-field" placeholder="m³"></td>
                    </tr>
                    <tr>
                        <td>Community</td>
                        <td><input type="number" name="rainfall_community_baseline" class="input-field" placeholder="m³"></td>
                        <td><input type="number" name="rainfall_community_plan" class="input-field" placeholder="m³"></td>
                    </tr>
                    <tr>
                        <td>Cost</td>
                        <td><input type="number" name="rainfall_cost_baseline" class="input-field" placeholder="cost" step="0.01"></td>
                        <td><input type="number" name="rainfall_cost_plan" class="input-field" placeholder="cost" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Ida'ama</td>
                        <td><input type="number" name="rainfall_idaama_baseline" class="input-field" placeholder="value"></td>
                        <td><input type="number" name="rainfall_idaama_plan" class="input-field" placeholder="value"></td>
                    </tr>
                    
                    <!-- Water spring development [Numbers] -->
                    <tr>
                        <td>Water spring development [Numbers]</td>
                        <td><input type="number" name="water_spring_baseline" class="input-field" placeholder="number"></td>
                        <td><input type="number" name="water_spring_plan" class="input-field" placeholder="number"></td>
                    </tr>
                    
                    <!-- Hand developed well [Numbers] -->
                    <tr>
                        <td>Hand developed well [Numbers]</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Family</td>
                        <td><input type="number" name="well_family_baseline" class="input-field" placeholder="number"></td>
                        <td><input type="number" name="well_family_plan" class="input-field" placeholder="number"></td>
                    </tr>
                    <tr>
                        <td>Community</td>
                        <td><input type="number" name="well_community_baseline" class="input-field" placeholder="number"></td>
                        <td><input type="number" name="well_community_plan" class="input-field" placeholder="number"></td>
                    </tr>
                    <tr>
                        <td>Cost</td>
                        <td><input type="number" name="well_cost_baseline" class="input-field" placeholder="cost" step="0.01"></td>
                        <td><input type="number" name="well_cost_plan" class="input-field" placeholder="cost" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Ida'ama</td>
                        <td><input type="number" name="well_idaama_baseline" class="input-field" placeholder="value"></td>
                        <td><input type="number" name="well_idaama_plan" class="input-field" placeholder="value"></td>
                    </tr>
                    
                    <!-- Rural drinking water supply facility maintenance [Number] -->
                    <tr>
                        <td>Rural drinking water supply facility maintenance [Number]</td>
                        <td><input type="number" name="water_supply_maintenance_baseline" class="input-field" placeholder="number"></td>
                        <td><input type="number" name="water_supply_maintenance_plan" class="input-field" placeholder="number"></td>
                    </tr>
                    
                    <!-- Constructing (opening) new road [Kilometers] -->
                    <tr>
                        <td>Constructing (opening) new road [Kilometers]</td>
                        <td><input type="number" name="new_road_baseline" class="input-field" placeholder="kilometers"></td>
                        <td><input type="number" name="new_road_plan" class="input-field" placeholder="kilometers"></td>
                    </tr>
                    
                    <!-- Road maintenance [Kilometers] -->
                    <tr>
                        <td>Road maintenance [Kilometers]</td>
                        <td><input type="number" name="road_maintenance_baseline" class="input-field" placeholder="kilometers"></td>
                        <td><input type="number" name="road_maintenance_plan" class="input-field" placeholder="kilometers"></td>
                    </tr>
                    
                    <!-- Water furrow construction [Kilometers] -->
                    <tr>
                        <td>Water furrow construction [Kilometers]</td>
                        <td><input type="number" name="water_furrow_baseline" class="input-field" placeholder="kilometers"></td>
                        <td><input type="number" name="water_furrow_plan" class="input-field" placeholder="kilometers"></td>
                    </tr>
                    
                    <!-- Construction of small bridges [Number] -->
                    <tr>
                        <td>Construction of small bridges [Number]</td>
                        <td><input type="number" name="small_bridges_baseline" class="input-field" placeholder="number"></td>
                        <td><input type="number" name="small_bridges_plan" class="input-field" placeholder="number"></td>
                    </tr>
                </tbody>
            </table>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit Data</button>
                <button type="reset" class="reset-btn">Reset Form</button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Year selection functionality
        const baselineYearSelect = document.getElementById('baseline_year');
        const planYearInput = document.getElementById('plan_year');
        const baselineYearDisplay = document.getElementById('baseline_year_display');
        const planYearDisplay = document.getElementById('plan_year_display');
        const baselineYearHidden = document.getElementById('baseline_year_hidden');
        const planYearHidden = document.getElementById('plan_year_hidden');

        function updateYearDisplays() {
            const baselineYear = baselineYearSelect.value;
            const planYear = planYearInput.value;
            if (baselineYear) {
                baselineYearDisplay.textContent = baselineYear;
                baselineYearHidden.value = baselineYear;
            }
            if (planYear) {
                planYearDisplay.textContent = planYear;
                planYearHidden.value = planYear;
            }
        }

        // Year selection event listeners
        baselineYearSelect.addEventListener('change', updateYearDisplays);
        planYearInput.addEventListener('input', updateYearDisplays);

        // Initialize
        updateYearDisplays();
    });
    </script>
</x-layout>