<x-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Entry Form</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            input[type="text"], input[type="number"] {
                width: 90%;
                padding: 5px;
                box-sizing: border-box;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <body>
    <div class="container">
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false,
                });
            </script>
        @endif

        <h3 class="mb-4">Ijaarsa Faasilitiiwwan Cee’umsa Diinagdee</h3>
        <form action="{{ route('economic_facility.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @php
                $currentYear = date('Y');
                $years = range($currentYear - 20, $currentYear + 10);
            @endphp

            <div class="row mb-4">

                <div class="col-md-6">
                    <label for="plan_year" class="form-label">Karoora Bara (Plan Year)</label>
                    <select name="plan_year" id="plan_year" class="form-select" required>
                        <option value="">-- Select Plan Year --</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ old('plan_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Existing table with input fields below -->
            <table class="table table-bordered">
                <thead class="table-primary">
                <tr>
                    <th>Activity</th>
                    <th>Baay'inaa</th>
                    <th>Kan Maatii</th>
                    <th>Kan Waldaa</th>
                    <th>Kan Hawaasaa</th>
                    <th>Ida'ama</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $activities = [
                        'ljaarsa man-kuusaa oomisha qonnaa',
                        'ljaarsa man-kuusaa galteewwan qonnaa',
                        'ljaarsa man-kuusaa nyaata beyladaa',
                        'Ijaarsa sheedii horsiisa horii aannanii',
                        'ljaarsa sheedii furdis horii',
                        'Ijaarsa sheedii horsiisa lukkuu',
                        'Ijaarsa poondii horsiisa qurxummii',
                        'Ijaarsa sheedii kompoostii raammoo',
                        'Ijaarsa sheedii industirii godoo',
                        'Ijaarsa giddu-gala gabaa oomisha qonnaa',
                    ];
                @endphp

                @foreach($activities as $key => $activity)
                    <tr>
                        <td>{{ $activity }} [Lakkoofsa]</td>
                        <td><input type="number" name="activities[{{ $key }}][amount]" class="form-control"
                                   value="0" min="0" required />
                        </td>
                        <td><input type="number" name="activities[{{ $key }}][family]" class="form-control" value="0"
                                   min="0"/></td>
                        <td><input type="number" name="activities[{{ $key }}][cooperative]" class="form-control"
                                   value="0" min="0"/></td>
                        <td><input type="number" name="activities[{{ $key }}][community]" class="form-control" value="0"
                                   min="0"/></td>
                        <td><input type="number" name="activities[{{ $key }}][total]" class="form-control" value="0"
                                   min="0" readonly/></td>
                    </tr>
                @endforeach

                <tr>
                    <td><strong>Baasii ijaarsa faasilitiiwwan cee'umsa diinagdee [Qarshii]</strong></td>
                    <td colspan="4">
                        <input type="number" name="construction_cost" class="form-control" value="0" min="0"/>
                    </td>
                </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">Save</button>
        </form>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const rows = document.querySelectorAll("tbody tr");

                    rows.forEach(row => {
                        const family = row.querySelector('input[name*="[family]"]');
                        const community = row.querySelector('input[name*="[community]"]');
                        const cooperative = row.querySelector('input[name*="[cooperative]"]');
                        const total = row.querySelector('input[name*="[total]"]');

                        if (family && community && cooperative && total) {
                            const updateTotal = () => {
                                const f = parseInt(family.value) || 0;
                                const c = parseInt(community.value) || 0;
                                const co = parseInt(cooperative.value) || 0;
                                total.value = f + c + co;
                            };

                            family.addEventListener('input', updateTotal);
                            community.addEventListener('input', updateTotal);
                            cooperative.addEventListener('input', updateTotal);
                        }
                    });
                });
            </script>
    </div>

    </body>
    </html>
</x-layout>
