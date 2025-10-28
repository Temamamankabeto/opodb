<x-layout>
    <title>New Construction Data Entry</title>
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
        <h2>Health</h2>

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
                <input type="number" id="plan_year" class="form-control" placeholder="e.g., 2018" min="2000" max="2100"
                       value="2018" required>
            </div>
        </div>

        <form method="POST" action="{{ url('health-plans/store') }}">
            @csrf
            <input type="hidden" name="baseline_year" id="baseline_year_hidden" value="2017">
            <input type="hidden" name="plan_year" id="plan_year_hidden" value="2018">

            <table>
                <thead>
                <tr>
                    <th>Activity</th>
                    <th>Baseline Year (<span id="baseline_year_display">2017</span> EFY achievement)</th>
                    <th>Plan of <span id="plan_year_display">2018</span> EFY</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Pregnant mothers followed by health centers [Number]</td>
                    <td><input type="number" name="pregnant_followed_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="pregnant_followed_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Mothers who delivered in health institutions [Number]</td>
                    <td><input type="number" name="institution_delivery_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="institution_delivery_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Mothers who received postpartum health follow-up at health institutions [Number]</td>
                    <td><input type="number" name="postpartum_followup_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="postpartum_followup_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Pregnant and lactating mothers from low-income backgrounds provided with balanced food [Number]</td>
                    <td><input type="number" name="balanced_food_support_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="balanced_food_support_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Newborns receiving consistent vaccination follow-ups [Number]</td>
                    <td><input type="number" name="newborn_vaccination_followup_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="newborn_vaccination_followup_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Children under the age of five who received campaign vaccinations [Number]</td>
                    <td><input type="number" name="under5_campaign_vaccine_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="under5_campaign_vaccine_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Children under the age of five affected by diarrhea [Number]</td>
                    <td><input type="number" name="under5_diarrhea_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="under5_diarrhea_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Malaria vector breeding sites cleaned [Square meters]</td>
                    <td><input type="number" name="malaria_site_cleaned_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="malaria_site_cleaned_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Mothers and young women receiving family planning services [Number]</td>
                    <td><input type="number" name="family_planning_service_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="family_planning_service_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Spouses registered under community health insurance</td>
                    <td><input type="number" name="community_insurance_spouses_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="community_insurance_spouses_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Community health insurance contributions collected</td>
                    <td><input type="number" name="community_insurance_contribution_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="community_insurance_contribution_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Child development centers established within health institutions providing services [Number]</td>
                    <td><input type="number" name="child_development_centers_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="child_development_centers_plan" placeholder="Plan"></td>
                </tr>

                <tr>
                    <td>Expenditure related to Early Childhood Development (ECD) [ETB]</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Food for low-income mothers</td>
                    <td><input type="number" name="ecd_food_low_income_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="ecd_food_low_income_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>For child development centers</td>
                    <td><input type="number" name="ecd_centers_baseline" placeholder="Baseline"></td>
                    <td><input type="number" name="ecd_centers_plan" placeholder="Plan"></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><input type="text" name="idaama_baseline" placeholder="Baseline"></td>
                    <td><input type="text" name="idaama_plan" placeholder="Plan"></td>
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
        document.addEventListener('DOMContentLoaded', function () {
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
