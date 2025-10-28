<x-layout>
    <title>Village Resource Data Entry</title>
    <style>
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
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: #3498db;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .year-selection {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            background: #f5f5f5;
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
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: #3498db;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }
        
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        
        .category-row {
            font-weight: 600;
            background-color: rgba(52, 152, 219, 0.15) !important;
        }
        
        .category-row td:first-child {
            border-left: 4px solid #3498db;
        }
        
        .subcategory-row td:first-child {
            padding-left: 30px;
            color: #555;
        }
        
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 14px;
            transition: border 0.3s;
        }
        
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            outline: none;
            border-color: #3498db;
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
            background-color: #3498db;
            color: white;
        }
        
        .submit-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .reset-btn {
            background-color: #e0e0e0;
            color: #333;
        }
        
        .reset-btn:hover {
            background-color: #d0d0d0;
        }
    </style>
 
<div class="form-container">
        <h2>Qabeenya Mekaanaayizeeshinii Horatame</h2>

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
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="plan_year">Enter Plan Year</label>
                <input type="number" id="plan_year" class="form-control" placeholder="e.g., 2026" min="2000" max="2100" required>
            </div>
        </div>

        <form id="data-form" method="POST" action="{{ route('village-resources.store') }}" style="display: none;">
            @csrf
            <input type="hidden" name="baseline_year" id="hidden_baseline_year">
            <input type="hidden" name="plan_year" id="hidden_plan_year">
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 30%;">Resource Type</th>
                        <th style="width: 20%;">Owner</th>
                        <th style="width: 25%;" id="baseline_header">Baseline Year</th>
                        <th style="width: 25%;" id="plan_header">Plan Year</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Tractors in the village -->
                    <tr class="category-row">
                        <td>Tractors in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="tractors_private_baseline" class="calc-baseline" data-group="tractors"></td>
                        <td><input type="text" name="tractors_private_plan" class="calc-plan" data-group="tractors"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="tractors_association_baseline" class="calc-baseline" data-group="tractors"></td>
                        <td><input type="text" name="tractors_association_plan" class="calc-plan" data-group="tractors"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="tractors_idaama_baseline" class="calculated" data-sum-baseline="tractors" readonly></td>
                        <td><input type="text" name="tractors_idaama_plan" class="calculated" data-sum-plan="tractors" readonly></td>
                    </tr>

                    <!-- A combiner in the village -->
                    <tr class="category-row">
                        <td>A combiner in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="combiner_private_baseline" class="calc-baseline" data-group="combiner"></td>
                        <td><input type="text" name="combiner_private_plan" class="calc-plan" data-group="combiner"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="combiner_association_baseline" class="calc-baseline" data-group="combiner"></td>
                        <td><input type="text" name="combiner_association_plan" class="calc-plan" data-group="combiner"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="combiner_idaama_baseline" class="calculated" data-sum-baseline="combiner" readonly></td>
                        <td><input type="text" name="combiner_idaama_plan" class="calculated" data-sum-plan="combiner" readonly></td>
                    </tr>

                    <!-- Water pump/Motor in the village -->
                    <tr class="category-row">
                        <td>Water pump/Motor in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="water_pump_private_baseline" class="calc-baseline" data-group="water_pump"></td>
                        <td><input type="text" name="water_pump_private_plan" class="calc-plan" data-group="water_pump"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="water_pump_association_baseline" class="calc-baseline" data-group="water_pump"></td>
                        <td><input type="text" name="water_pump_association_plan" class="calc-plan" data-group="water_pump"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="water_pump_idaama_baseline" class="calculated" data-sum-baseline="water_pump" readonly></td>
                        <td><input type="text" name="water_pump_idaama_plan" class="calculated" data-sum-plan="water_pump" readonly></td>
                    </tr>

                    <!-- Thresher in the village -->
                    <tr class="category-row">
                        <td>Thresher in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="thresher_private_baseline" class="calc-baseline" data-group="thresher"></td>
                        <td><input type="text" name="thresher_private_plan" class="calc-plan" data-group="thresher"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="thresher_association_baseline" class="calc-baseline" data-group="thresher"></td>
                        <td><input type="text" name="thresher_association_plan" class="calc-plan" data-group="thresher"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="thresher_idaama_baseline" class="calculated" data-sum-baseline="thresher" readonly></td>
                        <td><input type="text" name="thresher_idaama_plan" class="calculated" data-sum-plan="thresher" readonly></td>
                    </tr>

                    <!-- Planter in the village -->
                    <tr class="category-row">
                        <td>Planter in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="planter_private_baseline" class="calc-baseline" data-group="planter"></td>
                        <td><input type="text" name="planter_private_plan" class="calc-plan" data-group="planter"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="planter_association_baseline" class="calc-baseline" data-group="planter"></td>
                        <td><input type="text" name="planter_association_plan" class="calc-plan" data-group="planter"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="planter_idaama_baseline" class="calculated" data-sum-baseline="planter" readonly></td>
                        <td><input type="text" name="planter_idaama_plan" class="calculated" data-sum-plan="planter" readonly></td>
                    </tr>

                    <!-- Sprayer in the village -->
                    <tr class="category-row">
                        <td>Sprayer in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="sprayer_private_baseline" class="calc-baseline" data-group="sprayer"></td>
                        <td><input type="text" name="sprayer_private_plan" class="calc-plan" data-group="sprayer"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="sprayer_association_baseline" class="calc-baseline" data-group="sprayer"></td>
                        <td><input type="text" name="sprayer_association_plan" class="calc-plan" data-group="sprayer"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="sprayer_idaama_baseline" class="calculated" data-sum-baseline="sprayer" readonly></td>
                        <td><input type="text" name="sprayer_idaama_plan" class="calculated" data-sum-plan="sprayer" readonly></td>
                    </tr>

                    <!-- Other machinery in the village -->
                    <tr class="category-row">
                        <td>Other machinery in the village</td>
                        <td>Private</td>
                        <td><input type="text" name="other_machinery_private_baseline" class="calc-baseline" data-group="other_machinery"></td>
                        <td><input type="text" name="other_machinery_private_plan" class="calc-plan" data-group="other_machinery"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Association</td>
                        <td><input type="text" name="other_machinery_association_baseline" class="calc-baseline" data-group="other_machinery"></td>
                        <td><input type="text" name="other_machinery_association_plan" class="calc-plan" data-group="other_machinery"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="text" name="other_machinery_idaama_baseline" class="calculated" data-sum-baseline="other_machinery" readonly></td>
                        <td><input type="text" name="other_machinery_idaama_plan" class="calculated" data-sum-plan="other_machinery" readonly></td>
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
            const dataForm = document.getElementById('data-form');
            const baselineHeader = document.getElementById('baseline_header');
            const planHeader = document.getElementById('plan_header');
            const hiddenBaselineYear = document.getElementById('hidden_baseline_year');
            const hiddenPlanYear = document.getElementById('hidden_plan_year');
            
            // Function to validate years and show form
            function validateYears() {
                const baselineYear = baselineYearSelect.value;
                const planYear = planYearInput.value;
                
                if (baselineYear && planYear && parseInt(planYear) > parseInt(baselineYear)) {
                    // Update headers
                    baselineHeader.textContent = `Baseline Year ${baselineYear}`;
                    planHeader.textContent = `Plan Year ${planYear}`;
                    
                    // Update hidden fields
                    hiddenBaselineYear.value = baselineYear;
                    hiddenPlanYear.value = planYear;
                    
                    // Show the form
                    dataForm.style.display = 'block';
                } else {
                    dataForm.style.display = 'none';
                }
            }
            
            // Function to calculate sums
            function calculateSums() {
                // Get all resource groups
                const groups = ['tractors', 'combiner', 'water_pump', 'thresher', 'planter', 'sprayer', 'other_machinery'];
                
                groups.forEach(group => {
                    // Calculate baseline sum
                    const baselineInputs = document.querySelectorAll(`.calc-baseline[data-group="${group}"]`);
                    let baselineSum = 0;
                    baselineInputs.forEach(input => {
                        const value = parseFloat(input.value) || 0;
                        baselineSum += value;
                    });
                    document.querySelector(`.calculated[data-sum-baseline="${group}"]`).value = baselineSum;
                    
                    // Calculate plan sum
                    const planInputs = document.querySelectorAll(`.calc-plan[data-group="${group}"]`);
                    let planSum = 0;
                    planInputs.forEach(input => {
                        const value = parseFloat(input.value) || 0;
                        planSum += value;
                    });
                    document.querySelector(`.calculated[data-sum-plan="${group}"]`).value = planSum;
                });
            }
            
            // Add event listeners to all calculation inputs
            const calculationInputs = document.querySelectorAll('.calc-baseline, .calc-plan');
            calculationInputs.forEach(input => {
                input.addEventListener('input', calculateSums);
            });
            
            // Event listeners for year selection
            baselineYearSelect.addEventListener('change', validateYears);
            planYearInput.addEventListener('input', validateYears);
            
            // Initial calculations
            calculateSums();
        });
    </script>
</x-layout>