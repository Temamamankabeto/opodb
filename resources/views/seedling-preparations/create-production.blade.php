<x-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class=" ">🌱 Dhaabbii Biqiltuu</h1>

        <div class="border border-primary rounded-lg shadow-md p-6 bg-white">

            <form method="POST" action="{{ route('seedling-production.store') }}" class="space-y-4">
                @csrf

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden" id="seedling-preparations-table">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="p-3 border text-left">Bara</th>
                                <th class="p-3 border text-left">Gosa Biqiltuu</th>
                                <th class="p-3 border text-left">Biqiltuu</th>
                                <th class="p-3 border text-left">Nama Hirmaate</th>
                                <th class="p-3 border text-left">Baayina Guyyaa</th>
                                <th class="p-3 border text-left">Hanga</th>
                                <th class="p-3 border text-left">Bal'ina</th>
                                <th class="p-3 border text-left">Gocha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-t border-gray-200">
                                <td class="p-3 border">
                                    <select name="year[]" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select Year</option>
                                        @for($y = now()->year; $y >= 2011; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="p-3 border">
                                    <select name="seedlingtype_id[]" class="w-full border rounded px-3 py-2 seedlingtype-select focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select Seedling Type</option>
                                        @foreach($seedlingTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-3 border">
                                    <select name="seedling_id[]" class="w-full border rounded px-3 py-2 seedling-select focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select Seedling</option>
                                    </select>
                                </td>
                                <td class="p-3 border">
                                    <input type="number" step="0.01" name="nama_hirmaate[]" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </td>
                                <td class="p-3 border">
                                    <input type="number" step="0.01" name="baayina_guyyaa[]" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </td>
                                <td class="p-3 border">
                                    <input type="number" step="0.01" name="hanga[]" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </td>
                                <td class="p-3 border">
                                    <input type="number" step="0.01" name="area[]" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </td>
                                <td class="p-3 border text-center">
                                    <button type="button" class="remove-row text-red-600 hover:text-red-800 font-bold px-3 py-1 rounded-full hover:bg-red-50 transition-colors">
                                        ✖
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" id="add-row" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        ➕ Add More
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        💾 Save All
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function(){
            // fetch seedlings when seedling type changes
            $(document).on('change', '.seedlingtype-select', function(){
                let seedlingtypeId = $(this).val();
                let $seedlingSelect = $(this).closest('tr').find('.seedling-select');

                if(seedlingtypeId) {
                    $.ajax({
                        url: "{{ url('/seedlings/by-type') }}/" + seedlingtypeId,
                        type: 'GET',
                        success: function(data){
                            $seedlingSelect.empty().append('<option value="">Select Seedling</option>');
                            $.each(data, function(key, seedling){
                                $seedlingSelect.append('<option value="'+seedling.id+'">'+seedling.name+'</option>');
                            });
                        },
                        error: function() {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load seedlings' });
                        }
                    });
                } else {
                    $seedlingSelect.empty().append('<option value="">Select Seedling</option>');
                }
            });

            // Add new row (clone first row)
            $('#add-row').click(function(){
                let newRow = $('#seedling-preparations-table tbody tr:first').clone();
                newRow.find('input').val('');
                newRow.find('select').val('');
                $('#seedling-preparations-table tbody').append(newRow);
            });

            // Remove row
            $(document).on('click', '.remove-row', function(){
                if($('#seedling-preparations-table tbody tr').length > 1){
                    $(this).closest('tr').remove();
                } else {
                    Swal.fire({ icon: 'warning', title: 'Action not allowed', text: 'At least one row is required.' });
                }
            });

            // Show SweetAlert for session success
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', toast: true, timer: 3000, position: 'top-end' });
            @endif

            // Show SweetAlert for validation errors
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    html: `<ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`
                });
            @endif
        });
    </script>
</x-layout>
