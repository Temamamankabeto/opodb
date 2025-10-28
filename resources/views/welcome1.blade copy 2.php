<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Crop Planning System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header {
            font-weight: bold;
        }
        .baseline-header {
            background-color: #0d6efd;
            color: white;
        }
        .target-header {
            background-color: #198754;
            color: white;
        }
        .performance-header {
            background-color: #6c757d;
            color: white;
        }
        .calculation-result {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .performance-badge {
            font-size: 0.9rem;
            padding: 0.35em 0.65em;
        }
        .crop-section {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .crop-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .crop-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .toast-container {
            z-index: 1100;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Multi-Crop Planning System</h1>
        
        <form id="multiCropPlanForm" method="POST" action="/yearly-crop-plans">
            <input type="hidden" name="_token" id="csrf_token" value="">
            
            <!-- Basic Information Section -->
            <div class="card mb-4">
                <div class="card-header">Basic Information</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="plan_year" class="form-label required-field">Plan Year</label>
                            <input type="number" class="form-control" id="plan_year" name="plan_year" 
                                   min="2000" max="2030" required>
                        </div>
                        <div class="col-md-6">
                            <label for="production_year" class="form-label required-field">Production Year</label>
                            <select class="form-select" id="production_year" name="production_year" required>
                                <option value="">Select Year</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Crop Sections Container -->
            <div id="cropSectionsContainer">
                <!-- Crop sections will be added here dynamically -->
            </div>
            
            <!-- Add Crop Button -->
            <div class="d-grid mb-4">
                <button type="button" id="addCropBtn" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Add Another Crop
                </button>
            </div>
            
            <!-- Form Actions -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Save All Plans</button>
                <a href="/yearly-crop-plans" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Crop Section Template (hidden) -->
    <template id="cropSectionTemplate">
        <div class="crop-section" data-crop-id="">
            <div class="crop-header">
                <span class="crop-title">Crop Plan</span>
                <button type="button" class="btn btn-danger btn-sm remove-crop">Remove</button>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label required-field">Crop Type</label>
                    <select class="form-select crop-type" required>
                        <option value="">Select Crop Type</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label required-field">Crop</label>
                    <select class="form-select crop" required>
                        <option value="">Select Crop</option>
                    </select>
                </div>
            </div>
            
            <!-- Baseline Data -->
            <div class="card mb-3">
                <div class="card-header baseline-header">Baseline Data (Previous Year Achievements)</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2">Category</th>
                                    <th colspan="3">Seasonal Data</th>
                                    <th rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    <th>Summer</th>
                                    <th>Spring</th>
                                    <th>Autumn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Land Area (ha)</td>
                                    <td><input type="number" step="0.01" class="form-control baseline-land summer" name="baseline_land_summer"></td>
                                    <td><input type="number" step="0.01" class="form-control baseline-land spring" name="baseline_land_spring"></td>
                                    <td><input type="number" step="0.01" class="form-control baseline-land autumn" name="baseline_land_autumn"></td>
                                    <td><input type="number" step="0.01" class="form-control calculation-result baseline-land-total" readonly></td>
                                </tr>
                                <tr>
                                    <td>Production (ton)</td>
                                    <td><input type="number" step="0.01" class="form-control baseline-production summer" name="baseline_production_summer"></td>
                                    <td><input type="number" step="0.01" class="form-control baseline-production spring" name="baseline_production_spring"></td>
                                    <td><input type="number" step="0.01" class="form-control baseline-production autumn" name="baseline_production_autumn"></td>
                                    <td><input type="number" step="0.01" class="form-control calculation-result baseline-production-total" readonly></td>
                                </tr>
                                <tr>
                                    <td>Productivity (ton/ha)</td>
                                    <td><input type="number" step="0.01" class="form-control baseline-productivity summer" readonly></td>
                                    <td><input type="number" step="0.01" class="form-control baseline-productivity spring" readonly></td>
                                    <td><input type="number" step="0.01" class="form-control baseline-productivity autumn" readonly></td>
                                    <td><input type="number" step="0.01" class="form-control calculation-result baseline-productivity-total" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Target Data -->
            <div class="card mb-3">
                <div class="card-header performance-header">Target Data (Current Year Plan)</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2">Category</th>
                                    <th colspan="3">Seasonal Data</th>
                                    <th rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    <th>Summer</th>
                                    <th>Spring</th>
                                    <th>Autumn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Land Area (ha)</td>
                                    <td><input type="number" step="0.01" class="form-control target-land summer" name="target_land_summer"></td>
                                    <td><input type="number" step="0.01" class="form-control target-land spring" name="target_land_spring"></td>
                                    <td><input type="number" step="0.01" class="form-control target-land autumn" name="target_land_autumn"></td>
                                    <td><input type="number" step="0.01" class="form-control calculation-result target-land-total" readonly></td>
                                </tr>
                                <tr>
                                    <td>Production (ton)</td>
                                    <td><input type="number" step="0.01" class="form-control target-production summer" name="target_production_summer"></td>
                                    <td><input type="number" step="0.01" class="form-control target-production spring" name="target_production_spring"></td>
                                    <td><input type="number" step="0.01" class="form-control target-production autumn" name="target_production_autumn"></td>
                                    <td><input type="number" step="0.01" class="form-control calculation-result target-production-total" readonly></td>
                                </tr>
                                <tr>
                                    <td>Productivity (ton/ha)</td>
                                    <td><input type="number" step="0.01" class="form-control target-productivity summer" readonly></td>
                                    <td><input type="number" step="0.01" class="form-control target-productivity spring" readonly></td>
                                    <td><input type="number" step="0.01" class="form-control target-productivity autumn" readonly></td>
                                    <td><input type="number" step="0.01" class="form-control calculation-result target-productivity-total" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Performance Section -->
            <div class="card">
                <div class="card-header performance-header">Performance by Season</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                <span class="fw-bold">Summer:</span>
                                <span class="badge performance-badge summer-performance">0%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                <span class="fw-bold">Spring:</span>
                                <span class="badge performance-badge spring-performance">0%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                <span class="fw-bold">Autumn:</span>
                                <span class="badge performance-badge autumn-performance">0%</span>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="d-flex justify-content-between align-items-center p-2 border rounded bg-light">
                                <span class="fw-bold">Overall:</span>
                                <span class="badge performance-badge overall-performance">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let cropTypes = [];
        let crops = [];
        let productionYears = [];
        let cropCounter = 0;
        
        // DOM elements
        const cropSectionsContainer = document.getElementById('cropSectionsContainer');
        const addCropBtn = document.getElementById('addCropBtn');
        const cropSectionTemplate = document.getElementById('cropSectionTemplate');
        
        // Initialize the form
        document.addEventListener('DOMContentLoaded', async function() {
            // Set CSRF token
            document.getElementById('csrf_token').value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            // Set current year as default
            const currentYear = new Date().getFullYear();
            document.getElementById('plan_year').value = currentYear;
            
            // Load initial data
            await loadInitialData();
            
            // Add first crop section by default
            addCropSection();
            
            // Add event listener for adding new crop sections
            addCropBtn.addEventListener('click', addCropSection);
        });
        
        // Load initial data
        async function loadInitialData() {
            try {
                // Load production years
                const yearsResponse = await fetch('/api/production-years');
                if (!yearsResponse.ok) throw new Error('Failed to fetch production years');
                productionYears = await yearsResponse.json();
                
                // Populate production years dropdown
                const productionYearSelect = document.getElementById('production_year');
                productionYears.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    if (year === new Date().getFullYear()) option.selected = true;
                    productionYearSelect.appendChild(option);
                });
                
                // Load crop types
                const typesResponse = await fetch('/api/crop-types');
                if (!typesResponse.ok) throw new Error('Failed to fetch crop types');
                cropTypes = await typesResponse.json();
                
                // Load all crops
                const cropsResponse = await fetch('/api/crops');
                if (!cropsResponse.ok) throw new Error('Failed to fetch crops');
                crops = await cropsResponse.json();
                
            } catch (error) {
                console.error('Initialization error:', error);
                showToast('Error loading initial data. Please refresh the page.', 'error');
            }
        }
        
        // Add a new crop section
        function addCropSection() {
            cropCounter++;
            const clone = cropSectionTemplate.content.cloneNode(true);
            const cropSection = clone.querySelector('.crop-section');
            cropSection.dataset.cropId = `crop_${cropCounter}`;
            
            // Update title
            cropSection.querySelector('.crop-title').textContent = `Crop Plan #${cropCounter}`;
            
            // Populate crop type dropdown
            const cropTypeSelect = cropSection.querySelector('.crop-type');
            cropTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = type.name;
                cropTypeSelect.appendChild(option);
            });
            
            // Handle crop type change to populate crops
            cropTypeSelect.addEventListener('change', function() {
                const cropSelect = this.closest('.crop-section').querySelector('.crop');
                cropSelect.innerHTML = '<option value="">Select Crop</option>';
                
                const selectedTypeId = this.value;
                if (!selectedTypeId) return;
                
                const filteredCrops = crops.filter(crop => crop.croptype_id == selectedTypeId);
                filteredCrops.forEach(crop => {
                    const option = document.createElement('option');
                    option.value = crop.id;
                    option.textContent = crop.name;
                    cropSelect.appendChild(option);
                });
            });
            
            // Add remove button functionality
            cropSection.querySelector('.remove-crop').addEventListener('click', function() {
                if (cropSectionsContainer.children.length > 1) {
                    this.closest('.crop-section').remove();
                    renumberCropSections();
                } else {
                    showToast('You must have at least one crop section.', 'warning');
                }
            });
            
            // Add input event listeners for calculations
            const numberInputs = cropSection.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                input.addEventListener('input', function() {
                    calculateCropSection(this.closest('.crop-section'));
                });
            });
            
            // Add to container
            cropSectionsContainer.appendChild(clone);
        }
        
        // Renumber crop sections after removal
        function renumberCropSections() {
            const sections = cropSectionsContainer.querySelectorAll('.crop-section');
            sections.forEach((section, index) => {
                section.querySelector('.crop-title').textContent = `Crop Plan #${index + 1}`;
            });
            cropCounter = sections.length;
        }
        
        // Calculate all metrics for a crop section
        function calculateCropSection(section) {
            // Calculate baseline totals and productivity
            const baseLandSummer = parseFloat(section.querySelector('.baseline-land.summer').value) || 0;
            const baseLandSpring = parseFloat(section.querySelector('.baseline-land.spring').value) || 0;
            const baseLandAutumn = parseFloat(section.querySelector('.baseline-land.autumn').value) || 0;
            const baseLandTotal = baseLandSummer + baseLandSpring + baseLandAutumn;
            section.querySelector('.baseline-land-total').value = baseLandTotal.toFixed(2);
            
            const baseProdSummer = parseFloat(section.querySelector('.baseline-production.summer').value) || 0;
            const baseProdSpring = parseFloat(section.querySelector('.baseline-production.spring').value) || 0;
            const baseProdAutumn = parseFloat(section.querySelector('.baseline-production.autumn').value) || 0;
            const baseProdTotal = baseProdSummer + baseProdSpring + baseProdAutumn;
            section.querySelector('.baseline-production-total').value = baseProdTotal.toFixed(2);
            
            // Baseline productivity
            section.querySelector('.baseline-productivity.summer').value = 
                baseLandSummer > 0 ? (baseProdSummer / baseLandSummer).toFixed(2) : '0.00';
            section.querySelector('.baseline-productivity.spring').value = 
                baseLandSpring > 0 ? (baseProdSpring / baseLandSpring).toFixed(2) : '0.00';
            section.querySelector('.baseline-productivity.autumn').value = 
                baseLandAutumn > 0 ? (baseProdAutumn / baseLandAutumn).toFixed(2) : '0.00';
            section.querySelector('.baseline-productivity-total').value = 
                baseLandTotal > 0 ? (baseProdTotal / baseLandTotal).toFixed(2) : '0.00';
            
            // Calculate target totals and productivity
            const targetLandSummer = parseFloat(section.querySelector('.target-land.summer').value) || 0;
            const targetLandSpring = parseFloat(section.querySelector('.target-land.spring').value) || 0;
            const targetLandAutumn = parseFloat(section.querySelector('.target-land.autumn').value) || 0;
            const targetLandTotal = targetLandSummer + targetLandSpring + targetLandAutumn;
            section.querySelector('.target-land-total').value = targetLandTotal.toFixed(2);
            
            const targetProdSummer = parseFloat(section.querySelector('.target-production.summer').value) || 0;
            const targetProdSpring = parseFloat(section.querySelector('.target-production.spring').value) || 0;
            const targetProdAutumn = parseFloat(section.querySelector('.target-production.autumn').value) || 0;
            const targetProdTotal = targetProdSummer + targetProdSpring + targetProdAutumn;
            section.querySelector('.target-production-total').value = targetProdTotal.toFixed(2);
            
            // Target productivity
            section.querySelector('.target-productivity.summer').value = 
                targetLandSummer > 0 ? (targetProdSummer / targetLandSummer).toFixed(2) : '0.00';
            section.querySelector('.target-productivity.spring').value = 
                targetLandSpring > 0 ? (targetProdSpring / targetLandSpring).toFixed(2) : '0.00';
            section.querySelector('.target-productivity.autumn').value = 
                targetLandAutumn > 0 ? (targetProdAutumn / targetLandAutumn).toFixed(2) : '0.00';
            section.querySelector('.target-productivity-total').value = 
                targetLandTotal > 0 ? (targetProdTotal / targetLandTotal).toFixed(2) : '0.00';
            
            // Calculate performance
            calculatePerformance(section, 'summer', baseLandSummer, baseProdSummer, targetLandSummer, targetProdSummer);
            calculatePerformance(section, 'spring', baseLandSpring, baseProdSpring, targetLandSpring, targetProdSpring);
            calculatePerformance(section, 'autumn', baseLandAutumn, baseProdAutumn, targetLandAutumn, targetProdAutumn);
            
            // Calculate overall performance
            const overallPerformance = targetLandTotal > 0 ? (baseLandTotal / targetLandTotal) * 100 : 0;
            updatePerformanceBadge(section.querySelector('.overall-performance'), overallPerformance);
        }
        
        // Calculate performance for a season
        function calculatePerformance(section, season, baseLand, baseProd, targetLand, targetProd) {
            let performance = 0;
            
            if (targetLand > 0 && targetProd > 0) {
                // Calculate performance based on productivity (production per hectare)
                const baseProductivity = baseLand > 0 ? (baseProd / baseLand) : 0;
                const targetProductivity = targetProd / targetLand;
                performance = targetProductivity > 0 ? (baseProductivity / targetProductivity) * 100 : 0;
            }
            
            updatePerformanceBadge(section.querySelector(`.${season}-performance`), performance);
        }
        
        // Update performance badge with color coding
        function updatePerformanceBadge(element, performance) {
            if (!element) return;
            
            element.textContent = `${performance.toFixed(2)}%`;
            
            // Color coding
            if (performance < 80) {
                element.className = 'badge performance-badge bg-danger';
            } else if (performance < 100) {
                element.className = 'badge performance-badge bg-warning text-dark';
            } else {
                element.className = 'badge performance-badge bg-success';
            }
        }
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast show align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
        
        // Handle form submission
        document.getElementById('multiCropPlanForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            
            try {
                // Validate at least one crop section has a selected crop
                const cropSections = this.querySelectorAll('.crop-section');
                let isValid = true;
                
                cropSections.forEach(section => {
                    const cropType = section.querySelector('.crop-type').value;
                    const crop = section.querySelector('.crop').value;
                    
                    if (!cropType || !crop) {
                        isValid = false;
                        section.scrollIntoView({ behavior: 'smooth' });
                        showToast('Please select both crop type and crop for all sections', 'warning');
                    }
                });
                
                if (!isValid) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Save All Plans';
                    return;
                }
                
                // Prepare form data
                const formData = new FormData(this);
                formData.append('crop_count', cropSections.length);
                
                // Add crop-specific data
                cropSections.forEach((section, index) => {
                    const cropType = section.querySelector('.crop-type').value;
                    const crop = section.querySelector('.crop').value;
                    
                    formData.append(`crops[${index}][croptype_id]`, cropType);
                    formData.append(`crops[${index}][crop_id]`, crop);
                    
                    // Add all input values
                    section.querySelectorAll('input').forEach(input => {
                        if (input.name) {
                            const name = input.name.replace('_', ' ');
                            formData.append(`crops[${index}][${name}]`, input.value);
                        }
                    });
                });
                
                // Submit data
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.getElementById('csrf_token').value
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Failed to save data');
                }
                
                showToast('All crop plans saved successfully!');
                setTimeout(() => {
                    window.location.href = '/yearly-crop-plans';
                }, 1500);
            } catch (error) {
                console.error('Submission Error:', error);
                showToast(error.message, 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save All Plans';
            }
        });
    </script>
</body>
</html>