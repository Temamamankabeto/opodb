<x-layout>
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="fas fa-seedling mr-2"></i>Karoora Call Guddiftuu (Input Planning)</h2>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h5 class="alert-heading">Erratan fixi!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <form action="{{ route('inputs.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                   
                    @auth
                        <input type="hidden" class="form-control" name="created_by" id="created_by" value="{{ Auth::id() }}">
                        <input type="hidden" name="kebele_id" value="{{ Auth::user()->kebele_id }}">
                        <input type="hidden" name="woreda_id" value="{{ Auth::user()->woreda_id }}">
                        <input type="hidden" name="zone_id" value="{{ Auth::user()->zone_id }}">
                        <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                        
                    @endauth
                    
                    <!-- Year and Season Section -->
                    <div class="form-section mb-4 p-3 border rounded bg-light">
                        <h5 class="section-title text-primary mb-3">
                            <i class="far fa-calendar-alt mr-2"></i>Waqtii fi Bara
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="year" class="font-weight-bold">Bara Guuti</label>
                                <div class="input-group">
                                    <input type="text" name="year" id="year" class="form-control datepicker-year" 
                                           value="{{ old('year', $selectedYear ?? '') }}" required
                                           placeholder="Bara filadhu">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                    </div>
                                    <div class="invalid-feedback">
                                        Maaloo bara guuti galchi.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="season" class="font-weight-bold">Waqtii</label>
                                <select name="season" id="season" class="form-control selectpicker" required>
                                    <option value="">Waqtii Filadhu</option>
                                    <option value="Ganna" {{ old('season') == 'Ganna' ? 'selected' : '' }}>Ganna</option>
                                    <option value="Bona" {{ old('season') == 'Bona' ? 'selected' : '' }}>Bona</option>
                                    <option value="Arfaasaa" {{ old('season') == 'Arfaasaa' ? 'selected' : '' }}>Arfaasaa</option>
                                </select>
                                <div class="invalid-feedback">
                                    Maaloo waqtii filadhu.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Input Items Section -->
                    <div class="form-section mb-4">
                        <h5 class="section-title text-primary mb-3">
                            <i class="fas fa-tractor mr-2"></i>Callaguddiftuuwwan
                        </h5>
                        
                        <div class="table-responsive">
                            <table id="input-table" class="table table-hover table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="40%">Gosa Callaguddiftuu</th>
                                        <th width="20%">Hanga</th>
                                        <th width="20%">Safartuu</th>
                                        <th width="20%">
                                            <button type="button" id="add-row" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus"></i> Calli Dabaladhu
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="inputs[0][type]" class="form-control" required>
                                                <option value="">Gosa Callaguddiftuu filadhu</option>
                                                <option value="Xaa'oo keemikaalaa">Xaa'oo keemikaalaa</option>
                                                <option value="Sanyii Filataa">Sanyii Filataa</option>
                                                <option value="Biifaa keemikaala farra aramaa fi farra ilbiisotaa">Biifaa keemikaala farra aramaa fi farra ilbiisotaa</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="inputs[0][quantity]" class="form-control" required 
                                                   placeholder="Hanga">
                                        </td>
                                        <td>
                                            <select name="inputs[0][unit]" class="form-control" required>
                                                <option value="">Safartuu Filadhu</option>
                                                <option value="kg">kun (kg)</option>
                                                <option value="litre">liitira (l)</option>
                                                <option value="quintal">hect (qt)</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save mr-2"></i>Karoora Kee Galchi
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg ml-2">
                            <i class="fas fa-times mr-2"></i>Dhiisi
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="card-footer text-muted">
                <small>Karoora callaguddiftuu keessan galmaa'inaan galchitan akka hubataniif gadi bu'aa.</small>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript -->
    <script>
        // Check for success message and show SweetAlert
        @if(session('success'))
            Swal.fire({
                title: 'Galchitame!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Fayyadama',
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif

        // Dynamic row management
        let rowCount = 1;

        document.getElementById('add-row').addEventListener('click', function () {
            const tableBody = document.querySelector('#input-table tbody');
            const newRow = tableBody.rows[0].cloneNode(true);
            
            // Enable remove button for the new row
            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.disabled = false;
            removeBtn.addEventListener('click', function() {
                newRow.remove();
            });
            
            // Update all inputs in the new row
            Array.from(newRow.querySelectorAll('input, select')).forEach(function (input) {
                const name = input.getAttribute('name');
                const newName = name.replace(/\[\d+\]/, '[' + rowCount + ']');
                input.setAttribute('name', newName);
                input.value = '';
                
                // Reset validation
                input.classList.remove('is-invalid');
            });
            
            tableBody.appendChild(newRow);
            rowCount++;
            
            // Initialize selectpicker for new selects
            $('.selectpicker').selectpicker('refresh');
        });

        // Enable remove functionality for existing rows
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                const tableBody = document.querySelector('#input-table tbody');
                if (tableBody.rows.length > 1) {
                    row.remove();
                }
            }
        });
        
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                const forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
        
        // Year picker initialization
        $(document).ready(function(){
            $('.datepicker-year').datepicker({
                format: "yyyy",
                viewMode: "years", 
                minViewMode: "years",
                autoclose: true,
                todayHighlight: true
            });
            
            // Initialize select picker
            $('.selectpicker').selectpicker();
        });
    </script>

    <style>
        .form-section {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #007bff;
        }
        
        .section-title {
            font-weight: 600;
            border-bottom: 1px dashed #007bff;
            padding-bottom: 8px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .card-header {
            border-radius: 0 !important;
        }
        
        #input-table thead th {
            vertical-align: middle;
            text-align: center;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        .btn-lg {
            padding: 0.5rem 1.5rem;
            font-size: 1.1rem;
        }
        
        .invalid-feedback {
            font-size: 0.9rem;
        }
        
        .selectpicker {
            width: 100% !important;
        }
        
        /* SweetAlert customization */
        .swal2-popup {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
    </style>
</x-layout>