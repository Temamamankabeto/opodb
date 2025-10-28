<x-layout>
    <title>Credit Data Entry</title>
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
        <h2>Liqii fi Qusannaa Mekaanayizeeshiniin walqabate</h2>
        
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

        <form id="data-form" method="POST" action="{{ route('credit_data.store') }}">
            @csrf
            <input type="hidden" name="baseline_year" id="baseline_year_hidden">
            <input type="hidden" name="plan_year" id="plan_year_hidden">
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 30%;">Credit type</th>
                        <th style="width: 20%;">Owner</th>
                        <th style="width: 25%;" id="baseline_year_header">Baseline Year</th>
                        <th style="width: 25%;" id="plan_year_header">Plan Year</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Total debt -->
                    <tr class="category-row">
                        <td>Total debt</td>
                        <td>private</td>
                        <td><input type="number" name="total_debt_baseline_private" step="0.01"></td>
                        <td><input type="number" name="total_debt_plan_private" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>association</td>
                        <td><input type="number" name="total_debt_baseline_association" step="0.01"></td>
                        <td><input type="number" name="total_debt_plan_association" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>lda'ama</td>
                        <td><input type="number" class="total-debt-baseline" name="total_debt_baseline_total" readonly></td>
                        <td><input type="number" class="total-debt-plan" name="total_debt_plan_total" readonly></td>
                    </tr>

                    <!-- Loans repaid -->
                    <tr class="category-row">
                        <td>Loans repaid</td>
                        <td>private</td>
                        <td><input type="number" name="loans_repaid_baseline_private" step="0.01"></td>
                        <td><input type="number" name="loans_repaid_plan_private" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>association</td>
                        <td><input type="number" name="loans_repaid_baseline_association" step="0.01"></td>
                        <td><input type="number" name="loans_repaid_plan_association" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>lda'ama</td>
                        <td><input type="number" class="loans-repaid-baseline" name="loans_repaid_baseline_total" readonly></td>
                        <td><input type="number" class="loans-repaid-plan" name="loans_repaid_plan_total" readonly></td>
                    </tr>

                    <!-- Remaining debt -->
                    <tr class="category-row">
                        <td>The remaining debt</td>
                        <td>private</td>
                        <td><input type="number" name="remaining_debt_baseline_private" step="0.01"></td>
                        <td><input type="number" name="remaining_debt_plan_private" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>association</td>
                        <td><input type="number" name="remaining_debt_baseline_association" step="0.01"></td>
                        <td><input type="number" name="remaining_debt_plan_association" step="0.01"></td>
                    </tr>

                    <!-- Savings made -->
                    <tr class="category-row">
                        <td>Savings made for mechanization</td>
                        <td>association</td>
                        <td><input type="number" name="savings_baseline_association" step="0.01"></td>
                        <td><input type="number" name="savings_plan_association" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>private</td>
                        <td><input type="number" name="savings_baseline_private" step="0.01"></td>
                        <td><input type="number" name="savings_plan_private" step="0.01"></td>
                    </tr>
                    <tr class="subcategory-row">
                        <td></td>
                        <td>lda'ama</td>
                        <td><input type="number" class="savings-baseline" name="savings_baseline_total" readonly></td>
                        <td><input type="number" class="savings-plan" name="savings_plan_total" readonly></td>
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
            // Dynamic update of year headers and hidden fields
            const baselineYearSelect = document.getElementById('baseline_year');
            const planYearInput = document.getElementById('plan_year');
            const baselineYearHeader = document.getElementById('baseline_year_header');
            const planYearHeader = document.getElementById('plan_year_header');
            const baselineYearHidden = document.getElementById('baseline_year_hidden');
            const planYearHidden = document.getElementById('plan_year_hidden');

            function updateYearHeaders() {
                const baselineYear = baselineYearSelect.value;
                const planYear = planYearInput.value;

                baselineYearHeader.textContent = baselineYear ? `Baseline Year ${baselineYear}` : 'Baseline Year';
                planYearHeader.textContent = planYear ? `Plan Year ${planYear}` : 'Plan Year';

                baselineYearHidden.value = baselineYear;
                planYearHidden.value = planYear;
            }

            baselineYearSelect.addEventListener('change', updateYearHeaders);
            planYearInput.addEventListener('input', updateYearHeaders);

            // Calculation of totals
            function calculateTotals() {
                const totalDebtPrivate = parseFloat(document.querySelector('[name="total_debt_baseline_private"]').value) || 0;
                const totalDebtAssociation = parseFloat(document.querySelector('[name="total_debt_baseline_association"]').value) || 0;
                document.querySelector('.total-debt-baseline').value = (totalDebtPrivate + totalDebtAssociation).toFixed(2);

                const totalDebtPlanPrivate = parseFloat(document.querySelector('[name="total_debt_plan_private"]').value) || 0;
                const totalDebtPlanAssociation = parseFloat(document.querySelector('[name="total_debt_plan_association"]').value) || 0;
                document.querySelector('.total-debt-plan').value = (totalDebtPlanPrivate + totalDebtPlanAssociation).toFixed(2);

                const loansRepaidPrivate = parseFloat(document.querySelector('[name="loans_repaid_baseline_private"]').value) || 0;
                const loansRepaidAssociation = parseFloat(document.querySelector('[name="loans_repaid_baseline_association"]').value) || 0;
                document.querySelector('.loans-repaid-baseline').value = (loansRepaidPrivate + loansRepaidAssociation).toFixed(2);

                const loansRepaidPlanPrivate = parseFloat(document.querySelector('[name="loans_repaid_plan_private"]').value) || 0;
                const loansRepaidPlanAssociation = parseFloat(document.querySelector('[name="loans_repaid_plan_association"]').value) || 0;
                document.querySelector('.loans-repaid-plan').value = (loansRepaidPlanPrivate + loansRepaidPlanAssociation).toFixed(2);

                const savingsAssociation = parseFloat(document.querySelector('[name="savings_baseline_association"]').value) || 0;
                const savingsPrivate = parseFloat(document.querySelector('[name="savings_baseline_private"]').value) || 0;
                document.querySelector('.savings-baseline').value = (savingsAssociation + savingsPrivate).toFixed(2);

                const savingsPlanAssociation = parseFloat(document.querySelector('[name="savings_plan_association"]').value) || 0;
                const savingsPlanPrivate = parseFloat(document.querySelector('[name="savings_plan_private"]').value) || 0;
                document.querySelector('.savings-plan').value = (savingsPlanAssociation + savingsPlanPrivate).toFixed(2);
            }

            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('input', calculateTotals);
            });

            calculateTotals();
        });
    </script>
</x-layout>