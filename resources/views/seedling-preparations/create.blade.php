<x-layout>
    <div class="w-full px-4 py-6">
        <div class="border border-primary rounded-lg shadow-sm p-6 bg-white w-full">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">🌱 Qophii Biqiltuu</h1>

            <form method="POST" action="{{ route('seedling-preparations.store') }}" class="space-y-4 w-full">
                @csrf

                <div class="w-full overflow-x-auto">
                    <table class="w-full table-auto border border-gray-300 rounded-lg shadow-sm" id="seedling-preparations-table">
                        <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <tr>
                                <th class="p-3 border w-1/7">Bara</th>
                                <th class="p-3 border w-1/7">Gosa Biqiltuu</th>
                                <th class="p-3 border w-1/7">Biqiltuu</th>
                                <th class="p-3 border w-1/7">Nama Hirmaate</th>
                                <th class="p-3 border w-1/7">Baayina Guyyaa</th>
                                <th class="p-3 border w-1/7">Hanga</th>
                                <th class="p-3 border w-1/7">Ballessi</th>
                            </tr>
                        </thead>
                        <tbody class="w-full">
                            <tr class="row-item bg-white border-t border-gray-200 w-full">
                                <!-- YEAR DROPDOWN -->
                                <td class="p-2 border">
                                    <select name="year[]" class="year-select w-full border rounded px-3 py-2" required>
                                        <option value="">Bara Filadhu</option>
                                    </select>
                                </td>
                                <td class="p-2 border">
                                    <select name="seedlingtype_id[]" class="w-full border rounded px-3 py-2 seedlingtype-select" required>
                                        <option value="">Gosa Filadhu</option>
                                        @foreach($seedlingTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-2 border">
                                    <select name="seedling_id[]" class="w-full border rounded px-3 py-2 seedling-select">
                                        <option value="">Biqiltuu Filadhu</option>
                                    </select>
                                </td>
                                <td class="p-2 border">
                                    <input type="number" step="0.01" name="nama_hirmaate[]" class="w-full border rounded px-3 py-2" required>
                                </td>
                                <td class="p-2 border">
                                    <input type="number" step="0.01" name="baayina_guyyaa[]" class="w-full border rounded px-3 py-2" required>
                                </td>
                                <td class="p-2 border">
                                    <input type="number" step="0.01" name="hanga[]" class="w-full border rounded px-3 py-2" required>
                                </td>
                                <td class="p-2 border text-center">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 font-semibold px-3 py-1 rounded-full hover:bg-red-50">✖</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between mt-6 w-full">
                    <button type="button" id="add-row" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 shadow-md transition-colors duration-200 flex items-center">
                        <span class="mr-2">➕</span> Add More
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-8 py-2 rounded-lg hover:bg-green-700 shadow-md transition-colors duration-200 flex items-center">
                        <span class="mr-2">💾</span> Save All
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function(){

            // 🔹 Fill year dropdowns dynamically
            function populateYearDropdown($dropdown) {
                const currentYear = new Date().getFullYear();
                $dropdown.empty().append('<option value="">Bara Filadhu</option>');
                for (let year = 2011; year <= currentYear; year++) {
                    $dropdown.append('<option value="' + year + '">' + year + '</option>');
                }
            }
            $('.year-select').each(function(){
                populateYearDropdown($(this));
            });

            // 🔹 Seedling type change loads seedlings
            $(document).on('change', '.seedlingtype-select', function(){
                let seedlingtypeId = $(this).val();
                let $seedlingSelect = $(this).closest('tr').find('.seedling-select');

                if(seedlingtypeId) {
                    $.ajax({
                        url: "{{ url('/seedlings/by-type') }}/" + seedlingtypeId,
                        type: 'GET',
                        success: function(data){
                            $seedlingSelect.empty().append('<option value="">Biqiltuu Filadhu</option>');
                            $.each(data, function(key, seedling){
                                $seedlingSelect.append('<option value="'+seedling.id+'">'+seedling.name+'</option>');
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to load seedlings',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else {
                    $seedlingSelect.empty().append('<option value="">Biqiltuu Filadhu</option>');
                }
            });

            // 🔹 Add new row
            $('#add-row').click(function(){
                let newRow = $('#seedling-preparations-table tbody tr:first').clone();
                newRow.find('input').val('');
                newRow.find('select').val('');
                populateYearDropdown(newRow.find('.year-select')); // refresh years
                $('#seedling-preparations-table tbody').append(newRow);
            });

            // 🔹 Remove row
            $(document).on('click', '.remove-row', function(){
                if($('#seedling-preparations-table tbody tr').length > 1){
                    $(this).closest('tr').remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Action not allowed',
                        text: 'At least one row is required.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK',
                        position: 'center'
                    });
                }
            });

            // 🔹 Show success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'OK',
                    position: 'top-end',
                    toast: true,
                    timer: 3000,
                    showConfirmButton: true
                });
            @endif

            // 🔹 Show validation errors
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    html: `<div class="text-left">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>`,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Fix Errors',
                    position: 'center'
                });
            @endif
        });
    </script>

    <style>
        /* Table styling */
        #seedling-preparations-table {
            border-collapse: separate;
            border-spacing: 0;
        }
        #seedling-preparations-table th,
        #seedling-preparations-table td {
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        #seedling-preparations-table th:first-child,
        #seedling-preparations-table td:first-child {
            border-left: 1px solid #e5e7eb;
        }
        #seedling-preparations-table tr:first-child th {
            border-top: 1px solid #e5e7eb;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        #seedling-preparations-table tr:last-child td:first-child {
            border-bottom-left-radius: 0.5rem;
        }
        #seedling-preparations-table tr:last-child td:last-child {
            border-bottom-right-radius: 0.5rem;
        }
        /* Focus styling */
        input, select {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
    </style>
</x-layout>
