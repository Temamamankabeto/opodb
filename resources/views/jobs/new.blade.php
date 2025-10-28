<x-layout>
    <title>Job Opportunity Created</title>
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
        <h2>Job Opportunity Created</h2>

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

        <form id="data-form" method="POST" action="#">
            @csrf
            <input type="hidden" name="baseline_year" id="baseline_year_hidden" value="2017">
            <input type="hidden" name="plan_year" id="plan_year_hidden" value="2018">

            <table><thead>
                <tr>
                    <th>Entrepreneurs</th>
                    <th>Baseline Year (2017 EFY achievement)</th>
                    <th>Plan of 2018 EFY</th>
                </tr>
                </thead>
                <tbody>
                <!-- Entrepreneurs Organized -->
                <tr>
                    <td>Entrepreneurs Organized</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">Baay’ina Intarpiraayizii [Lakk]</td>
                    <td><input type="text" name="organized_baayina" style="width:100%;"></td>
                    <td><input type="text" name="organized_baayina_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">Miseensa [Lak]</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left:40px;">Dhi</td>
                    <td><input type="text" name="organized_dhi" style="width:100%;"></td>
                    <td><input type="text" name="organized_dhi_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding-left:40px;">Duba</td>
                    <td><input type="text" name="organized_duba" style="width:100%;"></td>
                    <td><input type="text" name="organized_duba_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding-left:40px;">Ida’ama</td>
                    <td><input type="text" name="organized_idaama" style="width:100%;"></td>
                    <td><input type="text" name="organized_idaama_plan" style="width:100%;"></td>
                </tr>

                <!-- Entrepreneurs entered into operation e -->
                <tr>
                    <td>Entrepreneurs entered into operation e</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">Baay’ina Intarpiraayizii [Lakk]</td>
                    <td><input type="text" name="entered_baayina" style="width:100%;"></td>
                    <td><input type="text" name="entered_baayina_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">Miseensa [Lak]</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-left:40px;">Dhi</td>
                    <td><input type="text" name="entered_dhi" style="width:100%;"></td>
                    <td><input type="text" name="entered_dhi_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding-left:40px;">Duba</td>
                    <td><input type="text" name="entered_duba" style="width:100%;"></td>
                    <td><input type="text" name="entered_duba_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding-left:40px;">Ida’ama</td>
                    <td><input type="text" name="entered_idaama" style="width:100%;"></td>
                    <td><input type="text" name="entered_idaama_plan" style="width:100%;"></td>
                </tr>

                <!-- Types of jobs participated -->
                <tr>
                    <td>Types of jobs they participated in<br>[Numbers of Entrepreneurs]</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Crop development</td>
                    <td><input type="text" name="crop_development" style="width:100%;"></td>
                    <td><input type="text" name="crop_development_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>Livestock Rearing</td>
                    <td><input type="text" name="livestock" style="width:100%;"></td>
                    <td><input type="text" name="livestock_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>Seedlings preparation and sales</td>
                    <td><input type="text" name="seedlings" style="width:100%;"></td>
                    <td><input type="text" name="seedlings_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>Hand craft and cottage industries</td>
                    <td><input type="text" name="handcraft" style="width:100%;"></td>
                    <td><input type="text" name="handcraft_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>Minerals</td>
                    <td><input type="text" name="minerals" style="width:100%;"></td>
                    <td><input type="text" name="minerals_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>Trade</td>
                    <td><input type="text" name="trade" style="width:100%;"></td>
                    <td><input type="text" name="trade_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>Food and tourism service</td>
                    <td><input type="text" name="food_tourism" style="width:100%;"></td>
                    <td><input type="text" name="food_tourism_plan" style="width:100%;"></td>
                </tr>
                <tr>
                    <td>ICT Service</td>
                    <td><input type="text" name="ict_service" style="width:100%;"></td>
                    <td><input type="text" name="ict_service_plan" style="width:100%;"></td>
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
