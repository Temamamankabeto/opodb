<x-layout>
    <div class="container py-5">
        <h2 class="mb-4 text-primary fw-bold">
            <i class="fas fa-seedling me-2"></i>Liqii fi Qusannaa Mekaanayizeeshiniin Walqabate
        </h2>

        <div class="card shadow-sm border-primary">
            <div class="card-body">
                {{-- Display Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('liqii.qusannaa.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    @auth
                        <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                        <input type="hidden" name="kebele_id" value="{{ Auth::user()->kebele_id }}">
                        <input type="hidden" name="woreda_id" value="{{ Auth::user()->woreda_id }}">
                        <input type="hidden" name="zone_id" value="{{ Auth::user()->zone_id }}">
                        <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                    @endauth

                    {{-- Year Section --}}
                    <div class="card mb-4 shadow-sm border-light">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="far fa-calendar-alt me-2"></i>Bara</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="year" class="form-label">Bara Filadhu</label>
                                    <select name="year" id="year" class="form-select" required>
                                        <option value="">-- Select Year --</option>
                                        @for($y = 2011; $y <= date('Y'); $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                    <div class="invalid-feedback">
                                        Bara filadhu.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Material Supply Section --}}
                    <div class="card mb-4 shadow-sm border-light">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Akaakuu Liqii</h5>
                            <button type="button" id="add-row" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>Itti Dabali
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table id="input-table" class="table table-hover table-bordered mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Akaakuu Liqii</th>
                                            <th>Kan Dhuunfaa (Qty)</th>
                                            <th>Kan Waldaa (Qty)</th>
                                            <th>Woliigala</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="input-row text-center align-middle">
                                            <td>
                                                <select name="inputs[0][loan_type]" class="form-select" required>
                                                    <option value="">Akaakuu Filadhu</option>
                                                    <option value="Liqii waliigalaa">Liqii waliigalaa</option>
                                                    <option value="Liqii deebi’e">Liqii deebi’e</option>
                                                    <option value="iqii hafe">iqii hafe</option>
                                                    <option value="Qusannaa">Mekaanaayizeeshiniif</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="inputs[0][private_quantity]" class="form-control private-qty" step="0.01" min="0" required>
                                            </td>
                                            <td>
                                                <input type="number" name="inputs[0][group_quantity]" class="form-control group-qty" step="0.01" min="0" required>
                                            </td>
                                            <td>
                                                <input type="number" name="inputs[0][total_qty]" class="form-control total-qty bg-light" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Olkaawwadhu
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times me-2"></i>Haqi
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Success alert
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 3000
            });
        @endif

        // Dynamic row management
        let rowCount = 1;
        document.getElementById('add-row').addEventListener('click', function () {
            const tableBody = document.querySelector('#input-table tbody');
            const newRow = tableBody.rows[0].cloneNode(true);

            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.disabled = false;
            removeBtn.addEventListener('click', () => {
                if(tableBody.rows.length > 1) newRow.remove();
            });

            Array.from(newRow.querySelectorAll('input, select')).forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${rowCount}]`);
                if (!input.classList.contains('total-qty')) input.value = '';
            });

            newRow.querySelector('.total-qty').value = '0.00';
            tableBody.appendChild(newRow);
            rowCount++;
        });

        // Auto calculate total
        document.addEventListener('input', function(e) {
            if(e.target.classList.contains('private-qty') || e.target.classList.contains('group-qty')) {
                const row = e.target.closest('tr');
                const privateQty = parseFloat(row.querySelector('.private-qty').value) || 0;
                const groupQty = parseFloat(row.querySelector('.group-qty').value) || 0;
                row.querySelector('.total-qty').value = (privateQty + groupQty).toFixed(2);
            }
        });

        // Remove row button
        document.addEventListener('click', function(e){
            if(e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                const tableBody = document.querySelector('#input-table tbody');
                if(tableBody.rows.length > 1) row.remove();
            }
        });
    </script>
</x-layout>
