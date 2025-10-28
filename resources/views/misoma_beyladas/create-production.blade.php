 <x-layout> 
    <div class="w-full px-4 py-6">
        <div class="border border-primary rounded-lg shadow-sm p-6 bg-white w-full">
        <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center">Misoma Beyladaa fi bu'aa isaanii </h2>

        <form action="{{ route('misoma_beyladas_production.store') }}" method="POST">
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

            <div class="w-full overflow-x-visible">
                <table class="w-full border border-gray-300 text-sm md:text-base table-auto" id="entriesTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Bara</th>
                            <th class="p-2 border">Jabbii ”AI” n dhalatte</th>
                            <th class="p-2 border">Killee oomishame</th>
                            <th class="p-2 border">Lukkuu foonii gabaaf dhiyaattee gurguramte</th>
                            <th class="p-2 border">Damma oomishame</th>
                            <th class="p-2 border">Qurxummii gabaaf dhiyaate</th>
                            <th class="p-2 border">Aannan elmame</th>
                            <th class="p-2 border">Gocha</th>
                        </tr>
                    </thead>
                    <tbody id="rows">
                        <tr>
                            <td class="border p-2"><input type="text" name="entries[0][year]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][Jabbii_ai]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][killee_oomishame]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][lukkuu_foonii]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][damma_oomishame]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][qurxummii_gabaaf]" class="form-input w-full" required></td>
                            <td class="border p-2"><input type="number" name="entries[0][aannan_elmame]" class="form-input w-full" required></td>
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
                <td class="border p-2"><input type="number" name="entries[${rowCount}][Jabbii_ai]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][killee_oomishame]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][lukkuu_foonii]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][damma_oomishame]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][qurxummii_gabaaf]" class="form-input w-full" required></td>
                <td class="border p-2"><input type="number" name="entries[${rowCount}][aannan_elmame]" class="form-input w-full" required></td>
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
