<x-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-6 text-primary">Narsarii Gandicha Keessatti Argamu</h2>

        <div class="border border-primary rounded shadow-lg p-6 bg-white">

            <form action="{{ route('nursery-sites.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong class="font-bold">Whoops!</strong>
                        <span class="block">There were some problems with your input:</span>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300 mb-4 text-sm rounded" id="nurseryTable">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="p-2 border">Year</th>
                                <th class="p-2 border">Baayyina</th>
                                <th class="p-2 border">Ballina</th>
                                <th class="p-2 border">Nama Hirmaate</th>
                                <th class="p-2 border">Baayina Guyyaa</th>
                                <th class="p-2 border text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="rows">
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="border p-2">
                                    <select name="nurseries[0][year]" class="w-full border rounded px-2 py-1 bg-white" required>
                                        <option value="">-- Select Year --</option>
                                        @for ($y = 2011; $y <= date('Y'); $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="border p-2">
                                    <input type="text" name="nurseries[0][baayyina]" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="border p-2">
                                    <input type="number" step="0.01" name="nurseries[0][ballina]" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="border p-2">
                                    <input type="number" step="0.01" name="nurseries[0][nama_hirmaate]" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="border p-2">
                                    <input type="number" step="0.01" name="nurseries[0][baayina_guyyaa]" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="border p-2 text-center">
                                    <button type="button" class="remove-row text-red-600 hover:text-red-800">🗑️</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap justify-between gap-4 mt-6">
                    <button type="button" id="addRow" class="bg-primary text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        + Add Row
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        Save All
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let rowCount = 1;

            // Add Row
            document.getElementById('addRow').addEventListener('click', function () {
                const tableBody = document.getElementById('rows');
                const row = document.createElement('tr');
                row.classList.add('bg-white', 'hover:bg-gray-50');
                row.innerHTML = `
                    <td class="border p-2">
                        <select name="nurseries[${rowCount}][year]" class="w-full border rounded px-2 py-1 bg-white" required>
                            <option value="">-- Select Year --</option>
                            @for ($y = 2011; $y <= date('Y'); $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </td>
                    <td class="border p-2"><input type="text" name="nurseries[${rowCount}][baayyina]" class="w-full border rounded px-2 py-1" required></td>
                    <td class="border p-2"><input type="number" step="0.01" name="nurseries[${rowCount}][ballina]" class="w-full border rounded px-2 py-1" required></td>
                    <td class="border p-2"><input type="number" step="0.01" name="nurseries[${rowCount}][nama_hirmaate]" class="w-full border rounded px-2 py-1" required></td>
                    <td class="border p-2"><input type="number" step="0.01" name="nurseries[${rowCount}][baayina_guyyaa]" class="w-full border rounded px-2 py-1" required></td>
                    <td class="border p-2 text-center">
                        <button type="button" class="remove-row text-red-600 hover:text-red-800">🗑️</button>
                    </td>
                `;
                tableBody.appendChild(row);
                rowCount++;
            });

            // Remove Row
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-row')) {
                    const totalRows = document.getElementById('rows').querySelectorAll('tr').length;
                    if (totalRows > 1) {
                        e.target.closest('tr').remove();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Cannot remove',
                            text: 'You must have at least one row',
                            confirmButtonColor: '#3085d6',
                        });
                    }
                }
            });

            // SweetAlert: success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    timer: 3000
                });
            @endif

            // SweetAlert: validation error
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    html: `{!! '<ul class="text-left">'.implode('', $errors->all('<li>:message</li>')).'</ul>' !!}`,
                    confirmButtonColor: '#d33',
                    showConfirmButton: true
                });
            @endif
        });
    </script>
</x-layout>
