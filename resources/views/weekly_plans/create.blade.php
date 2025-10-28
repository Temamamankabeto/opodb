<x-layout>
    <h2 class="form-title">Add Weekly Plan</h2>
    
    <form action="{{ route('weekly-plans.store') }}" method="POST" class="form-container">
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
        
        <!-- Crop Type Dropdown -->
        <div class="form-group">
            <label for="crop_type_id"><i class="fas fa-seedling"></i> Crop Type</label>
            <select name="croptype_id" id="crop_type_id" class="form-control" required>
                <option value="">-- Select --</option>
                @foreach($cropTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Plan Year Dropdown -->
        <div class="form-group">
            <label for="plan_year"><i class="fas fa-calendar-alt"></i> Baseline Year (E.C)</label>
            <select id="plan_year" name="plan_year" class="form-control" required>
                <option value="">-- Select plan Year --</option>
                @foreach ($planYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <!-- Crop Dropdown -->
        <div class="form-group">
            <label for="crop_id"><i class="fas fa-leaf"></i> Crop</label>
            <select name="crop_id" id="crop_id" class="form-control" required>
                <option value="">-- Select --</option>
            </select>
        </div>

        <!-- Production Plan Input -->
        <div class="form-group">
            <label for="production_plan_idaama"><i class="fas fa-chart-line"></i> Year Plan</label>
            <input type="number" name="production_plan_idaama" id="production_plan_idaama" readonly class="form-control" required>
        </div>

        <!-- Month Dropdown -->
        <div class="form-group">
            <label for="month"><i class="fas fa-calendar-day"></i> Month</label>
            <select name="month" required class="form-control">
                @foreach($months as $month)
                    <option value="{{ $month }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>

        <!-- Day of Month Input -->
        <div class="form-group">
            <label for="day"><i class="fas fa-calendar-check"></i> Day of Month (1–30)</label>
            <input type="number" name="day" id="day" min="1" max="30" class="form-control" required>
        </div>

        <!-- Planned Value Input -->
        <div class="form-group">
            <label for="planned_value"><i class="fas fa-money-bill-wave"></i> Planned Value</label>
            <input type="number" name="planned_value" id="planned_value" class="form-control">
        </div>
        <input type="hidden" name="kebele_id" id="kebele_id"
        value="{{ Auth::user()->kebele_id ?? '' }}">

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cropTypeSelect = document.getElementById('crop_type_id');
            const cropSelect = document.getElementById('crop_id');
            const yearSelect = document.getElementById('plan_year');
            const planInput = document.getElementById('production_plan_idaama');

            function fetchCrops(typeId) {
                cropSelect.innerHTML = '<option value="">-- Select --</option>';
                fetch(`/crops/by-type/${typeId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(crop => {
                            const option = document.createElement('option');
                            option.value = crop.id;
                            option.textContent = crop.name;
                            cropSelect.appendChild(option);
                        });
                    });
            }

            function fetchPlanValue() {
                const cropId = cropSelect.value;
                const planYear = yearSelect.value;
                if (cropId && planYear) {
                    fetch(`/yearly-crop-plan/value/${cropId}/${planYear}`)
                        .then(response => response.json())
                        .then(data => {
                            planInput.value = data.production_plan_idaama ?? '';
                        });
                } else {
                    planInput.value = '';
                }
            }

            cropTypeSelect.addEventListener('change', function () {
                fetchCrops(this.value);
            });

            cropSelect.addEventListener('change', fetchPlanValue);
            yearSelect.addEventListener('change', fetchPlanValue);
        });
    </script>

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .alert {
            color: #b94a48;
            background-color: #f2dede;
            border-radius: 5px;
            padding: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                margin: 20px;
            }

            button[type="submit"] {
                font-size: 18px;
            }
        }
    </style>
</x-layout>
