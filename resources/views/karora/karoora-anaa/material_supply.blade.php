<x-layout>
    <div class="container py-5">
       <div class="border border-primary rounded shadow-sm p-4 bg-white">
            <div class="card-header bg-white border-bottom py-3">
                <h2 class="h5 mb-0 text-primary">
                    <i class="fas fa-tractor text-primary mr-2"></i> Qabeenya Mekaanaayizeeshinii Horatame
                </h2>
            </div>

            <div class="card-body">
                <form action="{{ url('material-supplies') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    @auth
                        <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                        <input type="hidden" name="kebele_id" value="{{ Auth::user()->kebele_id }}">
                        <input type="hidden" name="woreda_id" value="{{ Auth::user()->woreda_id }}">
                        <input type="hidden" name="zone_id" value="{{ Auth::user()->zone_id }}">
                        <input type="hidden" name="region_id" value="{{ Auth::user()->region_id }}">
                        <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                    @endauth

                    <!-- Year Field -->
                    <div class="mb-4">
    <label for="year" class="form-label text-muted small font-weight-bold">Bara</label>
    <select name="year" id="year" class="form-control" required>
        <option value="" disabled selected>-- Select Year --</option>
        @for ($year = 2013; $year <= date('Y'); $year++)
            <option value="{{ $year }}">{{ $year }}</option>
        @endfor
    </select>
    <div class="invalid-feedback small">Bara filadhu.</div>
</div>
                    <!-- Material Supply Table -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            
                            <button type="button" id="add-row" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus mr-1"></i> Add Row
                            </button>
                        </div>

                        <div class="table-responsive border rounded">
                            <table id="input-table" class="table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 small text-muted font-weight-bold">Akaakuu Qabenyaa</th>
                                        <th class="border-0 small text-muted font-weight-bold">Kan Dhuunfaa (Qty)</th>
                                        <th class="border-0 small text-muted font-weight-bold">Kan Waldaa (Qty)</th>
                                        <th class="border-0 small text-muted font-weight-bold">Woliigala</th>
                                        <th class="border-0"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="input-row border-bottom">
                                        <td>
                                            <select name="inputs[0][material_name]" class="form-control form-control-sm border" required>
                                                <option value="" disabled selected>Select equipment</option>
                                                <option value="Tiraaktera">Tiraaktera</option>
                                                <option value="Kumbaaynera">Kumbaaynera</option>
                                                <option value="Paampii">Paampii</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="inputs[0][private_qty]" class="form-control form-control-sm border private-qty" step="0.01" min="0" required placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" name="inputs[0][group_qty]" class="form-control form-control-sm border group-qty" step="0.01" min="0" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input type="number" name="inputs[0][total_qty]" class="form-control form-control-sm border-0 bg-light total-qty" readonly placeholder="0.00">
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-link text-danger remove-row" disabled>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-check mr-2"></i>Submit
                        </button>
                    </div>
                </form>
            </div>

            
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: `<ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            @endif

            let rowCount = 1;

            document.getElementById('add-row').addEventListener('click', function() {
                const tableBody = document.querySelector('#input-table tbody');
                const newRow = tableBody.rows[0].cloneNode(true);

                const removeBtn = newRow.querySelector('.remove-row');
                removeBtn.disabled = false;
                removeBtn.addEventListener('click', function() {
                    if (tableBody.rows.length > 1) newRow.remove();
                });

                Array.from(newRow.querySelectorAll('input, select')).forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${rowCount}]`);
                    input.name = name;
                    if (!input.classList.contains('total-qty')) input.value = '';
                    input.classList.remove('is-invalid');
                });

                newRow.querySelector('.total-qty').value = '0.00';
                tableBody.appendChild(newRow);
                rowCount++;
            });

            document.addEventListener('input', e => {
                if (e.target.classList.contains('private-qty') || e.target.classList.contains('group-qty')) {
                    const row = e.target.closest('tr');
                    const privateQty = parseFloat(row.querySelector('.private-qty').value) || 0;
                    const groupQty = parseFloat(row.querySelector('.group-qty').value) || 0;
                    row.querySelector('.total-qty').value = (privateQty + groupQty).toFixed(2);
                }
            });

            document.addEventListener('click', e => {
                if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                    const row = e.target.closest('tr');
                    const tableBody = document.querySelector('#input-table tbody');
                    if (tableBody.rows.length > 1) row.remove();
                }
            });

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
        });
    </script>

    <style>
        body {
            background-color: #f8fafc;
        }
        .form-control, .input-group-text {
            height: calc(1.8em + 0.75rem + 2px);
            font-size: 0.875rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
        }
        .table td, .table th {
            vertical-align: middle;
            padding: 0.75rem;
        }
        .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .invalid-feedback {
            font-size: 0.75rem;
        }
    </style>
</x-layout>
