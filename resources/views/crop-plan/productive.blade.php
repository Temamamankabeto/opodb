<x-layout>
    <title>Agricultural Production Data Entry</title>
    <style>
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
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
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
            font-weight: 600;
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
            font-weight: 500;
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
            font-weight: 500;
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
        
        .category-row {
            font-weight: 600;
            background-color: rgba(52, 152, 219, 0.15) !important;
        }
        
        .category-row td:first-child {
            border-left: 4px solid var(--primary-color);
        }
        
        .subcategory-row td:first-child {
            padding-left: 30px;
            color: #555;
        }
        
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
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
            
            th, td {
                padding: 8px 10px;
                font-size: 0.9em;
            }
        }
        
        .indented-row {
            padding-left: 30px;
        }
        
        .total-row {
            font-weight: bold;
            background-color: rgba(52, 152, 219, 0.2) !important;
        }
    </style>

    <div class="form-container">
        <h2>Agricultural Production Data Entry</h2>
        
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

        <form id="data-form" method="POST" action="{{ route('productive.store') }}">
            @csrf
            <input type="hidden" name="baseline_year" id="baseline_year_hidden">
            <input type="hidden" name="plan_year" id="plan_year_hidden">
            
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Productive Area (ha)</th>
                        <th id="baseline_header">Baseline Year (<span id="baseline_year_display">2017</span> EFY achievement)</th>
                        <th id="plan_header">Plan of <span id="plan_year_display">2018</span> EFY</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Banana Section -->
                    <tr class="category-row">
                        <td>Banana</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>production</td>
                        <td><input type="text" name="banana_production_baseline" class="input-field"></td>
                        <td><input type="text" name="banana_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="banana_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="banana_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Avocado Section -->
                    <tr class="category-row">
                        <td>Avocado</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>production</td>
                        <td><input type="text" name="avocado_production_baseline" class="input-field"></td>
                        <td><input type="text" name="avocado_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="avocado_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="avocado_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Papaya Section -->
                    <tr class="category-row">
                        <td>Papaya</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Production</td>
                        <td><input type="text" name="papaya_production_baseline" class="input-field"></td>
                        <td><input type="text" name="papaya_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="papaya_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="papaya_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Apple Section -->
                    <tr class="category-row">
                        <td>Apple</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Production</td>
                        <td><input type="text" name="apple_production_baseline" class="input-field"></td>
                        <td><input type="text" name="apple_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="apple_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="apple_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Other Section -->
                    <tr class="category-row">
                        <td>Other</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Production</td>
                        <td><input type="text" name="other_production_baseline" class="input-field"></td>
                        <td><input type="text" name="other_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="other_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="other_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Different Spices Section -->
                    <tr class="category-row">
                        <td>Different Spices [Productive area in ha]</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Production</td>
                        <td><input type="text" name="spices_production_baseline" class="input-field"></td>
                        <td><input type="text" name="spices_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="spices_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="spices_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Coffee Section -->
                    <tr class="category-row">
                        <td>Coffee</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Production</td>
                        <td><input type="text" name="coffee_production_baseline" class="input-field"></td>
                        <td><input type="text" name="coffee_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="coffee_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="coffee_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Tea Section -->
                    <tr class="category-row">
                        <td>Tea</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Production</td>
                        <td><input type="text" name="tea_production_baseline" class="input-field"></td>
                        <td><input type="text" name="tea_production_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>land area</td>
                        <td><input type="text" name="tea_land_area_baseline" class="input-field"></td>
                        <td><input type="text" name="tea_land_area_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Totals Section -->
                    <tr class="total-row">
                        <td>Total productive land</td>
                        <td></td>
                        <td><input type="text" name="total_productive_land_baseline" class="input-field" readonly></td>
                        <td><input type="text" name="total_productive_land_plan" class="input-field" readonly></td>
                    </tr>
                    <tr class="total-row">
                        <td>Total production</td>
                        <td></td>
                        <td><input type="text" name="total_production_baseline" class="input-field" readonly></td>
                        <td><input type="text" name="total_production_plan" class="input-field" readonly></td>
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
        const baselineYearSelect = document.getElementById('baseline_year');
        const planYearInput = document.getElementById('plan_year');
        const baselineYearDisplay = document.getElementById('baseline_year_display');
        const planYearDisplay = document.getElementById('plan_year_display');
        const baselineYearHidden = document.getElementById('baseline_year_hidden');
        const planYearHidden = document.getElementById('plan_year_hidden');
        
        // Function to update year displays
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
        
        // Event listeners for year changes
        baselineYearSelect.addEventListener('change', updateYearDisplays);
        planYearInput.addEventListener('input', updateYearDisplays);
        
        // Initialize with default values if any
        updateYearDisplays();

        // Calculate all totals
        function calculateAllTotals() {
            // Get all input fields in the form
            const allInputs = document.querySelectorAll('input[type="text"]:not([readonly])');
            
            // Add event listeners to all relevant inputs
            allInputs.forEach(input => {
                input.addEventListener('input', updateAllTotals);
            });

            function updateAllTotals() {
                // Variables for land area totals
                let baselineLandTotal = 0;
                let planLandTotal = 0;
                
                // Variables for production totals
                let baselineProductionTotal = 0;
                let planProductionTotal = 0;

                // Calculate totals for all land areas
                const landAreaFields = [
                    'banana_land_area',
                    'avocado_land_area',
                    'papaya_land_area',
                    'apple_land_area',
                    'other_land_area',
                    'spices_land_area',
                    'coffee_land_area',
                    'tea_land_area'
                ];
                
                landAreaFields.forEach(field => {
                    const baselineInput = document.querySelector(`input[name="${field}_baseline"]`);
                    const planInput = document.querySelector(`input[name="${field}_plan"]`);
                    
                    if (baselineInput) baselineLandTotal += parseFloat(baselineInput.value) || 0;
                    if (planInput) planLandTotal += parseFloat(planInput.value) || 0;
                });

                // Calculate totals for all productions
                const productionFields = [
                    'banana_production',
                    'avocado_production',
                    'papaya_production',
                    'apple_production',
                    'other_production',
                    'spices_production',
                    'coffee_production',
                    'tea_production'
                ];
                
                productionFields.forEach(field => {
                    const baselineInput = document.querySelector(`input[name="${field}_baseline"]`);
                    const planInput = document.querySelector(`input[name="${field}_plan"]`);
                    
                    if (baselineInput) baselineProductionTotal += parseFloat(baselineInput.value) || 0;
                    if (planInput) planProductionTotal += parseFloat(planInput.value) || 0;
                });

                // Update all calculated fields
                document.querySelector('input[name="total_productive_land_baseline"]').value = baselineLandTotal;
                document.querySelector('input[name="total_productive_land_plan"]').value = planLandTotal;
                document.querySelector('input[name="total_production_baseline"]').value = baselineProductionTotal;
                document.querySelector('input[name="total_production_plan"]').value = planProductionTotal;
            }
        }

        // Initialize all calculations
        calculateAllTotals();
    });
</script>
</x-layout>