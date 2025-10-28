 <x-layout>
      <div class="w-full px-4 py-6">
        <div class="border border-primary rounded-lg shadow-sm p-6 bg-white w-full">
        <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center">  Misoma Beylada  </h2>

        <form action="{{ route('misoma_beyladas.store') }}" method="POST">
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
                <table class="min-w-full border border-gray-300 text-sm md:text-base" id="entriesTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border whitespace-nowrap">Bara</th>
                            <th class="p-2 border whitespace-nowrap">Raaddeen</th>
                            <th class="p-2 border whitespace-nowrap">Lukkuu Cuucii</th>
                            <th class="p-2 border whitespace-nowrap">Lukkuu Sookee</th>
                            <th class="p-2 border whitespace-nowrap">Cuucii Qurxummii</th>
                            <th class="p-2 border whitespace-nowrap">Gaagura Hammayya</th>
                            <th class="p-2 border whitespace-nowrap">Lafa Omisha Beylada</th>
                            <th class="p-2 border whitespace-nowrap">Omisha Beylada</th>
                            <th class="p-2 border whitespace-nowrap">Gocha</th>
                        </tr>
                    </thead>
                    <tbody id="rows">
                        <tr>
                            <td class="border p-2"><input type="text" name="entries[0][year]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][raaddeen]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][lukkuu_cuucii]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][lukkuu_sookee]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][cuucii_qurxummii]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][gaagura_hammayya]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][lafa_omisha_beylada]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][omisha_beylada]" class="form-input w-full" required></td>
                            <td class="border p-2 text-center">
                                <button type="button" class="text-red-500 remove-row">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap justify-between items-center mt-4">
                <button type="button" id="addRow"  >
                    + Add Row
                </button>

                <button type="submit"  >
                    Save All
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
                <td class="border p-2"><input type="text" name="entries[${rowCount}][year]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][raaddeen]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][lukkuu_cuucii]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][lukkuu_sookee]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][cuucii_qurxummii]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][gaagura_hammayya]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][lafa_omisha_beylada]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][omisha_beylada]" class="form-input w-full" required></td>
                <td class="border p-2 text-center">
                    <button type="button" class="text-red-500 remove-row">🗑️</button>
                </td>
            `;
            tableBody.appendChild(row);
            rowCount++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                // Optional: decrement rowCount or recalculate indexes
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
