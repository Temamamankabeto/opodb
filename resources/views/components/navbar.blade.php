<nav class="navbar navbar-expand-lg navbar-dark fixed-top"
    style="background: linear-gradient(135deg, #9ab8f0 0%, #82abf3 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" height="40" class="me-2">
            <span class="fw-bold fs-4 text-black">{{ __('OPO') }}</span>
        </a>


        <!-- Mobile Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="mdi mdi-menu fs-4 text-white"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Centered Welcome Text (desktop only) -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-none d-lg-flex align-items-center">
                <li class="nav-item text-center">
                    <h1 class="welcome-text fs-6 text-black fw-semibold mb-0"
                        style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                        {{ __('Planning and Performance Monitoring at the Office of the President of MNO Administration') }}

                    </h1>
                </li>
            </ul>

            <!-- Right Side Dropdowns -->
            <ul class="navbar-nav ms-auto align-items-center">
                @php
                    $user = Auth::user();
                    $profileImage = $user->profile_picture
                        ? asset('uploads/profile_pictures/' . $user->profile_picture)
                        : asset('uploads/defualt.png');
                    $roleId = $user->role_id;
                    $profileRoutes = [
                        1 => 'admin.users.profile',
                        2 => 'zone.profile.users',
                        3 => 'woreda.profile.users',
                        4 => 'kebele.profile.users',
                    ];
                    $passwordRoutes = [
                        1 => 'admin.users.password',
                        2 => 'zone.users.password',
                        3 => 'woreda.users.password',
                        4 => 'kebele.users.password',
                    ];
                @endphp


                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle text-black fw-medium" href="#" id="languageDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-translate me-1"></i>
                        {{ strtoupper(session('locale', 'EN')) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        @foreach (config('app.languages') as $langCode => $langName)
                            <li><a class="dropdown-item"
                                    href="{{ route('set.language', ['language' => $langCode]) }}"><span>{{ $langName }}</span>
                                    @if (app()->getLocale() == $langCode)
                                        <i class="bx bx-check text-primary ms-auto"></i>
                                    @endif
                                </a></li>
                        @endforeach
                    </ul>



                </li>


                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle me-2"
                            style="width: 40px; height: 40px; border: 2px solid white;">
                        <span class="text-black fw-medium">{{ $user->name }}</span>
                        <i class="mdi mdi-chevron-down text-white ms-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow rounded-3" aria-labelledby="userDropdown">
                        <li class="dropdown-header text-center py-3"
                            style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                            <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle mb-2"
                                style="width: 80px; height: 80px; border: 3px solid white;">
                            <p class="fw-semibold text-black mb-1">{{ $user->name }}</p>
                            <p class="text-black mb-0" style="opacity:0.8;">{{ $user->phone }}</p>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="#">
                                <i class="mdi mdi-account-outline me-2 text-primary fs-5"></i>
                                <span>{{ $user->role?->name }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route($profileRoutes[$roleId], $user->uuid) }}"
                                class="dropdown-item d-flex align-items-center py-2">
                                <i class="mdi mdi-lock me-2 text-danger fs-5"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route($passwordRoutes[$roleId], $user->uuid) }}"
                                class="dropdown-item d-flex align-items-center py-2">
                                <i class="mdi mdi-lock me-2 text-danger fs-5"></i>
                                <span>Security</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item d-flex align-items-center py-2">
                                <i class="mdi mdi-power me-2 text-danger fs-5"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
