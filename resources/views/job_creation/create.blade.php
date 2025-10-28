 <x-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white shadow rounded-lg">
        <h2 class="text-2xl font-semibold text-indigo-700 mb-4 flex items-center gap-2">
            <i data-lucide="briefcase" class="w-6 h-6"></i> Job Creation Records
        </h2>

        <form action="{{ route('job-records.store') }}" method="POST">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-sm border border-gray-200" id="dynamicTable">
                    <thead class="bg-indigo-100 text-indigo-800">
                        <tr>
                            <th class="p-2">Year</th>
                            <th class="p-2">Enterprise Type</th>
                            <th class="p-2">Number</th>
                            <th class="p-2">Job Type</th>
                            <th class="p-2">Male</th>
                            <th class="p-2">Female</th>
                            <th class="p-2">Sum</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2">
                                <input type="number" name="data[0][year]" class="form-input" required>
                            </td>
                            <td class="p-2">
                                <select name="data[0][enterprise_type]" class="form-input" required>
                                    <option value="kan gurmaa’e">kan gurmaa’e</option>
                                    <option value="kan hijiitti gale">kan hojii tti gale</option>
                                </select>
                            </td>
                             <td class="p-2">
                                <input type="number" name="data[0][number]" class="form-input male" required>
                            </td>
                            <td class="p-2">
                                <select name="data[0][job_type_id]" class="form-input" required>
                                    @foreach ($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                           
                            <td class="p-2">
                                <input type="number" name="data[0][male]" class="form-input male" required>
                            </td>
                            <td class="p-2">
                                <input type="number" name="data[0][female]" class="form-input female" required>
                            </td>
                            <td class="p-2">
                                <input type="number" name="data[0][sum]" class="form-input sum bg-indigo-50" readonly>
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" class="remove-row text-rose-600 hover:text-rose-800 transition duration-200">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-4 mt-4">
                <button type="button" id="addRow" class="btn btn-sm btn-info">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i> Add Row
                </button>

                <button type="submit" class="btn btn-sm btn-success">
                    <i data-lucide="save" class="w-5 h-5"></i> Submit
                </button>
            </div>
        </form>
    </div>

    {{-- Scripts --}}
    <script>
        let i = 1;

        document.getElementById('addRow').addEventListener('click', function () {
            const table = document.getElementById('dynamicTable').querySelector('tbody');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td class="p-2"><input type="number" name="data[${i}][year]" class="form-input" required></td>
                <td class="p-2">
                    <select name="data[${i}][enterprise_type]" class="form-input" required>
                        <option value="kan gurmaa’e">kan gurmaa’e</option>
                        <option value="kan hijiitti gale">kan hojii tti gale</option>
                    </select>
                </td>
                <td class="p-2"><input type="number" name="data[${i}][number]" class="form-input male" required></td>

                <td class="p-2">
                    <select name="data[${i}][job_type_id]" class="form-input" required>
                        @foreach ($jobTypes as $jobType)
                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="p-2"><input type="number" name="data[${i}][male]" class="form-input male" required></td>
                <td class="p-2"><input type="number" name="data[${i}][female]" class="form-input female" required></td>
                <td class="p-2"><input type="number" name="data[${i}][sum]" class="form-input sum bg-indigo-50" readonly></td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-row text-rose-600 hover:text-rose-800 transition duration-200">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </td>
            `;

            table.appendChild(newRow);
            i++;
            lucide.createIcons();
        });

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('male') || e.target.classList.contains('female')) {
                const row = e.target.closest('tr');
                const male = parseInt(row.querySelector('.male').value) || 0;
                const female = parseInt(row.querySelector('.female').value) || 0;
                row.querySelector('.sum').value = male + female;
            }
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        lucide.createIcons();
    </script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .form-input {
            @apply w-full px-3 py-2 border border-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400;
        }
    </style>

    <!-- Include SweetAlert2 from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Entry',
                html: `{!! nl2br(e(session('error'))) !!}`,
                confirmButtonColor: '#d33',
            });
        @endif
    </script>
</x-layout>
