<x-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container mt-4">
        <h2 class="mb-4">Calla Guddiftuu</h2>

        <form action="{{ route('land-plans.store') }}" method="POST">
            @csrf

            @auth
                <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                <input type="hidden" name="kebele_id" value="{{ Auth::user()->kebele_id }}">
                <input type="hidden" name="woreda_id" value="{{ Auth::user()->woreda_id }}">
                <input type="hidden" name="zone_id" value="{{ Auth::user()->zone_id }}">
                <input type="hidden" name="region_id" value="{{ Auth::user()->region_id }}">
                <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
            @endauth

            <table id="land-plan-table" class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Year</th>
                        <th>Season</th>
                        <th>Ferilizer Type</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>
                            <button type="button" onclick="addRow()" class="btn btn-sm btn-success">➕</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="plans[0][year]" class="form-control year-select" required>
                                <option value="" disabled selected>Select Year</option>
                            </select>
                        </td>
                        <td>
                            <select name="plans[0][season]" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="Ganna">Ganna</option>
                                <option value="Bona">Bona</option>
                                <option value="Arfaasa">Arfaasa</option>
                            </select>
                        </td>
                        <td>
                            <select name="plans[0][type]" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="Xaa’oo keemikaalaa">Xaa’oo keemikaalaa</option>
                                <option value="Sanyii Filataa">Sanyii Filataa</option>
                                <option value="Biifaa keemikaala farra aramaa fi farra ilbiisotaa">Biifaa keemikaala farra aramaa fi farra ilbiisotaa</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="plans[0][quantity]" class="form-control" required>
                        </td>
                        <td>
                            <select name="plans[0][unit]" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="kg">kg</option>
                                <option value="litre">litre</option>
                                <option value="hect">hect</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" onclick="removeRow(this)" class="btn btn-sm btn-danger">❌</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mt-3">Save Plans</button>
        </form>
    </div>

    <!-- SweetAlert Handling -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>

    <script>
        let rowCount = 1;

        // Populate year select dynamically
        function populateYearSelect(select) {
            const currentYear = new Date().getFullYear();
            const startYear = 2010;
            const endYear = currentYear + 5;

            select.innerHTML = '<option value="" disabled selected>Select Year</option>';

            for (let y = startYear; y <= endYear; y++) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                select.appendChild(opt);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.year-select').forEach(populateYearSelect);
        });

        function addRow() {
            const tableBody = document.querySelector('#land-plan-table tbody');
            const firstRow = tableBody.rows[0];
            const newRow = firstRow.cloneNode(true);

            Array.from(newRow.querySelectorAll('input, select')).forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/\[\d+\]/, `[${rowCount}]`);
                    el.value = '';
                }
            });

            // Populate the year select in the new row
            populateYearSelect(newRow.querySelector('.year-select'));

            tableBody.appendChild(newRow);
            rowCount++;
        }

        function removeRow(button) {
            const row = button.closest('tr');
            const rowCount = document.querySelectorAll('#land-plan-table tbody tr').length;
            if (rowCount > 1) row.remove();
        }
    </script>
</x-layout>
