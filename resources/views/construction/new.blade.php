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
        <h2> Hirmaannaa fi Itti Fufiinsa</h2>

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

        <form action="{{ route('construction.store') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2">Tajaajila / Ijaarsa</th>
                        <th colspan="2">Raawwii Bara 2017</th>
                        <th colspan="2">Karoora Bara 2018</th>
                    </tr>
                    <tr>
                        <th>Baay'ina</th>
                        <th>Baasii (Qarshii)</th>
                        <th>Baay'ina</th>
                        <th>Baasii (Qarshii)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $services = [
                            'Mana Barumsa Bu\'uura Boruu',
                            'Mana Barumsa Sad. 1ffaa',
                            'Mana Jireenya Barsiisotaa',
                            'Keellaa Fayyaa Dhaloota 2ffaa',
                            'Mana Qoricha Hawaasaa',
                            'Wiirtuu Leenjii Fooyya\'insa',
                            'Ogummaa Qonnaan/ Horsiisee Bulaa:FTC',
                            'Waajjira Bulchiinsa Gandaa',
                            'Mana Jireenya Hooggansa Gandaa',
                            'Mana Murtii Aadaa',
                            'Wirtuu Dagaagina Aadaa'
                        ];
                    @endphp

                    @foreach($services as $index => $service)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $service }}</td>

                            <!-- 2017 performance -->
                            <td>
                                <input type="number" name="data[{{ $index }}][2017][count]" class="form-control" min="0" placeholder="Baay'ina">
                            </td>
                            <td>
                                <input type="number" name="data[{{ $index }}][2017][cost]" class="form-control" min="0" placeholder="Baasii (Q)">
                            </td>

                            <!-- 2018 plan -->
                            <td>
                                <input type="number" name="data[{{ $index }}][2018][count]" class="form-control" min="0" placeholder="Baay'ina">
                            </td>
                            <td>
                                <input type="number" name="data[{{ $index }}][2018][cost]" class="form-control" min="0" placeholder="Baasii (Q)">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
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
