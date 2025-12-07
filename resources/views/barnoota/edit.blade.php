<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Barnoota Report</h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('sector.barnoota.update', $barnoota->id) }}" method="POST" id="barnootaForm">
                    @csrf
                    @method('PUT')
                    
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
                                                        <option value="{{ $value }}" {{ $barnoota->year == $value ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                                    <small class="float-right">Total Items: <span id="hirmaannaaCount">{{ count($hirmaannaaData) }}</span></small>
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
                                                    @foreach($hirmaannaaData as $index => $item)
                                                    <tr>
                                                        <td>
                                                            {{ $item['name'] }}
                                                            <input type="hidden" name="hirmaannaa[{{ $index }}][id]" value="{{ $item['id'] }}">
                                                        </td>
                                                        <td>
                                                            <select name="hirmaannaa[{{ $index }}][student_type]" class="form-control" required>
                                                                <option value="">Select Student Type</option>
                                                                @foreach($studentTypes as $type)
                                                                    <option value="{{ $type }}" {{ ($item['student_type'] ?? '') == $type ? 'selected' : '' }}>
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
                                                                       value="{{ $item['amount'] ?? 0 }}"
                                                                       placeholder="Enter amount">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Lakk</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm removeHirmaannaaRow">
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
                                    <small class="float-right">Total Items: <span id="buaaCount">{{ count($buaaQabeessummaaData) }}</span></small>
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
                                                    @foreach($buaaQabeessummaaData as $index => $item)
                                                    <tr>
                                                        <td>
                                                            {{ $item['name'] }}
                                                            <input type="hidden" name="buaa_qabeessummaa[{{ $index }}][id]" value="{{ $item['id'] }}">
                                                        </td>
                                                        <td>
                                                            <select name="buaa_qabeessummaa[{{ $index }}][student_type]" class="form-control" required>
                                                                <option value="">Select Student Type</option>
                                                                @foreach($studentTypes as $type)
                                                                    <option value="{{ $type }}" {{ ($item['student_type'] ?? '') == $type ? 'selected' : '' }}>
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
                                                                       value="{{ $item['amount'] ?? 0 }}"
                                                                       placeholder="Enter amount">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Lakk</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm removeBuaaRow">
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
                                    <small class="float-right">Total Items: <span id="nyaataCount">{{ count($dhiyeesiiNyaataData) }}</span></small>
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
                                                @foreach($dhiyeesiiNyaataData as $index => $item)
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" 
                                                                   name="dhiyeesii_nyaata[{{ $index }}][amount]" 
                                                                   class="form-control" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   value="{{ $item['amount'] ?? 0 }}"
                                                                   placeholder="Enter amount" 
                                                                   required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kunt</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select name="dhiyeesii_nyaata[{{ $index }}][source]" class="form-control" required>
                                                            <option value="">Select Source</option>
                                                            <option value="Kan manni barumsaa oomishe" {{ ($item['source'] ?? '') == 'Kan manni barumsaa oomishe' ? 'selected' : '' }}>
                                                                Kan manni barumsaa oomishe
                                                            </option>
                                                            <option value="Kan hawaasarraa sassaabame" {{ ($item['source'] ?? '') == 'Kan hawaasarraa sassaabame' ? 'selected' : '' }}>
                                                                Kan hawaasarraa sassaabame
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" 
                                                                   name="dhiyeesii_nyaata[{{ $index }}][cost]" 
                                                                   class="form-control" 
                                                                   step="0.01" 
                                                                   min="0" 
                                                                   value="{{ $item['cost'] ?? 0 }}"
                                                                   placeholder="Enter cost" 
                                                                   required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Birr</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm removeNyaataRow" {{ count($dhiyeesiiNyaataData) > 1 ? '' : 'disabled' }}>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
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
                                        <i class="fas fa-save"></i> Update Barnoota
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
        document.addEventListener('DOMContentLoaded', function() {
            let nyaataRowCount = {{ count($dhiyeesiiNyaataData) }};

            // Add new Nyaata row
            document.getElementById('addNyaataRow').addEventListener('click', function() {
                const tbody = document.getElementById('nyaataBody');
                const newIndex = nyaataRowCount;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="input-group">
                            <input type="number" 
                                   name="dhiyeesii_nyaata[${newIndex}][amount]" 
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
                        <select name="dhiyeesii_nyaata[${newIndex}][source]" class="form-control" required>
                            <option value="">Select Source</option>
                            <option value="Kan manni barumsaa oomishe">Kan manni barumsaa oomishe</option>
                            <option value="Kan hawaasarraa sassaabame">Kan hawaasarraa sassaabame</option>
                        </select>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" 
                                   name="dhiyeesii_nyaata[${newIndex}][cost]" 
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
                        <button type="button" class="btn btn-danger btn-sm removeNyaataRow">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
                nyaataRowCount++;
                updateNyaataCount();
                updateRemoveButtons();
            });

            // Remove row event delegation
            document.addEventListener('click', function(e) {
                // Handle Nyaata row removal
                if (e.target.closest('.removeNyaataRow')) {
                    const row = e.target.closest('tr');
                    if (document.querySelectorAll('#nyaataBody tr').length > 1) {
                        row.remove();
                        updateNyaataCount();
                        updateRemoveButtons();
                        // Reindex the remaining rows
                        reindexNyaataRows();
                    }
                }
                
                // Handle Hirmaannaa row removal
                if (e.target.closest('.removeHirmaannaaRow')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    updateHirmaannaaCount();
                }
                
                // Handle Buaa row removal
                if (e.target.closest('.removeBuaaRow')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    updateBuaaCount();
                }
            });

            // Reindex Nyaata rows after removal
            function reindexNyaataRows() {
                const rows = document.querySelectorAll('#nyaataBody tr');
                rows.forEach((row, index) => {
                    // Update input names
                    const amountInput = row.querySelector('input[name*="[amount]"]');
                    const sourceSelect = row.querySelector('select[name*="[source]"]');
                    const costInput = row.querySelector('input[name*="[cost]"]');
                    
                    if (amountInput) amountInput.name = `dhiyeesii_nyaata[${index}][amount]`;
                    if (sourceSelect) sourceSelect.name = `dhiyeesii_nyaata[${index}][source]`;
                    if (costInput) costInput.name = `dhiyeesii_nyaata[${index}][cost]`;
                });
                nyaataRowCount = rows.length;
            }

            // Update remove buttons state
            function updateRemoveButtons() {
                const rows = document.querySelectorAll('#nyaataBody tr');
                const buttons = document.querySelectorAll('.removeNyaataRow');
                
                buttons.forEach((button, index) => {
                    if (rows.length > 1) {
                        button.disabled = false;
                    } else {
                        button.disabled = true;
                    }
                });
            }

            // Update counts
            function updateHirmaannaaCount() {
                const count = document.querySelectorAll('#hirmaannaaBody tr').length;
                document.getElementById('hirmaannaaCount').textContent = count;
            }
            
            function updateBuaaCount() {
                const count = document.querySelectorAll('#buaaBody tr').length;
                document.getElementById('buaaCount').textContent = count;
            }
            
            function updateNyaataCount() {
                const count = document.querySelectorAll('#nyaataBody tr').length;
                document.getElementById('nyaataCount').textContent = count;
            }

            // Form validation
            document.getElementById('barnootaForm').addEventListener('submit', function(e) {
                let isValid = true;
                const errors = [];
                
                // Check Hirmaannaa section
                if (document.querySelectorAll('#hirmaannaaBody tr').length === 0) {
                    errors.push('Please add at least one item in Hirmaannaa section');
                    isValid = false;
                }
                
                // Check Buaa section
                if (document.querySelectorAll('#buaaBody tr').length === 0) {
                    errors.push('Please add at least one item in Bu\'aa Qabeessummaa section');
                    isValid = false;
                }
                
                // Check Nyaata section
                if (document.querySelectorAll('#nyaataBody tr').length === 0) {
                    errors.push('Please add at least one item in Nyaata section');
                    isValid = false;
                }
                
                // Validate amounts are positive
                const amountInputs = document.querySelectorAll('input[name*="[amount]"], input[name*="[cost]"]');
                amountInputs.forEach(input => {
                    if (parseFloat(input.value) < 0) {
                        errors.push('Amounts and costs cannot be negative');
                        isValid = false;
                    }
                });
                
                // Validate required fields
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
            });

            // Initialize
            updateRemoveButtons();
        });
    </script>
    
</x-layout>