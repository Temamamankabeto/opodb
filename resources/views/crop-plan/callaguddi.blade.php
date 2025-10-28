<x-layout>
    <title>Agricultural Input Data Entry</title>
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
    </style>

<div class="form-container">
        <h2>Ittifayyadama Callaa Guddistuu</h2>
        
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

        <form id="data-form" method="POST" action="{{ route('agricultural-inputs.store') }}" style="display: none;">
            @csrf
            <input type="hidden" name="baseline_year" id="hidden_baseline_year">
            <input type="hidden" name="plan_year" id="hidden_plan_year">
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 30%;">Chemical Type</th>
                        <th style="width: 20%;">Season</th>
                        <th style="width: 25%;" id="baseline_header">Baseline Year</th>
                        <th style="width: 25%;" id="plan_header">Plan Year</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Chemical fertilizers [Tons] -->
                    <tr class="category-row">
                        <td>Chemical fertilizers [Tons]</td>
                        <td>Ganna</td>
                        <td><input type="number" class="fertilizer-baseline" name="chemical_fertilizer_baseline_ganna" step="0.01"></td>
                        <td><input type="number" class="fertilizer-plan" name="chemical_fertilizer_plan_ganna" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Bona/Jallisii</td>
                        <td><input type="number" class="fertilizer-baseline" name="chemical_fertilizer_baseline_bona" step="0.01"></td>
                        <td><input type="number" class="fertilizer-plan" name="chemical_fertilizer_plan_bona" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Arfaasaa</td>
                        <td><input type="number" class="fertilizer-baseline" name="chemical_fertilizer_baseline_arfaasaa" step="0.01"></td>
                        <td><input type="number" class="fertilizer-plan" name="chemical_fertilizer_plan_arfaasaa" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="number" class="fertilizer-total-baseline" name="chemical_fertilizer_baseline_idaama" readonly></td>
                        <td><input type="number" class="fertilizer-total-plan" name="chemical_fertilizer_plan_idaama" readonly></td>
                    </tr>

                    <!-- Selective Seed [Tons] -->
                    <tr class="category-row">
                        <td>Selective Seed [Tons]</td>
                        <td>Ganna</td>
                        <td><input type="number" class="seed-baseline" name="selective_seed_baseline_ganna" step="0.01"></td>
                        <td><input type="number" class="seed-plan" name="selective_seed_plan_ganna" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Bona/Jallisii</td>
                        <td><input type="number" class="seed-baseline" name="selective_seed_baseline_bona" step="0.01"></td>
                        <td><input type="number" class="seed-plan" name="selective_seed_plan_bona" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Arfaasaa</td>
                        <td><input type="number" class="seed-baseline" name="selective_seed_baseline_arfaasaa" step="0.01"></td>
                        <td><input type="number" class="seed-plan" name="selective_seed_plan_arfaasaa" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="number" class="seed-total-baseline" name="selective_seed_baseline_idaama" readonly></td>
                        <td><input type="number" class="seed-total-plan" name="selective_seed_plan_idaama" readonly></td>
                    </tr>

                    <!-- Chemical pesticide and insecticide sprayers [Litre] -->
                    <tr class="category-row">
                        <td>Chemical pesticide and insecticide sprayers [Litre]</td>
                        <td>Ganna</td>
                        <td><input type="number" class="pesticide-baseline" name="pesticide_baseline_ganna" step="0.01"></td>
                        <td><input type="number" class="pesticide-plan" name="pesticide_plan_ganna" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Bona/Jallisii</td>
                        <td><input type="number" class="pesticide-baseline" name="pesticide_baseline_bona" step="0.01"></td>
                        <td><input type="number" class="pesticide-plan" name="pesticide_plan_bona" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Arfaasaa</td>
                        <td><input type="number" class="pesticide-baseline" name="pesticide_baseline_arfaasaa" step="0.01"></td>
                        <td><input type="number" class="pesticide-plan" name="pesticide_plan_arfaasaa" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="number" class="pesticide-total-baseline" name="pesticide_baseline_idaama" readonly></td>
                        <td><input type="number" class="pesticide-total-plan" name="pesticide_plan_idaama" readonly></td>
                    </tr>

                    <!-- Spraying of pesticides and insecticides [Hectares of land] -->
                    <tr class="category-row">
                        <td>Spraying of pesticides and insecticides [Hectares of land]</td>
                        <td>Ganna</td>
                        <td><input type="number" class="spraying-baseline" name="spraying_baseline_ganna" step="0.01"></td>
                        <td><input type="number" class="spraying-plan" name="spraying_plan_ganna" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Bona/Jallisii</td>
                        <td><input type="number" class="spraying-baseline" name="spraying_baseline_bona" step="0.01"></td>
                        <td><input type="number" class="spraying-plan" name="spraying_plan_bona" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Arfaasaa</td>
                        <td><input type="number" class="spraying-baseline" name="spraying_baseline_arfaasaa" step="0.01"></td>
                        <td><input type="number" class="spraying-plan" name="spraying_plan_arfaasaa" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>Ida'ama</td>
                        <td><input type="number" class="spraying-total-baseline" name="spraying_baseline_idaama" readonly></td>
                        <td><input type="number" class="spraying-total-plan" name="spraying_plan_idaama" readonly></td>
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
            
            // Function to calculate totals
            function calculateTotals() {
                // Chemical fertilizers totals
                const fertilizerBaselineInputs = document.querySelectorAll('.fertilizer-baseline');
                const fertilizerPlanInputs = document.querySelectorAll('.fertilizer-plan');
                calculateTotal(fertilizerBaselineInputs, '.fertilizer-total-baseline');
                calculateTotal(fertilizerPlanInputs, '.fertilizer-total-plan');
                
                // Selective seed totals
                const seedBaselineInputs = document.querySelectorAll('.seed-baseline');
                const seedPlanInputs = document.querySelectorAll('.seed-plan');
                calculateTotal(seedBaselineInputs, '.seed-total-baseline');
                calculateTotal(seedPlanInputs, '.seed-total-plan');
                
                // Pesticide totals
                const pesticideBaselineInputs = document.querySelectorAll('.pesticide-baseline');
                const pesticidePlanInputs = document.querySelectorAll('.pesticide-plan');
                calculateTotal(pesticideBaselineInputs, '.pesticide-total-baseline');
                calculateTotal(pesticidePlanInputs, '.pesticide-total-plan');
                
                // Spraying totals
                const sprayingBaselineInputs = document.querySelectorAll('.spraying-baseline');
                const sprayingPlanInputs = document.querySelectorAll('.spraying-plan');
                calculateTotal(sprayingBaselineInputs, '.spraying-total-baseline');
                calculateTotal(sprayingPlanInputs, '.spraying-total-plan');
            }
            
            // Helper function to calculate total for a group of inputs
            function calculateTotal(inputs, totalSelector) {
                let total = 0;
                inputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    total += value;
                });
                document.querySelector(totalSelector).value = total.toFixed(2);
            }
            
            // Event listeners for input changes
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', calculateTotals);
            });
            
            // Event listeners for year selection
            baselineYearSelect.addEventListener('change', validateYears);
            planYearInput.addEventListener('input', validateYears);
        });
    </script>
</x-layout>