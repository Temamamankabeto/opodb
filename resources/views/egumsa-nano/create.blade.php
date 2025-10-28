<x-layout>
    <div class="w-full px-4 py-6">
        <div class="border border-primary rounded-lg shadow-sm p-6 bg-white w-full">
            <h2 class="text-center text-xl font-semibold mb-4">🌲 Eegumsa Naannoo fi Misooma Sululaa</h2>

            <form action="{{ route('egumsa.associations.store') }}" method="POST">
                @csrf

                <div id="data-entries">
                    @php $index = 0; @endphp
                    @foreach ($entries ?? [null] as $entry)
                        <div
                            class="entry-row grid grid-cols-1 md:grid-cols-10 gap-4 mb-4 border p-4 rounded-xl bg-gray-100 shadow-sm relative">
                            <button type="button" onclick="removeRow(this)"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600"
                                title="Delete">×</button>

                            {{-- Year Dropdown --}}
                            <select name="data[{{ $index }}][year]"
                                class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required>
                                <option value="">-- Select Year --</option>
                                @for ($y = 2011; $y <= date('Y'); $y++)
                                    <option value="{{ $y }}" {{ ($entry['year'] ?? '') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>

                            <input type="number" name="data[{{ $index }}][limited_forest_land]" value="{{ $entry['limited_forest_land'] ?? '' }}"
                                placeholder="Limited Forest Land" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm"
                                required step="0.01">

                            <input type="number" name="data[{{ $index }}][land_free_from_contact]" value="{{ $entry['land_free_from_contact'] ?? '' }}"
                                placeholder="Land Free from Contact" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm"
                                required step="0.01">

                            <input type="number" name="data[{{ $index }}][associations_organized]" value="{{ $entry['associations_organized'] ?? '' }}"
                                placeholder="Associations Organized" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm"
                                required>

                            <input type="number" name="data[{{ $index }}][number_of_members]" value="{{ $entry['number_of_members'] ?? '' }}"
                                placeholder="Number of Members" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm"
                                required>

                            <input type="number" name="data[{{ $index }}][dry_waste_sites]" value="{{ $entry['dry_waste_sites'] ?? '' }}"
                                placeholder="Dry Waste Sites" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm"
                                required>

                            <input type="number" name="data[{{ $index }}][private_bathroom]" value="{{ $entry['private_bathroom'] ?? '' }}"
                                placeholder="Private Bathroom" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm bathroom-input"
                                required oninput="calculateSum(this)">

                            <input type="number" name="data[{{ $index }}][community_toilets]" value="{{ $entry['community_toilets'] ?? '' }}"
                                placeholder="Community Toilets" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm bathroom-input"
                                required oninput="calculateSum(this)">

                            <input type="number" name="data[{{ $index }}][sum_bathroom]" value="{{ $entry['sum_bathroom'] ?? '' }}"
                                placeholder="Sum Bathroom" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm sum-bathroom"
                                readonly>
                        </div>
                        @php $index++; @endphp
                    @endforeach
                </div>

                <div class="flex gap-4 mt-4">
                    <button type="button" onclick="addEntry({{ $index }})"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">➕ Add Row</button>

                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">💾 Save All</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `{!! nl2br(e(session('error'))) !!}`,
                confirmButtonColor: '#d33',
            });
        </script>
    @endif

    <script>
        let entryIndex = {{ $index ?? 1 }};
        const currentYear = new Date().getFullYear();

        function addEntry(index = null) {
            if (index !== null) entryIndex = index;

            const container = document.getElementById('data-entries');

            const row = document.createElement('div');
            row.className =
                'entry-row grid grid-cols-1 md:grid-cols-10 gap-4 mb-4 border p-4 rounded-xl bg-gray-100 shadow-sm relative';

            // Build year dropdown dynamically
            let yearOptions = `<option value="">-- Select Year --</option>`;
            for (let y = 2011; y <= currentYear; y++) {
                yearOptions += `<option value="${y}" ${y === currentYear ? 'selected' : ''}>${y}</option>`;
            }

            row.innerHTML = `
                <button type="button" onclick="removeRow(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600" title="Delete">×</button>

                <select name="data[${entryIndex}][year]" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required>
                    ${yearOptions}
                </select>

                <input type="number" name="data[${entryIndex}][limited_forest_land]" placeholder="Limited Forest Land" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required step="0.01">

                <input type="number" name="data[${entryIndex}][land_free_from_contact]" placeholder="Land Free from Contact" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required step="0.01">

                <input type="number" name="data[${entryIndex}][associations_organized]" placeholder="Associations Organized" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required>

                <input type="number" name="data[${entryIndex}][number_of_members]" placeholder="Number of Members" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required>

                <input type="number" name="data[${entryIndex}][dry_waste_sites]" placeholder="Dry Waste Sites" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm" required>

                <input type="number" name="data[${entryIndex}][private_bathroom]" placeholder="Private Bathroom" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm bathroom-input" required oninput="calculateSum(this)">

                <input type="number" name="data[${entryIndex}][community_toilets]" placeholder="Community Toilets" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm bathroom-input" required oninput="calculateSum(this)">

                <input type="number" name="data[${entryIndex}][sum_bathroom]" placeholder="Sum Bathroom" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm sum-bathroom" readonly>
            `;

            container.appendChild(row);
            entryIndex++;
        }

        function removeRow(button) {
            button.closest('.entry-row').remove();
        }

        function calculateSum(input) {
            const row = input.closest('.entry-row');
            const privateBathroom = row.querySelector('input[name$="[private_bathroom]"]');
            const communityToilets = row.querySelector('input[name$="[community_toilets]"]');
            const sumField = row.querySelector('.sum-bathroom');

            const privateVal = parseInt(privateBathroom.value) || 0;
            const communityVal = parseInt(communityToilets.value) || 0;

            sumField.value = privateVal + communityVal;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.bathroom-input').forEach(input => calculateSum(input));
        });
    </script>
</x-layout>
