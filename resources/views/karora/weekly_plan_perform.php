<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .summary-card {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }

        .summary-card.alt {
            background: linear-gradient(135deg, #ff6a00, #ee0979);
        }

        .summary-card h6 {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .summary-card .value {
            font-size: 1.5rem;
            font-weight: 600;
        }
    </style>

    <div class="container py-4">
        <div class="row g-4 align-items-stretch">
            <!-- 📝 Form Section -->
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="bi bi-calendar-week"></i> Weekly Crop Plan</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row g-3">
                                <!-- Plan Year -->
                                <div class="col-md-4">
                                    <label for="year" class="form-label"><i class="bi bi-calendar3"></i> Year</label>
                                    <select class="form-select" id="plan_year" name="plan_year">
                                        <option selected>Select Year</option>
                                        @foreach ($planYears as $year)
                                        <option value="{{ $year->plan_year }}">{{ $year->plan_year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Crop Type -->
                                <div class="col-md-4">
                                    <label for="cropType" class="form-label"><i class="bi bi-diagram-3"></i> Crop
                                        Type</label>
                                    <select class="form-select" id="crop_type_id" name="crop_type_id">
                                        <option selected>Select Crop Type</option>
                                    </select>
                                </div>

                                <!-- Crop -->
                                <div class="col-md-4">
                                    <label for="crop" class="form-label"><i class="bi bi-leaf"></i> Crop</label>
                                    <select class="form-select" id="crop_id" name="crop_name">
                                        <option selected>Select Crop</option>
                                    </select>
                                </div>

                                <!-- Weekly Plan -->
                                <div class="col-md-6">
                                    <label for="weeklyPlan" class="form-label"><i class="bi bi-journal-plus"></i> Weekly
                                        Plan (ha)</label>
                                    <input type="number" step="0.01" class="form-control" id="weeklyPlan"
                                           placeholder="Enter planned area">
                                </div>

                                <!-- Weekly Achievement -->
                                <div class="col-md-6">
                                    <label for="weeklyAchievement" class="form-label"><i
                                            class="bi bi-check2-square"></i> Weekly Achievement (ha)</label>
                                    <input type="number" step="0.01" class="form-control" id="weeklyAchievement"
                                           placeholder="Enter achieved area">
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- 📊 Summary Section -->
            <div class="col-md-4">
                <div class="summary-card d-flex justify-content-between align-items-center">
                    <div>
                        <h6><i class="bi bi-graph-up"></i> Yearly Plan</h6>
                        <div class="value">1,200 ha</div>
                    </div>
                    <i class="bi bi-calendar3 display-6 opacity-50"></i>
                </div>

                <div class="summary-card alt d-flex justify-content-between align-items-center">
                    <div>
                        <h6><i class="bi bi-bar-chart-steps"></i> Weekly Plan</h6>
                        <div class="value">300 ha</div>
                    </div>
                    <i class="bi bi-calendar-week display-6 opacity-50"></i>
                </div>
            </div>


        </div>
    </div>
    <script>
        document.getElementById('plan_year').addEventListener('change', function () {
            let year = this.value;
            fetch(`/get-crop-types/${year}`)
                .then(response => response.json())
                .then(data => {
                    let cropTypeSelect = document.getElementById('crop_type_id');
                    cropTypeSelect.innerHTML = '<option>Select Crop Type</option>';
                    data.forEach(cropType => {
                        cropTypeSelect.innerHTML += `<option value="${cropType.id}">${cropType.name}</option>`;
                    });
                });
        });

        document.getElementById('crop_type_id').addEventListener('change', function () {
            let year = document.getElementById('plan_year').value;
            let cropTypeId = this.value;

            fetch(`/get-crops/${year}/${cropTypeId}`)
                .then(response => response.json())
                .then(data => {
                    let cropSelect = document.getElementById('crop_id');
                    cropSelect.innerHTML = '<option>Select Crop</option>';
                    data.forEach(crop => {
                        cropSelect.innerHTML += `<option value="${crop.name}">${crop.name}</option>`;
                    });
                });
        });
    </script>

</x-layout>
