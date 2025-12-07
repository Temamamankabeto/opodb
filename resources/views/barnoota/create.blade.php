<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Barnoota Report</h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('sector.barnoota.store') }}" method="POST" id="barnootaForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Basic Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="year">Year *</label>
                                                <select name="year" id="year" class="form-control" required>
                                                    <option value="">Select Year</option>
                                                    @foreach($years as $value)
                                                        <option value="{{ $value }}" {{ old('year') == $value ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('year')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                             
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hirmaannaa Itti Fufiinsa Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Hirmaannaa fi Ittifufiinsa *</h3>
                                    <small class="float-right">Total Items: <span id="hirmaannaaCount">0</span></small>
                                </div>
                                <div class="card-body">
                                    @if($hirmaannaaItems->isEmpty())
                                        <div class="alert alert-warning">
                                            <i class="icon fas fa-exclamation-triangle"></i>
                                            No educational participation items found. Please add items first.
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="hirmaannaaTable">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Item Name</th>
                                                        <th width="20%">Student Type</th>
                                                        <th width="30%">Amount (Lakk)</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="hirmaannaaBody">
                                                    @foreach($hirmaannaaItems as $index => $item)
                                                    <tr>
                                                        <td>
                                                            {{ $item->name }}
                                                            <input type="hidden" name="hirmaannaa[{{ $index }}][id]" value="{{ $item->id }}">
                                                        </td>
                                                        <td>
                                                            <select name="hirmaannaa[{{ $index }}][student_type]" class="form-control" required>
                                                                <option value="">Select Student Type</option>
                                                                @foreach($studentTypes as $type)
                                                                    <option value="{{ $type }}" {{ old('hirmaannaa.'.$index.'.student_type', $item->student_type) == $type ? 'selected' : '' }}>
                                                                        {{ ucfirst($type) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="number" 
                                                                       name="hirmaannaa[{{ $index }}][amount]" 
                                                                       class="form-control" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       required
                                                                       value="{{ old('hirmaannaa.'.$index.'.amount', '0') }}"
                                                                       placeholder="Enter amount">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Lakk</span>
                                                                </div>
                                                            </div>
                                                            @error('hirmaannaa.'.$index.'.amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-row" data-table="hirmaannaa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bu'aa Qabeessummaa Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Bu'aa Qabeessummaa Barnootaa *</h3>
                                    <small class="float-right">Total Items: <span id="buaaCount">0</span></small>
                                </div>
                                <div class="card-body">
                                    @if($buaaQabeessummaaItems->isEmpty())
                                        <div class="alert alert-warning">
                                            <i class="icon fas fa-exclamation-triangle"></i>
                                            No educational participation items found. Please add items first.
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="buaaTable">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Item Name</th>
                                                        <th width="20%">Student Type</th>
                                                        <th width="30%">Amount (Lakk)</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="buaaBody">
                                                    @foreach($buaaQabeessummaaItems as $index => $item)
                                                    <tr>
                                                        <td>
                                                            {{ $item->name }}
                                                            <input type="hidden" name="buaa_qabeessummaa[{{ $index }}][id]" value="{{ $item->id }}">
                                                        </td>
                                                        <td>
                                                            <select name="buaa_qabeessummaa[{{ $index }}][student_type]" class="form-control" required>
                                                                <option value="">Select Student Type</option>
                                                                @foreach($studentTypes as $type)
                                                                    <option value="{{ $type }}" {{ old('buaa_qabeessummaa.'.$index.'.student_type', $item->student_type) == $type ? 'selected' : '' }}>
                                                                        {{ ucfirst($type) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="number" 
                                                                       name="buaa_qabeessummaa[{{ $index }}][amount]" 
                                                                       class="form-control" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       required
                                                                       value="{{ old('buaa_qabeessummaa.'.$index.'.amount', '0') }}"
                                                                       placeholder="Enter amount">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Lakk</span>
                                                                </div>
                                                            </div>
                                                            @error('buaa_qabeessummaa.'.$index.'.amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-row" data-table="buaa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dhiyeesii Nyaata Barattootaa Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Dhiyeesii Nyaata Barattootaa *</h3>
                                    <small class="float-right">Total Items: <span id="nyaataCount">1</span></small>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="nyaataTable">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Amount (Kunt)</th>
                                                    <th width="25%">Source</th>
                                                    <th width="25%">Cost (Birr)</th>
                                                    <th width="25%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="nyaataBody">
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" 
                                                                   name="dhiyeesii_nyaata[0][amount]" 
                                                                   class="form-control" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   value="{{ old('dhiyeesii_nyaata.0.amount', '0') }}"
                                                                   placeholder="Enter amount" 
                                                                   required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kunt</span>
                                                            </div>
                                                        </div>
                                                        @error('dhiyeesii_nyaata.0.amount')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <select name="dhiyeesii_nyaata[0][source]" class="form-control" required>
                                                            <option value="">Select Source</option>
                                                            <option value="Kan manni barumsaa oomishe" {{ old('dhiyeesii_nyaata.0.source') == 'Kan manni barumsaa oomishe' ? 'selected' : '' }}>
                                                                Kan manni barumsaa oomishe
                                                            </option>
                                                            <option value="Kan hawaasarraa sassaabame" {{ old('dhiyeesii_nyaata.0.source') == 'Kan hawaasarraa sassaabame' ? 'selected' : '' }}>
                                                                Kan hawaasarraa sassaabame
                                                            </option>
                                                        </select>
                                                        @error('dhiyeesii_nyaata.0.source')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" 
                                                                   name="dhiyeesii_nyaata[0][cost]" 
                                                                   class="form-control" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   value="{{ old('dhiyeesii_nyaata.0.cost', '0') }}"
                                                                   placeholder="Enter cost" 
                                                                   required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Birr</span>
                                                            </div>
                                                        </div>
                                                        @error('dhiyeesii_nyaata.0.cost')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row" data-table="nyaata" disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2" id="addNyaataRow">
                                        <i class="fas fa-plus"></i> Add More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Barnoota
                                    </button>
                                    <a href="{{ route('sector.barnoota.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

   
    <script>
        // Global variables
        let nyaataRowCount = 1;
        
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing scripts');
            
            // Initialize counts
            updateCounts();
            
            // Add event listener for Add More button
            const addButton = document.getElementById('addNyaataRow');
            if (addButton) {
                addButton.addEventListener('click', addNyaataRow);
                console.log('Add button event listener attached');
            } else {
                console.error('Add button not found!');
            }
            
            // Add event listener for remove buttons using event delegation
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const button = e.target.closest('.remove-row');
                    const tableType = button.getAttribute('data-table');
                    removeRow(button, tableType);
                }
            });
            
            // Add form validation
            const form = document.getElementById('barnootaForm');
            if (form) {
                form.addEventListener('submit', validateForm);
            }
        });
        
        // Function to add new Nyaata row
        function addNyaataRow() {
            console.log('Adding new Nyaata row');
            
            const tbody = document.getElementById('nyaataBody');
            if (!tbody) {
                console.error('Nyaata body not found');
                return;
            }
            
            // Create new row
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <div class="input-group">
                        <input type="number" 
                               name="dhiyeesii_nyaata[${nyaataRowCount}][amount]" 
                               class="form-control" 
                               step="0.01" 
                               min="0" 
                               value="0"
                               placeholder="Enter amount" 
                               required>
                        <div class="input-group-append">
                            <span class="input-group-text">Kunt</span>
                        </div>
                    </div>
                </td>
                <td>
                    <select name="dhiyeesii_nyaata[${nyaataRowCount}][source]" class="form-control" required>
                        <option value="">Select Source</option>
                        <option value="Kan manni barumsaa oomishe">Kan manni barumsaa oomishe</option>
                        <option value="Kan hawaasarraa sassaabame">Kan hawaasarraa sassaabame</option>
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" 
                               name="dhiyeesii_nyaata[${nyaataRowCount}][cost]" 
                               class="form-control" 
                               step="0.01" 
                               min="0" 
                               value="0"
                               placeholder="Enter cost" 
                               required>
                        <div class="input-group-append">
                            <span class="input-group-text">Birr</span>
                        </div>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row" data-table="nyaata">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            // Append the new row
            tbody.appendChild(newRow);
            nyaataRowCount++;
            
            // Update counts and buttons
            updateCounts();
            updateRemoveButtons();
            
            console.log('New row added successfully. Total rows:', nyaataRowCount);
        }
        
        // Function to remove a row
        function removeRow(button, tableType) {
            const row = button.closest('tr');
            const tbody = row.parentElement;
            const rows = tbody.querySelectorAll('tr');
            
            if (rows.length > 1) {
                row.remove();
                updateCounts();
                updateRemoveButtons();
                
                // Reindex rows if needed
                if (tableType === 'nyaata') {
                    reindexNyaataRows();
                }
            }
        }
        
        // Function to reindex Nyaata rows
        function reindexNyaataRows() {
            const rows = document.querySelectorAll('#nyaataBody tr');
            rows.forEach((row, index) => {
                // Update all inputs and selects in the row
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const currentName = input.getAttribute('name');
                    if (currentName) {
                        // Extract the field name (amount, source, or cost)
                        const match = currentName.match(/dhiyeesii_nyaata\[\d+\]\[(\w+)\]/);
                        if (match) {
                            const fieldName = match[1];
                            input.setAttribute('name', `dhiyeesii_nyaata[${index}][${fieldName}]`);
                        }
                    }
                });
            });
            nyaataRowCount = rows.length;
        }
        
        // Function to update remove buttons state
        function updateRemoveButtons() {
            // Update Nyaata remove buttons
            const nyaataRows = document.querySelectorAll('#nyaataBody tr');
            const nyaataButtons = document.querySelectorAll('#nyaataBody .remove-row');
            
            nyaataButtons.forEach(button => {
                button.disabled = nyaataRows.length <= 1;
            });
            
            // Update other tables remove buttons
            const hirmaannaaRows = document.querySelectorAll('#hirmaannaaBody tr');
            const hirmaannaaButtons = document.querySelectorAll('#hirmaannaaBody .remove-row');
            
            hirmaannaaButtons.forEach(button => {
                button.disabled = hirmaannaaRows.length <= 1;
            });
            
            const buaaRows = document.querySelectorAll('#buaaBody tr');
            const buaaButtons = document.querySelectorAll('#buaaBody .remove-row');
            
            buaaButtons.forEach(button => {
                button.disabled = buaaRows.length <= 1;
            });
        }
        
        // Function to update all counts
        function updateCounts() {
            // Hirmaannaa count
            const hirmaannaaRows = document.querySelectorAll('#hirmaannaaBody tr');
            document.getElementById('hirmaannaaCount').textContent = hirmaannaaRows.length;
            
            // Buaa count
            const buaaRows = document.querySelectorAll('#buaaBody tr');
            document.getElementById('buaaCount').textContent = buaaRows.length;
            
            // Nyaata count
            const nyaataRows = document.querySelectorAll('#nyaataBody tr');
            document.getElementById('nyaataCount').textContent = nyaataRows.length;
        }
        
        // Function to validate form before submission
        function validateForm(e) {
            let isValid = true;
            const errors = [];
            
            // Check Hirmaannaa section
            const hirmaannaaRows = document.querySelectorAll('#hirmaannaaBody tr');
            if (hirmaannaaRows.length === 0) {
                errors.push('Please add at least one item in Hirmaannaa section');
                isValid = false;
            }
            
            // Check Buaa section
            const buaaRows = document.querySelectorAll('#buaaBody tr');
            if (buaaRows.length === 0) {
                errors.push('Please add at least one item in Bu\'aa Qabeessummaa section');
                isValid = false;
            }
            
            // Check Nyaata section
            const nyaataRows = document.querySelectorAll('#nyaataBody tr');
            if (nyaataRows.length === 0) {
                errors.push('Please add at least one item in Nyaata section');
                isValid = false;
            }
            
            // Check year selection
            const yearSelect = document.getElementById('year');
            if (!yearSelect.value) {
                errors.push('Please select a year');
                isValid = false;
            }
            
            // Validate all number inputs are positive
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                const value = parseFloat(input.value);
                if (isNaN(value) || value < 0) {
                    errors.push('All amounts and costs must be positive numbers');
                    isValid = false;
                }
            });
            
            // Validate all required selects are filled
            const requiredSelects = document.querySelectorAll('select[required]');
            requiredSelects.forEach(select => {
                if (!select.value) {
                    errors.push('Please fill all required fields');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        }
        
        // Initialize on window load as well
        window.onload = function() {
            updateRemoveButtons();
            console.log('Window loaded - scripts initialized');
        };
    </script>
   
</x-layout>