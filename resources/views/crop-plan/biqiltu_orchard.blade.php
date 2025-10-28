<x-layout>
    <title>Agricultural Orchard Data Entry</title>
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
    </style>

    <div class="form-container">
        <h2>Orchard and Seedling Data Entry</h2>
        
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

        <form id="data-form" method="POST" action="{{ route('orchard.store') }}">
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
            <input type="hidden" name="baseline_year" id="baseline_year_hidden">
            <input type="hidden" name="plan_year" id="plan_year_hidden">
            
            <table>
                <thead>
                    <tr>
                        <th>Preparation</th>
                        <th id="baseline_header">Baseline year (<span id="baseline_year_display">2017</span> EFY)</th>
                        <th id="plan_header">Plan year (<span id="plan_year_display">2018</span> EFY)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Orchard Sites Section -->
                    <tr class="category-row">
                        <td><strong>Orchard Sites</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Area [Square-meters]</td>
                        <td><input type="text" name="orchard_area_baseline" class="input-field"></td>
                        <td><input type="text" name="orchard_area_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>The Participant</td>
                        <td><input type="text" name="orchard_participant_baseline" class="input-field"></td>
                        <td><input type="text" name="orchard_participant_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Number of Days</td>
                        <td><input type="text" name="orchard_days_baseline" class="input-field"></td>
                        <td><input type="text" name="orchard_days_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Other seedling Section -->
                    <tr class="category-row">
                        <td><strong>Other seedling</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Green legacy</td>
                        <td><input type="text" name="signal_baseline" class="input-field"></td>
                        <td><input type="text" name="signal_plan" class="input-field"></td>
                    </tr>
                    
                    <tr class="subcategory-row">
                        <td>The Spices</td>
                        <td><input type="text" name="sweetener_baseline" class="input-field"></td>
                        <td><input type="text" name="sweetener_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Coffee</td>
                        <td><input type="text" name="coffee_baseline" class="input-field"></td>
                        <td><input type="text" name="coffee_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Tea</td>
                        <td><input type="text" name="tea_baseline" class="input-field"></td>
                        <td><input type="text" name="tea_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>The Participant</td>
                        <td><input type="text" name="seedling_participant_baseline" class="input-field"></td>
                        <td><input type="text" name="seedling_participant_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Number of Days</td>
                        <td><input type="text" name="seedling_days_baseline" class="input-field"></td>
                        <td><input type="text" name="seedling_days_plan" class="input-field"></td>
                    </tr>
                    
                    <!-- Fruits Section -->
                    <tr class="category-row">
                        <td><strong>Fruits</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Avokaadoo</td>
                        <td><input type="text" name="avokaadoo_baseline" class="input-field"></td>
                        <td><input type="text" name="avokaadoo_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Paappaayaa</td>
                        <td><input type="text" name="paappaayaa_baseline" class="input-field"></td>
                        <td><input type="text" name="paappaayaa_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Appilli</td>
                        <td><input type="text" name="appilli_baseline" class="input-field"></td>
                        <td><input type="text" name="appilli_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Others</td>
                        <td><input type="text" name="fruits_others_baseline" class="input-field"></td>
                        <td><input type="text" name="fruits_others_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>The Participant</td>
                        <td><input type="text" name="fruits_participant_baseline" class="input-field"></td>
                        <td><input type="text" name="fruits_participant_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Number of Days</td>
                        <td><input type="text" name="fruits_days_baseline" class="input-field"></td>
                        <td><input type="text" name="fruits_days_plan" class="input-field"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>Ida'ama</td>
                        <td><input type="text" name="fruits_idaama_baseline" class="input-field" readonly></td>
                        <td><input type="text" name="fruits_idaama_plan" class="input-field" readonly></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>participant </td>
                        <td><input type="text" name="total_number_of_participant_baseline" class="input-field" readonly></td>
                        <td><input type="text" name="total_number_of_participant_plan" class="input-field" readonly></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td>days  </td>
                        <td><input type="text" name="total_number_of_days_baseline" class="input-field" readonly></td>
                        <td><input type="text" name="total_number_of_days_plan" class="input-field" readonly></td>
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

        // Calculate all totals (Ida'ama, participants, and days)
        function calculateAllTotals() {
            // List of all input fields to exclude from Ida'ama calculation
            const excludedIdaamaPatterns = [
                /orchard_/, // All orchard fields
                /_participant/, // All participant fields
                /_days/ // All days fields
            ];

            // Get all input fields in the form
            const allInputs = document.querySelectorAll('input[type="text"]:not([readonly])');
            
            // Add event listeners to all relevant inputs
            allInputs.forEach(input => {
                input.addEventListener('input', updateAllTotals);
            });

            function updateAllTotals() {
                // Variables for Ida'ama calculation
                let baselineIdaamaTotal = 0;
                let planIdaamaTotal = 0;
                
                // Variables for participant calculation
                let baselineParticipantTotal = 0;
                let planParticipantTotal = 0;
                
                // Variables for days calculation
                let baselineDaysTotal = 0;
                let planDaysTotal = 0;

                // Calculate participant totals (only specific participant fields)
                const participantFields = [
                    'orchard_participant',
                    'seedling_participant',
                    'fruits_participant'
                ];
                
                participantFields.forEach(field => {
                    const baselineInput = document.querySelector(`input[name="${field}_baseline"]`);
                    const planInput = document.querySelector(`input[name="${field}_plan"]`);
                    
                    if (baselineInput) baselineParticipantTotal += parseFloat(baselineInput.value) || 0;
                    if (planInput) planParticipantTotal += parseFloat(planInput.value) || 0;
                });

                // Calculate days totals (only specific days fields)
                const daysFields = [
                    'orchard_days',
                    'seedling_days',
                    'fruits_days'
                ];
                
                daysFields.forEach(field => {
                    const baselineInput = document.querySelector(`input[name="${field}_baseline"]`);
                    const planInput = document.querySelector(`input[name="${field}_plan"]`);
                    
                    if (baselineInput) baselineDaysTotal += parseFloat(baselineInput.value) || 0;
                    if (planInput) planDaysTotal += parseFloat(planInput.value) || 0;
                });

                // Calculate Ida'ama totals (all other relevant fields)
                document.querySelectorAll('input[name$="_baseline"]').forEach(input => {
                    if (!excludedIdaamaPatterns.some(pattern => pattern.test(input.name)) && 
                        !input.readOnly && 
                        !participantFields.some(field => input.name.includes(field)) &&
                        !daysFields.some(field => input.name.includes(field))) {
                        baselineIdaamaTotal += parseFloat(input.value) || 0;
                    }
                });

                document.querySelectorAll('input[name$="_plan"]').forEach(input => {
                    if (!excludedIdaamaPatterns.some(pattern => pattern.test(input.name)) && 
                        !input.readOnly && 
                        !participantFields.some(field => input.name.includes(field)) &&
                        !daysFields.some(field => input.name.includes(field))) {
                        planIdaamaTotal += parseFloat(input.value) || 0;
                    }
                });

                // Update all calculated fields
                document.querySelector('input[name="fruits_idaama_baseline"]').value = baselineIdaamaTotal;
                document.querySelector('input[name="fruits_idaama_plan"]').value = planIdaamaTotal;
                document.querySelector('input[name="total_number_of_participant_baseline"]').value = baselineParticipantTotal;
                document.querySelector('input[name="total_number_of_participant_plan"]').value = planParticipantTotal;
                document.querySelector('input[name="total_number_of_days_baseline"]').value = baselineDaysTotal;
                document.querySelector('input[name="total_number_of_days_plan"]').value = planDaysTotal;
            }
        }

        // Initialize all calculations
        calculateAllTotals();
    });
</script>
</x-layout>