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
        <h2> Effectiveness of students </h2>

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

        <form action="{{url('student-effectiveness/save')}}" method="POST">
            @csrf
            <table border="1" cellspacing="0" cellpadding="5">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Baseline Year (2017 EFY achievement)</th>
                    <th>Plan of 2018 EFY</th>
                </tr>
                </thead>
                <tbody>
                <!-- Grade 1-5 -->
                <tr>
                    <td rowspan="2">Grade 1-5</td>
                    <td>accomplish classes</td>
                    <td><input type="text" name="baseline_grade_1_5_class"></td>
                    <td><input type="text" name="plan_grade_1_5_class"></td>
                </tr>
                <tr>
                    <td>Passed exam</td>
                    <td><input type="text" name="baseline_grade_1_5_exam"></td>
                    <td><input type="text" name="plan_grade_1_5_exam"></td>
                </tr>

                <!-- Grade 6 exam -->
                <tr>
                    <td rowspan="2">Grade 6th exam</td>
                    <td>sit for exam</td>
                    <td><input type="text" name="baseline_grade_6_sit"></td>
                    <td><input type="text" name="plan_grade_6_sit"></td>
                </tr>
                <tr>
                    <td>Passed the exam</td>
                    <td><input type="text" name="baseline_grade_6_pass"></td>
                    <td><input type="text" name="plan_grade_6_pass"></td>
                </tr>

                <!-- Grade 7 -->
                <tr>
                    <td rowspan="2">Grade 7</td>
                    <td>Finish the class</td>
                    <td><input type="text" name="baseline_grade_7_class"></td>
                    <td><input type="text" name="plan_grade_7_class"></td>
                </tr>
                <tr>
                    <td>Pass the exam</td>
                    <td><input type="text" name="baseline_grade_7_exam"></td>
                    <td><input type="text" name="plan_grade_7_exam"></td>
                </tr>

                <!-- Grade 8 -->
                <tr>
                    <td rowspan="2">National exam Grade 8th</td>
                    <td>Take the exam</td>
                    <td><input type="text" name="baseline_grade_8_take"></td>
                    <td><input type="text" name="plan_grade_8_take"></td>
                </tr>
                <tr>
                    <td>Pass the exam</td>
                    <td><input type="text" name="baseline_grade_8_pass"></td>
                    <td><input type="text" name="plan_grade_8_pass"></td>
                </tr>

                <!-- Grade 9-11 -->
                <tr>
                    <td rowspan="2">Grade 9-11</td>
                    <td>finish the class</td>
                    <td><input type="text" name="baseline_grade_9_11_class"></td>
                    <td><input type="text" name="plan_grade_9_11_class"></td>
                </tr>
                <tr>
                    <td>Pass the exam</td>
                    <td><input type="text" name="baseline_grade_9_11_exam"></td>
                    <td><input type="text" name="plan_grade_9_11_exam"></td>
                </tr>

                <!-- Grade 12 -->
                <tr>
                    <td rowspan="2">National Exam grade 12nd</td>
                    <td>Take the exam</td>
                    <td><input type="text" name="baseline_grade_12_take"></td>
                    <td><input type="text" name="plan_grade_12_take"></td>
                </tr>
                <tr>
                    <td>Pass the exam</td>
                    <td><input type="text" name="baseline_grade_12_pass"></td>
                    <td><input type="text" name="plan_grade_12_pass"></td>
                </tr>
                </tbody>
            </table>
            <br>
            <button type="submit">Submit</button>
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
