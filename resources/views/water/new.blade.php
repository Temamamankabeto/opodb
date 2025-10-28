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

        <h3 class="mb-4">Karoora fi Raawwii Ijaarsaa fi Suphaa</h3>
        <form action="{{ route('energy.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="baseline_year" class="form-label">Base Line Year</label>
                <select name="baseline_year" id="baseline_year" class="form-select">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected($baselineYear == $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="plan_year" class="form-label">Plan Year</label>
                <select name="plan_year" id="plan_year" class="form-select">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected($planYear == $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <table class="table table-bordered">
                <thead class="table-light text-center">
                <tr>
                    <th rowspan="2">Ijaarsaa fi Suphaa</th>
                    <th colspan="3">Raawwii Bara {{ request('baseline_year') ?? '----' }}</th>
                    <th colspan="3">Karoora Bara {{ request('plan_year') ?? '----' }}</th>
                    <th rowspan="2">Baasii [Qarshii]</th>
                </tr>
                <tr>
                    <th>Maatii</th><th>Hawaasaa</th><th>Ida'ama</th>
                    <th>Maatii</th><th>Hawaasaa</th><th>Ida'ama</th>
                </tr>
                </thead>
                <tbody>
                @foreach($activities as $activity)
                    <tr class="activity-row">
                        <td>{{ $activity }}</td>

                        <td><input type="number" name="data[{{ $activity }}][raawwii_maatii]" class="form-control raawwii_maatii" /></td>
                        <td><input type="number" name="data[{{ $activity }}][raawwii_hawaasaa]" class="form-control raawwii_hawaasaa" /></td>
                        <td><input type="number" name="data[{{ $activity }}][raawwii_idaama]" class="form-control raawwii_idaama" readonly /></td>

                        <td><input type="number" name="data[{{ $activity }}][karoora_maatii]" class="form-control karoora_maatii" /></td>
                        <td><input type="number" name="data[{{ $activity }}][karoora_hawaasaa]" class="form-control karoora_hawaasaa" /></td>
                        <td><input type="number" name="data[{{ $activity }}][karoora_idaama]" class="form-control karoora_idaama" readonly /></td>

                        <td><input type="number" name="data[{{ $activity }}][baasii]" class="form-control" step="0.01" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success mt-3">Submit</button>
        </form>
        <script>
            function calculateSum(row) {
                let raawwiiMaatii = parseFloat(row.querySelector('.raawwii_maatii').value) || 0;
                let raawwiiHawaasaa = parseFloat(row.querySelector('.raawwii_hawaasaa').value) || 0;
                row.querySelector('.raawwii_idaama').value = raawwiiMaatii + raawwiiHawaasaa;

                let karooraMaatii = parseFloat(row.querySelector('.karoora_maatii').value) || 0;
                let karooraHawaasaa = parseFloat(row.querySelector('.karoora_hawaasaa').value) || 0;
                row.querySelector('.karoora_idaama').value = karooraMaatii + karooraHawaasaa;
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('tr.activity-row').forEach(function (row) {
                    row.querySelectorAll('input').forEach(function (input) {
                        input.addEventListener('input', function () {
                            calculateSum(row);
                        });
                    });
                });
            });
        </script>

    </div>

    <script>
        document.getElementById('dataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Here you would typically send the data to your server
            console.log(data);
            alert('Form data logged to console (check developer tools)');
        });
    </script>
    </body>
    </html>
</x-layout>
