<x-layout>
    <title>Rural Kebele Government Asset</title>
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
        <h2>Rural Kebele Government Asset</h2>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="year-selection">



        </div>

        <form id="data-form" method="POST" action="{{ url('rural-government-asset-plans/save') }}">
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
            <div>
                <label for="plan_year">Enter Plan Year</label>
                <input type="number" id="plan_year" name="plan_year" class="form-control" placeholder="e.g., 2018" min="2000" max="2100"
                       value="2018" required>
            </div>
            <table>
                <thead>
                <tr>
                    <th>Government Revenue</th>
                    <th>Revenue collected [ETB]</th>
                    <th>Plan of 2018 EFY</th>
                </tr>
                </thead>
                <tbody>

                <!-- Buusaa Gonofaa Section -->
                 <tr>
                  <td>Galii Mootummaa</td>
                    <td><input type="number" name="bg_members_plan"></td>
</tr>
                <tr>
                    <td rowspan="6" class="gray">Buusaa Gonofaa</td>
                   
                    <td>Members [Number]</td>
                    <td><input type="number" name="bg_members_plan"></td>
                </tr>
                
                <tr>
                    <td>Membership contributions collected [ETB]</td>
                    <td><input type="number" name="bg_contributions_plan"></td>
                </tr>
                <tr>
                    <td>Buusaa Gonofaa contribution crop production [quintal]</td>
                    <td><input type="number" name="bg_crop_quintal_plan"></td>
                </tr>
                <tr>
                    <td>In monetary value [ETB]</td>
                    <td><input type="number" name="bg_monetary_value_plan"></td>
                </tr>
                <tr>
                    <td>Cash Money [ETB]</td>
                    <td><input type="number" name="bg_cash_plan"></td>
                </tr>
                <tr>
                    <td>Ida’ama [Qarshii]</td>
                    <td><input type="text" name="bg_idaama_plan"></td>
                </tr>

                <!-- Gaachena Sirnaa Section -->
                <tr>
                    <td rowspan="2" class="gray">Gaachena Sirnaa</td>
                    <td>Trained personnel [Numbers]</td>
                    <td><input type="number" name="gs_trained_plan"></td>
                </tr>
                <tr>
                    <td>Numbers of people assigned daily [Numbers]</td>
                    <td><input type="number" name="gs_assigned_daily_plan"></td>
                </tr>

                <!-- Citizenship service -->
                <tr>
                    <td colspan="2" class="yellow">Citizenship service activities [ETB]</td>
                    <td><input type="number" name="citizenship_service_plan"></td>
                </tr>

                <!-- Mobilized Family Prosperity -->
                <tr>
                    <td colspan="2" class="green">Mobilized Family Prosperity [ETB]</td>
                    <td><input type="number" name="family_prosperity_plan"></td>
                </tr>

                <!-- Loan Section -->
                <tr>
                    <td rowspan="3" class="gray">Loan taken [ETB]</td>
                    <td>Not paid</td>
                    <td><input type="number" name="loan_not_paid_plan"></td>
                </tr>
                <tr>
                    <td>Credit taken this year</td>
                    <td><input type="number" name="loan_taken_this_year_plan"></td>
                </tr>
                <tr>
                    <td>Ida’ama</td>
                    <td><input type="text" name="loan_idaama_plan"></td>
                </tr>

                <!-- Credit/Loan Paid -->
                <tr>
                    <td rowspan="3" class="gray">Credit/loan paid this year [ETB]</td>
                    <td>Singe Bank Saving [ETB]</td>
                    <td><input type="number" name="loan_paid_saving_plan"></td>
                </tr>
                <tr>
                    <td>Family Cooperative</td>
                    <td><input type="number" name="loan_paid_coop_plan"></td>
                </tr>
                <tr>
                    <td>Ida’ama</td>
                    <td><input type="text" name="loan_paid_idaama_plan"></td>
                </tr>
                </tbody>
            </table>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit Data</button>
                <button type="reset" class="reset-btn">Reset Form</button>
            </div>
        </form>
    </div>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session("success") }}',
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
            });
        </script>
    @endif
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
