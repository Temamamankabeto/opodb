<x-layout>
    <title>Livestock and Fisheries Data Entry</title>

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
        <h2>Livestock and Fisheries Production Data</h2>

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

        <form id="data-form" method="POST" action="{{ route('livestock.production.store') }}">
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
                        <td>Calves borne through AI [Numbers]</td>
                        <td><input type="number" name="calves_ai_baseline" class="input-field"></td>
                        <td><input type="number" name="calves_ai_plan" class="input-field"></td>
                    </tr>
                    <tr>
                        <td>Eggs produced [Numbers]</td>
                        <td><input type="number" name="eggs_produced_baseline" class="input-field" id="eggs_produced_baseline"></td>
                        <td><input type="number" name="eggs_produced_plan" class="input-field" id="eggs_produced_plan"></td>
                    </tr>
                    <tr>
                        <td>Number of chicken laying egg</td>
                        <td><input type="number" name="laying_chickens_baseline" class="input-field" id="laying_chickens_baseline"></td>
                        <td><input type="number" name="laying_chickens_plan" class="input-field" id="laying_chickens_plan"></td>
                    </tr>
                    <tr>
                        <td>Number eggs per chicken</td>
                        <td><input type="number" name="eggs_per_chicken_baseline" class="input-field calculated-field" step="0.01" id="eggs_per_chicken_baseline" readonly></td>
                        <td><input type="number" name="eggs_per_chicken_plan" class="input-field calculated-field" step="0.01" id="eggs_per_chicken_plan" readonly></td>
                    </tr>
                    <tr>
                        <td>Meat chicken supplied to the market and marketed [Numbers]</td>
                        <td><input type="number" name="meat_chicken_baseline" class="input-field" id="meat_chicken_baseline"></td>
                        <td><input type="number" name="meat_chicken_plan" class="input-field" id="meat_chicken_plan"></td>
                    </tr>
                    <tr>
                        <td>Number of chickens given meat</td>
                        <td><input type="number" name="meat_chickens_count_baseline" class="input-field" id="meat_chickens_count_baseline"></td>
                        <td><input type="number" name="meat_chickens_count_plan" class="input-field" id="meat_chickens_count_plan"></td>
                    </tr>
                    <tr>
                        <td>Meat per chicken</td>
                        <td><input type="number" name="meat_per_chicken_baseline" class="input-field calculated-field" step="0.01" id="meat_per_chicken_baseline" readonly></td>
                        <td><input type="number" name="meat_per_chicken_plan" class="input-field calculated-field" step="0.01" id="meat_per_chicken_plan" readonly></td>
                    </tr>
                    <tr>
                        <td>Honey produced [Kg]</td>
                        <td><input type="number" name="honey_produced_baseline" class="input-field" step="0.01"></td>
                        <td><input type="number" name="honey_produced_plan" class="input-field" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Honey per modern beehive</td>
                        <td><input type="number" name="honey_per_beehive_baseline" class="input-field" step="0.01"></td>
                        <td><input type="number" name="honey_per_beehive_plan" class="input-field" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Fish supplied to market [Kg]</td>
                        <td><input type="number" name="fish_supplied_baseline" class="input-field" step="0.01"></td>
                        <td><input type="number" name="fish_supplied_plan" class="input-field" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Amount of milk produced [Liter]</td>
                        <td><input type="number" name="milk_produced_baseline" class="input-field" id="milk_produced_baseline" step="0.01"></td>
                        <td><input type="number" name="milk_produced_plan" class="input-field" id="milk_produced_plan" step="0.01"></td>
                    </tr>
                    <tr>
                        <td>Number of lactating cows</td>
                        <td><input type="number" name="lactating_cows_baseline" class="input-field" id="lactating_cows_baseline"></td>
                        <td><input type="number" name="lactating_cows_plan" class="input-field" id="lactating_cows_plan"></td>
                    </tr>
                    <tr>
                        <td>Milk per lactating cow</td>
                        <td><input type="number" name="milk_per_cow_baseline" class="input-field calculated-field" step="0.01" id="milk_per_cow_baseline" readonly></td>
                        <td><input type="number" name="milk_per_cow_plan" class="input-field calculated-field" step="0.01" id="milk_per_cow_plan" readonly></td>
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

        // Productivity calculations
        function calculateProductivity() {
            // Eggs per chicken calculation
            const eggsProducedBaseline = parseFloat(document.getElementById('eggs_produced_baseline').value) || 0;
            const layingChickensBaseline = parseFloat(document.getElementById('laying_chickens_baseline').value) || 0;
            const eggsProducedPlan = parseFloat(document.getElementById('eggs_produced_plan').value) || 0;
            const layingChickensPlan = parseFloat(document.getElementById('laying_chickens_plan').value) || 0;

            if (layingChickensBaseline > 0) {
                document.getElementById('eggs_per_chicken_baseline').value = (eggsProducedBaseline / layingChickensBaseline).toFixed(2);
            }
            if (layingChickensPlan > 0) {
                document.getElementById('eggs_per_chicken_plan').value = (eggsProducedPlan / layingChickensPlan).toFixed(2);
            }

            // Meat per chicken calculation
            const meatChickenBaseline = parseFloat(document.getElementById('meat_chicken_baseline').value) || 0;
            const meatChickensCountBaseline = parseFloat(document.getElementById('meat_chickens_count_baseline').value) || 0;
            const meatChickenPlan = parseFloat(document.getElementById('meat_chicken_plan').value) || 0;
            const meatChickensCountPlan = parseFloat(document.getElementById('meat_chickens_count_plan').value) || 0;

            if (meatChickensCountBaseline > 0) {
                document.getElementById('meat_per_chicken_baseline').value = (meatChickenBaseline / meatChickensCountBaseline).toFixed(2);
            }
            if (meatChickensCountPlan > 0) {
                document.getElementById('meat_per_chicken_plan').value = (meatChickenPlan / meatChickensCountPlan).toFixed(2);
            }

            // Milk per cow calculation
            const milkProducedBaseline = parseFloat(document.getElementById('milk_produced_baseline').value) || 0;
            const lactatingCowsBaseline = parseFloat(document.getElementById('lactating_cows_baseline').value) || 0;
            const milkProducedPlan = parseFloat(document.getElementById('milk_produced_plan').value) || 0;
            const lactatingCowsPlan = parseFloat(document.getElementById('lactating_cows_plan').value) || 0;

            if (lactatingCowsBaseline > 0) {
                document.getElementById('milk_per_cow_baseline').value = (milkProducedBaseline / lactatingCowsBaseline).toFixed(2);
            }
            if (lactatingCowsPlan > 0) {
                document.getElementById('milk_per_cow_plan').value = (milkProducedPlan / lactatingCowsPlan).toFixed(2);
            }
        }

        // Set up event listeners for all input fields that affect calculations
        const calculationInputs = [
            'eggs_produced_baseline', 'laying_chickens_baseline',
            'eggs_produced_plan', 'laying_chickens_plan',
            'meat_chicken_baseline', 'meat_chickens_count_baseline',
            'meat_chicken_plan', 'meat_chickens_count_plan',
            'milk_produced_baseline', 'lactating_cows_baseline',
            'milk_produced_plan', 'lactating_cows_plan'
        ];

        calculationInputs.forEach(id => {
            document.getElementById(id)?.addEventListener('input', calculateProductivity);
        });

        // Year selection event listeners
        baselineYearSelect.addEventListener('change', updateYearDisplays);
        planYearInput.addEventListener('input', updateYearDisplays);

        // Initialize
        updateYearDisplays();
    });
    </script>
</x-layout>