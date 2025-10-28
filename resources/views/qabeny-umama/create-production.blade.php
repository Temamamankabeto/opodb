<x-layout>
   <div class="w-full px-4 py-6">
        <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center text-primary">
            Qabeenya Uumamaa fi Misooma Sululaa
        </h2>

        <div class="border border-primary rounded-xl shadow-md p-6 bg-white w-full">

        <form action="{{ route('qabenya.store') }}" method="POST">
            @csrf

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm md:text-base text-center" id="entriesTable">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-700">
                            <th class="p-2 border">Bara</th>
                            <th class="p-2 border">Dheerina Daagaa Hidhamee (m)</th>
                            <th class="p-2 border">Bal’ina Lafaa (ha)</th>
                            <th class="p-2 border">Baay’ina Hirmaannaa Nama</th>
                            <th class="p-2 border">Turtii (Guyyaa)</th>
                            <th class="p-2 border">Gocha</th>
                        </tr>
                    </thead>
                    <tbody id="rows">
                        <tr>
                            <td class="border p-2">
                                <select name="entries[0][year]" class="form-select w-full rounded-md border-gray-300" required>
                                    <option value="">-- Bara Filadhu --</option>
                                    @for ($y = now()->year; $y >= 2011; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </td>
                            <td class="border p-2">
                                <input type="number" step="0.01" name="entries[0][dherina_d_h]" class="form-input w-full" required>
                            </td>
                            <td class="border p-2">
                                <input type="number" step="0.01" name="entries[0][ballina]" class="form-input w-full" required>
                            </td>
                            <td class="border p-2">
                                <input type="number" name="entries[0][humna_nama]" class="form-input w-full" required>
                            </td>
                            <td class="border p-2">
                                <input type="number" name="entries[0][turtii]" class="form-input w-full" required>
                            </td>
                            <td class="border p-2 text-center">
                                <button type="button" class="text-red-500 hover:text-red-700 remove-row">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap justify-between items-center mt-4">
                <button type="button" id="addRow" 
                    class="btn btn-sm btn-info">
                    + Add Row
                </button>

                <button type="submit" 
                    class="btn btn-sm btn-success">
                    💾 Save All
                </button>
            </div>
        </form>
    </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let rowCount = 1;

        document.getElementById('addRow').addEventListener('click', function () {
            const tableBody = document.getElementById('rows');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border p-2">
                    <select name="entries[${rowCount}][year]" class="form-select w-full rounded-md border-gray-300" required>
                        <option value="">-- Bara Filadhu --</option>
                        @for ($y = now()->year; $y >= 2011; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </td>
                <td class="border p-2">
                    <input type="number" step="0.01" name="entries[${rowCount}][dherina_d_h]" class="form-input w-full" required>
                </td>
                <td class="border p-2">
                    <input type="number" step="0.01" name="entries[${rowCount}][ballina]" class="form-input w-full" required>
                </td>
                <td class="border p-2">
                    <input type="number" name="entries[${rowCount}][humna_nama]" class="form-input w-full" required>
                </td>
                <td class="border p-2">
                    <input type="number" name="entries[${rowCount}][turtii]" class="form-input w-full" required>
                </td>
                <td class="border p-2 text-center">
                    <button type="button" class="text-red-500 hover:text-red-700 remove-row">🗑️</button>
                </td>
            `;
            tableBody.appendChild(row);
            rowCount++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-layout>
