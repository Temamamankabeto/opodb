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
        <h2> Participation and continuity</h2>

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('infrastructure.store') }}" method="POST">
            @csrf

            <div class="year-selection">
                <div>
                    <label for="baseline_year">Select Baseline Year</label>
                    <select id="baseline_year" name="baseline_year" required>
                        <option value="">-- Select Baseline Year --</option>
                        @foreach ($planYears as $year)
                            <option value="{{ $year }}" {{ $year == 2017 ? 'selected' : '' }}>{{ $year }} EFY</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="plan_year">Enter Plan Year</label>
                    <input type="number" id="plan_year" name="plan_year" placeholder="e.g., 2018" min="2000" max="2100" value="2018" required>
                </div>
            </div>

            {{-- Add these selects or inputs for zone, woreda, kebele, sector --}}

            <div class="year-selection" style="margin-top: 15px;">
                <div>
                    <label for="zone">Zone</label>
                    <input type="text" id="zone" name="zone" value="{{ old('zone') }}" required>
                </div>
                <div>
                    <label for="woreda">Woreda</label>
                    <input type="text" id="woreda" name="woreda" value="{{ old('woreda') }}" required>
                </div>
                <div>
                    <label for="kebele">Kebele</label>
                    <input type="text" id="kebele" name="kebele" value="{{ old('kebele') }}" required>
                </div>
                <div>
                    <label for="sector">Sector</label>
                    <input type="text" id="sector" name="sector" value="{{ old('sector') }}" required>
                </div>
            </div>

            <div class="table-responsive" style="margin-top: 20px;">
                <table class="table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2" class="text-center">Fooya & Haaromsa Waliigalaa</th>
                        <th rowspan="2">Service Name</th>
                        <th colspan="2" class="text-center">2017</th>
                        <th colspan="2" class="text-center">2018</th>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Actual</th>
                        <th>Cost</th>
                        <th>Plan</th>
                        <th>Cost</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $categories = [
                            ['Fooyya’insa dhaabbilee', 'Haaromsa Waliigalaa'],
                            ['Fooyya’insa dhaabbilee', 'Daree/Bilookii babal\'isu'],
                            ['Baasii fooyya’insa dhaabbilee [Qarshii]', 'Haaromsa Waliigalaa'],
                            ['Baasii fooyya’insa dhaabbilee [Qarshii]', 'Daree/Bilookii babal\'isu'],
                            ['baasifooya', 'Meeshaa keessoo guutuu'],
                        ];
                        $services = [
                            'Mana Barumsa Bu’uura Boruu',
                            'Mana Barumsa Sad. 1ffaa',
                            'Buufata Fayyaa',
                            'Dhaabbata Fayyaa Beyladaa'
                        ];
                        $rowspan = count($services);
                    @endphp

                    @foreach ($categories as [$cat, $sub])
                        @foreach ($services as $index => $service)
                            <tr>
                                @if($index === 0)
                                    <td rowspan="{{ $rowspan }}">{{ ucfirst($cat) }}</td>
                                    <td rowspan="{{ $rowspan }}">{{ $sub }}</td>
                                @endif
                                <td>{{ $service }}</td>

                                <td>
                                    <input type="number" min="0" name="data[{{ $cat }}][{{ $sub }}][{{ $service }}][2017][actual]" value="{{ old("data.$cat.$sub.$service.2017.actual") }}" class="form-control">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" name="data[{{ $cat }}][{{ $sub }}][{{ $service }}][2017][cost]" value="{{ old("data.$cat.$sub.$service.2017.cost") }}" class="form-control">
                                </td>
                                <td>
                                    <input type="number" min="0" name="data[{{ $cat }}][{{ $sub }}][{{ $service }}][2018][plan]" value="{{ old("data.$cat.$sub.$service.2018.plan") }}" class="form-control">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" name="data[{{ $cat }}][{{ $sub }}][{{ $service }}][2018][cost]" value="{{ old("data.$cat.$sub.$service.2018.cost") }}" class="form-control">
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-btn">Save</button>
                <button type="reset" class="reset-btn">Reset</button>
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
