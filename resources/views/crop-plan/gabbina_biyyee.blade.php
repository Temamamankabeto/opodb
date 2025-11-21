<x-layout>
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🌿 Hojii Gabbina Biyyoo</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>maaloo!</strong> Rakkoo Armaan Gadii Fyraa:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('sector.gabbina_biyyee.store') }}" method="POST" id="compostForm">
                    @csrf
                    @auth
                        <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                        <input type="hidden" name="kebele_id" value="{{ Auth::user()->kebele_id }}">
                        <input type="hidden" name="woreda_id" value="{{ Auth::user()->woreda_id }}">
                        <input type="hidden" name="zone_id" value="{{ Auth::user()->zone_id }}">
                        <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                    @endauth

                    <div class="mb-3">
                        <label for="year" class="form-label fw-bold">📅 Bara</label>
                        <select name="year" class="form-select border-start-0 ps-0" required id="yearSelect">
                            <option value="">-- Select Year --</option>
                            @php
                                $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                            @endphp
                            @foreach ($years as $year)
                                <option value="{{ $year }}"
                                    {{ old('year') == $year ? 'selected' : '' }}>
                                    {{ $year }} EC
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Compost Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">🧪 Gosa Kompostii Filadhu</label>
                        <div class="row" id="compostTypeSelection">
                            @foreach($compostTypes as $compostType)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input compost-type-checkbox" 
                                           type="checkbox" 
                                           value="{{ $compostType->name }}" 
                                           id="compost_{{ $compostType->id }}"
                                           data-unit="{{ $compostType->unit ?? 'Qt' }}">
                                    <label class="form-check-label" for="compost_{{ $compostType->id }}">
                                        {{ $compostType->name }} 
                                        <small class="text-muted">({{ $compostType->unit ?? 'Qt' }})</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Data Entry Table -->
                    <div class="table-responsive" id="dataEntrySection" style="display: none;">
                        <table class="table table-bordered align-middle text-center" id="entry-table">
                            <thead class="table-light">
                                <tr>
                                    <th>🧪 Gosa Kompostii</th>
                                    <th>👥 Kan Woloo</th>
                                    <th>👤 Kan Dhuunfa</th>
                                    <th>🧮 Ida'ama</th>
                                    <th>🌱 Bal'ina Lafaa (ha)</th>
                                </tr>
                            </thead>
                            <tbody id="entry-body">
                                <!-- Rows will be dynamically added here based on selected compost types -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" style="display: none;">✅ Karoora Koompostii Galchi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <!-- Dynamic Form Script -->
    <script>
        let selectedCompostTypes = new Set();
        let compostTypeUnits = {};

        // Initialize compost type units from checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.compost-type-checkbox').forEach(checkbox => {
                compostTypeUnits[checkbox.value] = checkbox.dataset.unit || 'Qt';
            });
        });

        // Function to calculate total
        function calculateTotal(row) {
            const common = parseFloat(row.querySelector('.common')?.value || 0);
            const individual = parseFloat(row.querySelector('.individual')?.value || 0);
            const totalInput = row.querySelector('.total');
            totalInput.value = (common + individual).toFixed(2);
        }

        // Function to create row for a compost type
        function createRow(compostType) {
            const rowIndex = selectedCompostTypes.size - 1;
            const row = document.createElement('tr');
            row.className = 'compost-row';
            row.dataset.compostType = compostType;
            
            const unit = compostTypeUnits[compostType] || 'Qt';
            
            row.innerHTML = `
                <td>
                    <strong>${compostType}</strong>
                    <div class="unit-badge">${unit}</div>
                    <input type="hidden" name="entries[${rowIndex}][compost_type]" value="${compostType}">
                    <input type="hidden" name="entries[${rowIndex}][unit]" value="${unit}">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][common]" 
                               class="form-control common" 
                               step="0.01" 
                               min="0" 
                               required 
                               placeholder="0.00">
                        <span class="input-group-text unit-display">${unit}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][individual]" 
                               class="form-control individual" 
                               step="0.01" 
                               min="0" 
                               required 
                               placeholder="0.00">
                        <span class="input-group-text unit-display">${unit}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][total]" 
                               class="form-control total" 
                               step="0.01" 
                               readonly 
                               placeholder="0.00">
                        <span class="input-group-text unit-display">${unit}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][land_area]" 
                               class="form-control land-area" 
                               step="0.01" 
                               min="0" 
                               placeholder="0.00">
                        <span class="input-group-text">ha</span>
                    </div>
                </td>
            `;
            
            // Set up event listeners for the new row
            const commonInput = row.querySelector('.common');
            const individualInput = row.querySelector('.individual');
            
            commonInput.addEventListener('input', () => calculateTotal(row));
            individualInput.addEventListener('input', () => calculateTotal(row));
            
            return row;
        }

        // Function to update the form based on selected compost types
        function updateForm() {
            const entryBody = document.getElementById('entry-body');
            const dataEntrySection = document.getElementById('dataEntrySection');
            const submitBtn = document.getElementById('submitBtn');
            
            // Clear existing rows
            entryBody.innerHTML = '';
            
            if (selectedCompostTypes.size > 0) {
                // Show data entry section and submit button
                dataEntrySection.style.display = '';
                submitBtn.style.display = '';
                
                // Add rows for each selected compost type
                selectedCompostTypes.forEach(compostType => {
                    const row = createRow(compostType);
                    entryBody.appendChild(row);
                });
            } else {
                // Hide data entry section and submit button
                dataEntrySection.style.display = 'none';
                submitBtn.style.display = 'none';
            }
        }

        // Event listener for compost type checkboxes
        document.getElementById('compostTypeSelection').addEventListener('change', function(e) {
            if (e.target.classList.contains('compost-type-checkbox')) {
                const compostType = e.target.value;
                
                if (e.target.checked) {
                    selectedCompostTypes.add(compostType);
                } else {
                    selectedCompostTypes.delete(compostType);
                }
                
                updateForm();
            }
        });

        // Form validation before submission
        document.getElementById('compostForm').addEventListener('submit', function(e) {
            const yearSelect = document.getElementById('yearSelect');
            if (!yearSelect.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please select a year.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            if (selectedCompostTypes.size === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please select at least one compost type.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Validate that all required fields are filled
            const requiredInputs = document.querySelectorAll('input[required]');
            let isValid = true;
            let errorMessage = '';
            
            requiredInputs.forEach(input => {
                if (!input.value || parseFloat(input.value) < 0) {
                    isValid = false;
                    const compostType = input.closest('tr').dataset.compostType;
                    errorMessage = `Please fill all required fields for ${compostType}`;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage || 'Please fill all required fields.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Auto-calculate totals when page loads (if there are any existing values)
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any existing rows
            document.querySelectorAll('.compost-row').forEach(row => {
                calculateTotal(row);
            });
        });
    </script>

    <style>
        .compost-type-checkbox {
            transform: scale(1.2);
            margin-right: 8px;
        }
        
        .form-check-label {
            font-weight: 500;
            cursor: pointer;
        }
        
        .compost-row td {
            vertical-align: middle;
        }
        
        .compost-row td:first-child {
            font-weight: 600;
            color: #2c5aa0;
            position: relative;
        }
        
        .unit-badge {
            position: absolute;
            top: -5px;
            right: 5px;
            background: #e9ecef;
            color: #6c757d;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        input.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        #compostTypeSelection {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .land-area {
            background-color: #f8f9fa;
        }
        
        .input-group-sm .input-group-text {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .unit-display {
            background-color: #e9ecef;
            color: #495057;
            font-weight: 500;
            min-width: 45px;
        }
        
        .form-check-label small {
            font-size: 0.75rem;
        }
    </style>
</x-layout>