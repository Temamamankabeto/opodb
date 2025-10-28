<x-layout>
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">🌿 Hojii Gabbina Biyyoo</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>maaloo!</strong> Rakkoo Armaan Gadii Fyraa:
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('gabbina_biyyee.store') }}" method="POST">
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
                        <select name="year" id="year" class="form-select" required>
                        <option value="">-- Select Year --</option>
    @for ($y = 2011; $y <= date('Y'); $y++)
        <option value="{{ $y }}">{{ $y }}</option>
    @endfor
</select>                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center" id="entry-table">
                            <thead class="table-light">
                                <tr>
                                    <th>🧪 Gosa Kompostii</th>
                                    <th>👥 Kan Woloo(Qt)</th>
                                    <th>👤 Kan Dhuunfa(Qt)</th>
                                    <th>🧮 Ida’ama (Qt)</th>
                                    <th>🌱 Bal’ina Lafaa (ha)</th>
                                    <th>🔧 Gocha</th>
                                </tr>
                            </thead>
                            <tbody id="entry-body">
                                <tr>
                                    <td>
                                        <select name="entries[0][compost_type]" class="form-select compost-type" required>
                                            <option value="" disabled>-- Gosa Filadhu --</option>
                                            @foreach($compostTypes as $type)
                                                <option value="{{ $type->name }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="entries[0][common]" class="form-control common" step="0.01"></td>
                                    <td><input type="number" name="entries[0][individual]" class="form-control individual" step="0.01"></td>
                                    <td><input type="number" name="entries[0][total]" class="form-control total" step="0.01" readonly></td>
                                    <td><input type="number" name="entries[0][land_area]" class="form-control land-area" step="0.01" style="display:none;" placeholder="Only for 'Nooraa Hojiirra oole'"></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success add-row" title="Add Row">➕</button>
                                        <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove Row">🗑️</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">✅ Karoora Koompostii Galchi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
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
        let rowIndex = 1;

        function calculateTotal(row) {
            const common = parseFloat(row.querySelector('.common')?.value || 0);
            const individual = parseFloat(row.querySelector('.individual')?.value || 0);
            row.querySelector('.total').value = (common + individual).toFixed(2);
        }

        function toggleLandArea(row) {
            const select = row.querySelector('.compost-type');
            const landInput = row.querySelector('.land-area');
            if (select.value === "Nooraa Hojiirra oole") {
                landInput.style.display = 'block';
            } else {
                landInput.style.display = 'none';
                landInput.value = '';
            }
        }

        document.getElementById('entry-body').addEventListener('input', function (e) {
            const row = e.target.closest('tr');
            if (e.target.classList.contains('common') || e.target.classList.contains('individual')) {
                calculateTotal(row);
            }
        });

        document.getElementById('entry-body').addEventListener('change', function (e) {
            if (e.target.classList.contains('compost-type')) {
                const row = e.target.closest('tr');
                toggleLandArea(row);
            }
        });

        document.getElementById('entry-body').addEventListener('click', function (e) {
            const target = e.target;

            if (target.classList.contains('remove-row')) {
                const row = target.closest('tr');
                const tbody = document.getElementById('entry-body');
                if (tbody.rows.length > 1) {
                    row.remove();
                }
            }

            if (target.classList.contains('add-row')) {
                const tbody = document.getElementById('entry-body');
                const newRow = document.createElement('tr');

                newRow.innerHTML = `
                    <td>
                        <select name="entries[${rowIndex}][compost_type]" class="form-select compost-type" required>
                            <option value="">-- Select Type --</option>
                            <option value="Worm Compost (Qt)">Worm Compost (Qt)</option>
                            <option value="Regular Compost (m³)">Regular Compost (m³)</option>
                            <option value="Nooraa Hojiirra oole">Nooraa Hojiirra oole</option>
                        </select>
                    </td>
                    <td><input type="number" name="entries[${rowIndex}][common]" class="form-control common" step="0.01" required></td>
                    <td><input type="number" name="entries[${rowIndex}][individual]" class="form-control individual" step="0.01" required></td>
                    <td><input type="number" name="entries[${rowIndex}][total]" class="form-control total" step="0.01" readonly></td>
                    <td><input type="number" name="entries[${rowIndex}][land_area]" class="form-control land-area" step="0.01" style="display:none;" placeholder="ha"></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success add-row" title="Add Row">➕</button>
                        <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove Row">🗑️</button>
                    </td>
                `;
                tbody.appendChild(newRow);
                rowIndex++;
            }
        });
    </script>
</x-layout>
