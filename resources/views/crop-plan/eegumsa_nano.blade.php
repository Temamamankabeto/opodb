<x-layout>
    <title>Forest Management Data Entry</title>

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
        <h2>Forest Management Data</h2>

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
                        <option value="{{ $year }}">{{ $year }} EFY</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="plan_year">Enter Plan Year</label>
                <input type="number" id="plan_year" class="form-control" placeholder="e.g., 2018" min="2000" max="2100" required>
            </div>
        </div>

        <form id="data-form" method="POST" action="{{ route('forest.management.store') }}">
            @csrf
            <input type="hidden" name="baseline_year" id="baseline_year_hidden">
            <input type="hidden" name="plan_year" id="plan_year_hidden">

            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th id="baseline_header">Baseline Year (<span id="baseline_year_display">2017</span> EFY achievement)</th>
                        <th id="plan_header">Plan of <span id="plan_year_display">2018</span> EFY</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Area allocated for forest management [Ha]</td>
                        <td><input type="number" name="forest_area_baseline" class="input-field" step="0.01"></td>
                        <td><input type="number" name="forest_area_plan" class="input-field" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Area free of grazing and other activities [Ha]</td>
                        <td><input type="number" name="grazing_free_area_baseline" class="input-field" step="0.01"></td>
                        <td><input type="number" name="grazing_free_area_plan" class="input-field" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Cooperatives Organized on forest management [Numbers]</td>
                        <td><input type="number" name="cooperatives_baseline" class="input-field"></td>
                        <td><input type="number" name="cooperatives_plan" class="input-field"></td>
                    </tr>
                    <tr>
                        <td>Members of the cooperatives Organized in Forest management [numbers]</td>
                        <td><input type="number" name="cooperative_members_baseline" class="input-field"></td>
                        <td><input type="number" name="cooperative_members_plan" class="input-field"></td>
                    </tr>
                    <tr>
                        <td>Areas prepared for dry waste management [numbers]</td>
                        <td><input type="number" name="dry_waste_areas_baseline" class="input-field"></td>
                        <td><input type="number" name="dry_waste_areas_plan" class="input-field"></td>
                    </tr>
                    <tr>
                        <td>A latrine/toilet</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Constructed [Numbers]</td>
                        <td><input type="number" name="latrine_private_baseline" class="input-field" placeholder="private"></td>
                        <td><input type="number" name="latrine_private_plan" class="input-field" placeholder="private"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="number" name="latrine_community_baseline" class="input-field" placeholder="community"></td>
                        <td><input type="number" name="latrine_community_plan" class="input-field" placeholder="community"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="number" name="latrine_total_baseline" class="input-field calculated-field" readonly></td>
                        <td><input type="number" name="latrine_total_plan" class="input-field calculated-field" readonly></td>
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

        // Calculate latrine totals
        function calculateLatrineTotals() {
            // Baseline calculation
            const privateBaseline = parseFloat(document.querySelector('input[name="latrine_private_baseline"]').value) || 0;
            const communityBaseline = parseFloat(document.querySelector('input[name="latrine_community_baseline"]').value) || 0;
            document.querySelector('input[name="latrine_total_baseline"]').value = privateBaseline + communityBaseline;

            // Plan calculation
            const privatePlan = parseFloat(document.querySelector('input[name="latrine_private_plan"]').value) || 0;
            const communityPlan = parseFloat(document.querySelector('input[name="latrine_community_plan"]').value) || 0;
            document.querySelector('input[name="latrine_total_plan"]').value = privatePlan + communityPlan;
        }

        // Set up event listeners for latrine inputs
        document.querySelector('input[name="latrine_private_baseline"]')?.addEventListener('input', calculateLatrineTotals);
        document.querySelector('input[name="latrine_community_baseline"]')?.addEventListener('input', calculateLatrineTotals);
        document.querySelector('input[name="latrine_private_plan"]')?.addEventListener('input', calculateLatrineTotals);
        document.querySelector('input[name="latrine_community_plan"]')?.addEventListener('input', calculateLatrineTotals);

        // Year selection event listeners
        baselineYearSelect.addEventListener('change', updateYearDisplays);
        planYearInput.addEventListener('input', updateYearDisplays);

        // Initialize
        updateYearDisplays();
        calculateLatrineTotals(); // Calculate initial totals if any values are pre-filled
    });
    </script>
</x-layout>