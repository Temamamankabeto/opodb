<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg rounded-4">
                    <div class="card-header bg-primary text-white text-center rounded-top-4">
                        <h4 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Change Password</h4>
                    </div>
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                   @php
    $rolePrefix = match (Auth::user()->role_id) {
        1 => 'admin',
        2 => 'zones',
        3 => 'woredas',
        4 => 'kebele',
        default => 'user'
    };
@endphp

<form method="POST" action="{{ url($rolePrefix . '/change-password') }}">
    @csrf
                            <div class="mb-3">
                                <label for="old_password" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Old Password
                                </label>
                                <input type="password" name="old_password" class="form-control rounded-3" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone-fill me-1"></i>Phone Number
                                </label>
                                <input type="text" name="phone" class="form-control rounded-3" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    <i class="bi bi-key-fill me-1"></i>New Password
                                </label>
                                <input type="password" name="new_password" class="form-control rounded-3" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">
                                    <i class="bi bi-check-circle-fill me-1"></i>Confirm New Password
                                </label>
                                <input type="password" name="new_password_confirmation" class="form-control rounded-3" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-3">
                                    <i class="bi bi-arrow-repeat me-1"></i>Change Password
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
