<x-layout>
    <div class="container py-5">
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
        </style>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Create New User</h4>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="form-control">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="form-control">
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control">
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <!-- Zone -->
                        <div class="col-md-6 mb-3">
                            <label for="zone_id" class="form-label">Zone</label>
                            <select name="zone_id" id="zone_id" class="form-select">
                                <option value="">Select Zone</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Woreda -->
                        <div class="col-md-6 mb-3">
                            <label for="woreda_id" class="form-label">Woreda</label>
                            <select name="woreda_id" id="woreda_id" class="form-select">
                                <option value="">Select Woreda</option>
                            </select>
                        </div>

                        <!-- Kebele -->
                        <div class="col-md-6 mb-3">
                            <label for="kebele_id" class="form-label">Kebele</label>
                            <select name="kebele_id" id="kebele_id" class="form-select">
                                <option value="">Select Kebele</option>
                            </select>
                        </div>
                        <!-- Role -->
                        <div class="col-md-6">
                            <label for="role_id" class="form-label">Role</label>
                            <select name="role_id" id="role_id" class="form-select">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" required class="form-control">
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i> Create User
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {

            // When Zone changes, load Woredas
            $('#zone_id').on('change', function () {
                var zoneId = $(this).val();
                $('#woreda_id').html('<option value="">Loading...</option>');
                $('#kebele_id').html('<option value="">Select Kebele</option>');

                if (zoneId) {
                    $.ajax({
                        url: '/get-woredas/' + zoneId,
                        type: 'GET',
                        success: function (data) {
                            $('#woreda_id').empty().append('<option value="">Select Woreda</option>');
                            $.each(data, function (key, woreda) {
                                $('#woreda_id').append('<option value="' + woreda.id + '">' + woreda.name + '</option>');
                            });
                        }
                    });
                }
            });

            // When Woreda changes, load Kebeles
            $('#woreda_id').on('change', function () {
                var woredaId = $(this).val();
                $('#kebele_id').html('<option value="">Loading...</option>');

                if (woredaId) {
                    $.ajax({
                        url: '/get-kebeles/' + woredaId,
                        type: 'GET',
                        success: function (data) {
                            $('#kebele_id').empty().append('<option value="">Select Kebele</option>');
                            $.each(data, function (key, kebele) {
                                $('#kebele_id').append('<option value="' + kebele.id + '">' + kebele.name + '</option>');
                            });
                        }
                    });
                }
            });

        });
    </script>
</x-layout>
