<x-layout>
<div class="content-header">
    <section class="content">
        <div class="container-fluid py-4">

            {{-- ================= CARD ================= --}}
            <div class="card card-primary shadow-sm">
                <div class="card-header">
                    <h4 class="card-title mb-0">🌿 Hojii Gabbina Biyyoo</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>maaloo!</strong> Rakkoo Armaan Gadii Fyraa:
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
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

                        {{-- ================= YEAR SELECTION ================= --}}
                        <div class="form-group mb-3">
                            <label for="yearSelect" class="form-label fw-bold">📅 Bara</label>
                            <select name="year" class="form-control" required id="yearSelect">
                                <option value="">-- Select Year --</option>
                                @php
                                    $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
                                    $years = range($currentEthiopianYear - 2, $currentEthiopianYear + 3);
                                @endphp
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                        {{ $year }} EC
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ================= COMPOST TYPE CHECKBOXES ================= --}}
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">🧪 Gosa Kompostii Filadhu</label>
                            <div class="row p-3 bg-light rounded" id="compostTypeSelection">
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

                        {{-- ================= DYNAMIC DATA ENTRY TABLE ================= --}}
                        <div class="table-responsive mb-3" id="dataEntrySection" style="display: none;">
                            <table class="table table-bordered table-hover text-center" id="entry-table">
                                <thead class="table-primary">
                                    <tr>
                                        <th>🧪 Gosa Kompostii</th>
                                        <th>👥 Kan Woloo</th>
                                        <th>👤 Kan Dhuunfa</th>
                                        <th>🧮 Ida'ama</th>
                                        <th>🌱 Bal'ina Lafaa (ha)</th>
                                    </tr>
                                </thead>
                                <tbody id="entry-body"></tbody>
                            </table>
                        </div>

                        {{-- ================= SUBMIT BUTTON ================= --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" style="display: none;">
                                ✅ Karoora Koompostii Galchi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= SWEETALERT SUCCESS ================= --}}
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

    {{-- ================= DYNAMIC FORM SCRIPT ================= --}}
    <script>
        let selectedCompostTypes = new Set();
        let compostTypeUnits = {};
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.compost-type-checkbox').forEach(checkbox => {
                compostTypeUnits[checkbox.value] = checkbox.dataset.unit || 'Qt';
            });
        });

        function calculateTotal(row) {
            const common = parseFloat(row.querySelector('.common')?.value || 0);
            const individual = parseFloat(row.querySelector('.individual')?.value || 0);
            row.querySelector('.total').value = (common + individual).toFixed(2);
        }

        function createRow(compostType) {
            const rowIndex = selectedCompostTypes.size - 1;
            const row = document.createElement('tr');
            row.className = 'compost-row';
            row.dataset.compostType = compostType;
            const unit = compostTypeUnits[compostType] || 'Qt';
            row.innerHTML = `
                <td>
                    <strong>${compostType}</strong>
                    <div class="badge bg-secondary">${unit}</div>
                    <input type="hidden" name="entries[${rowIndex}][compost_type]" value="${compostType}">
                    <input type="hidden" name="entries[${rowIndex}][unit]" value="${unit}">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][common]" class="form-control common" step="0.01" min="0" required placeholder="0.00">
                        <span class="input-group-text">${unit}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][individual]" class="form-control individual" step="0.01" min="0" required placeholder="0.00">
                        <span class="input-group-text">${unit}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][total]" class="form-control total" step="0.01" readonly placeholder="0.00">
                        <span class="input-group-text">${unit}</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" name="entries[${rowIndex}][land_area]" class="form-control land-area" step="0.01" min="0" placeholder="0.00">
                        <span class="input-group-text">ha</span>
                    </div>
                </td>`;
            row.querySelector('.common').addEventListener('input', () => calculateTotal(row));
            row.querySelector('.individual').addEventListener('input', () => calculateTotal(row));
            return row;
        }

        function updateForm() {
            const entryBody = document.getElementById('entry-body');
            const dataEntrySection = document.getElementById('dataEntrySection');
            const submitBtn = document.getElementById('submitBtn');
            entryBody.innerHTML = '';
            if (selectedCompostTypes.size > 0) {
                dataEntrySection.style.display = '';
                submitBtn.style.display = '';
                selectedCompostTypes.forEach(type => entryBody.appendChild(createRow(type)));
            } else {
                dataEntrySection.style.display = 'none';
                submitBtn.style.display = 'none';
            }
        }

        document.getElementById('compostTypeSelection').addEventListener('change', function(e) {
            if (e.target.classList.contains('compost-type-checkbox')) {
                e.target.checked ? selectedCompostTypes.add(e.target.value) : selectedCompostTypes.delete(e.target.value);
                updateForm();
            }
        });

        document.getElementById('compostForm').addEventListener('submit', function(e) {
            if (!document.getElementById('yearSelect').value || selectedCompostTypes.size === 0) {
                e.preventDefault();
                Swal.fire({icon: 'error', title: 'Error!', text: 'Please select a year and at least one compost type.', confirmButtonColor: '#3085d6', confirmButtonText: 'OK'});
            }
        });
    </script>
</x-layout>
