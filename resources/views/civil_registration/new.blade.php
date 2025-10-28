<x-layout>
    <title>Civil Records Registration</title>

    <style>
        .add-row-btn {
            background-color: var(--accent-color);
            color: var(--white);
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-row-btn:hover {
            background-color: #c0392b;
        }

        .remove-row-btn {
            background-color: #e67e22;
            color: var(--white);
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-row-btn:hover {
            background-color: #d35400;
        }

        .form-container {
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            max-width: 1000px;
            margin: auto;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .submit-btn, .reset-btn {
            padding: 10px 16px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn {
            background-color: #2ecc71;
            color: white;
        }

        .submit-btn:hover {
            background-color: #27ae60;
        }

        .reset-btn {
            background-color: #bdc3c7;
            color: black;
        }

        .reset-btn:hover {
            background-color: #95a5a6;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Civil Records Registration</h2>
            </div>
            <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form id="data-form" method="POST" action="{{ url('civil-registration-plans/save') }}">
                @csrf

                <table id="records-table">
                    <thead>
                    <tr>
                        <th>Year (E.C.)</th>
                        <th>Birth</th>
                        <th>Marriage</th>
                        <th>Adoption</th>
                        <th>Divorce</th>
                        <th>Death</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="record-rows">
                    <tr>
                        <td><input type="number" name="year[]" class="form-control year-input" required
                                   style="width: 100px"></td>
                        <td><input type="number" name="birth_cert[]" placeholder="Dhaloota" required></td>
                        <td><input type="number" name="marriage_cert[]" placeholder="Gaa'elaa" required></td>
                        <td><input type="number" name="adoption[]" placeholder="Guddifachaa" required></td>
                        <td><input type="number" name="divorce_cert[]" placeholder="Hiikkaa Gaa'elaa" required></td>
                        <td><input type="number" name="death_cert[]" placeholder="Du'aa" required></td>
                        <td>
                            <button type="button" class="remove-row-btn">Remove</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="button-group">
                    <button type="button" class="add-row-btn" id="add-row">+ Add Row</button>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-btn">Submit Data</button>
                    <button type="reset" class="reset-btn">Reset Form</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Success message
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
        @endif

        // Validation error message
        @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += `{{ $error }}\n`;
        @endforeach

        Swal.fire({
            title: 'Error!',
            text: errorMessages,
            icon: 'error',
            confirmButtonText: 'OK'
        });
        @endif
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('record-rows');
            const addRowBtn = document.getElementById('add-row');

            // Calculate Ethiopian year
            function getEthiopianYear() {
                const today = new Date();
                const gYear = today.getFullYear();
                const gMonth = today.getMonth() + 1;
                const gDate = today.getDate();

                let ethYear = gYear - 8;
                if (gMonth < 9 || (gMonth === 9 && gDate < 11)) {
                    ethYear -= 1;
                }
                return ethYear;
            }

            const defaultEthYear = getEthiopianYear();

            // Set default year in the first row
            document.querySelector('.year-input').value = defaultEthYear;

            // Add row
            addRowBtn.addEventListener('click', function () {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="number" name="year[]" value="${defaultEthYear}" class="form-control" required></td>
                    <td><input type="number" name="birth_cert[]" placeholder="Dhaloota" required></td>
                    <td><input type="number" name="marriage_cert[]" placeholder="Gaa'eela" required></td>
                    <td><input type="number" name="adoption[]" placeholder="Guddifachaa" required></td>
                    <td><input type="number" name="divorce_cert[]" placeholder="Hiikkaa" required></td>
                    <td><input type="number" name="death_cert[]" placeholder="Du'a" required></td>
                    <td><button type="button" class="remove-row-btn">Remove</button></td>
                `;
                tableBody.appendChild(newRow);
            });

            // Remove row
            tableBody.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-row-btn')) {
                    const row = e.target.closest('tr');
                    row.remove();
                }
            });
        });
    </script>
</x-layout>
