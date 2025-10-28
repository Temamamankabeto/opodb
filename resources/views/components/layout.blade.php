<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
       <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>OPO </title>
    <!-- Include jQuery and Bootstrap Datepicker -->
    <link rel="stylesheet"
    
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('uploads/logo.png')}}" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    {{-- <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">--}}
    {{-- <link rel="manifest" href="{{ asset('/manifest.json') }}">--}}

</head>

<body class="with-welcome-text">
    <div class="container-scroller">
        <div class="row p-0 m-0 proBanner" id="proBanner">
            <div class="col-md-12 p-0 m-0">
                <div class="card-body card-body-padding px-3 d-flex align-items-center justify-content-between">
                    <div class="ps-lg-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="#" target="_blank" class="btn me-2 buy-now-btn border-0"> </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="#"><i class="ti-home me-3 text-white"></i></a>
                        <button id="bannerClose" class="btn border-0 p-0">
                            <i class="ti-close text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row"
            style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize"
                        style="color: white;">
                        <span class="icon-menu" style="font-size: 1.5rem;"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="index.html" style="display: flex; align-items: center;">
                        <img src="{{ asset('img/logo.png')}}" alt="logo" style="height: 40px; margin-right: 10px;" />
                        <span style="color: black; font-size: 1.5rem; font-weight: 700; letter-spacing: 1px;">OPO</span>
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="index.html">
                        <img src="{{ asset('assets/images/logo-mini.svg')}}" alt="logo" style="height: 30px;" />
                    </a>
                </div>
            </div>

            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text"
                            style="color: black; font-size: 1.2rem; margin-bottom: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); text-align: center;">
                            Waajjira Perezedaantii Bulchiinsa MNO tti Too'a'nnoo Karooraa fi Raawwii
                            <span class="fw-bold" style="color: #ffd700;"></span>
                        </h1>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                        <a class="nav-link d-flex align-items-center" id="UserDropdown" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false" style="padding: 0.5rem 1rem;">


                            @php
                            $profileImage = auth()->user()->profile_picture
                            ? asset('uploads/profile_pictures/' . auth()->user()->profile_picture)
                            : asset('uploads/defualt.png'); // default image
                            @endphp
                            <img class="img-md rounded-circle mb-2"
                                src="{{ $profileImage }}"
                                alt="Profile image"
                                style="width: 50px; height: 40px; border: 3px solid white;">
                            <span style="color: black; margin-left: 8px; font-weight: 500;">{{Auth::user()->name}}</span>
                            <i class="mdi mdi-chevron-down text-white ms-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown py-0" aria-labelledby="UserDropdown"
                            style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden;">
                            <div class="dropdown-header text-center py-3"
                                style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                                @php
                                $profileImage = auth()->user()->profile_picture
                                ? asset('uploads/profile_pictures/' . auth()->user()->profile_picture)
                                : asset('uploads/defualt.png'); // default image
                                @endphp

                                <img class="img-md rounded-circle mb-2"
                                    src="{{ $profileImage }}"
                                    alt="Profile image"
                                    style="width: 80px; height: 80px; border: 3px solid white;">
                                <p class="mb-1 mt-2 fw-semibold text-black">{{Auth::user()->name}}</p>
                                <p class="fw-light text-black mb-0" style="opacity: 0.8;">{{Auth::user()->phone}}</p>
                            </div>
                            <div class="px-3 py-2">
                                <a class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                    <i class="mdi mdi-account-outline me-2" style="color: #2a5298; font-size: 1.2rem;"></i>
                                    <span>{{Auth::user()->role?->name}}</span>
                                </a>
                                @php
                                $currentRole=Auth::user()->role_id;
                                // echo $currentRole;
                                if ($currentRole === 1) { @endphp
                                <a href="{{route('admin.users.profile',Auth::user()->uuid)}}"
                                    class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                    <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span>Profile</span>
                                </a> @php
                                } elseif ($currentRole === 2) {
                                @endphp
                                <a href="{{route('zone.profile.users',Auth::user()->uuid)}}"
                                    class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                    <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                    <span>Profile</span>
                                    @php }
                                    elseif ($currentRole === 3) {
                                    @endphp
                                    <a href="{{route('woreda.profile.users',Auth::user()->uuid)}}"
                                        class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                        <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                        <span>Profile</span>
                                        @php }
                                        elseif ($currentRole === 4) { @endphp
                                        <a href="{{route('kebele.profile.users',Auth::user()->uuid)}}"
                                            class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                            <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                            <span>Profile</span>
                                            @php }
                                            @endphp


                                            {{-- security check --}}
                                            @php
                                            if ($currentRole === 1)
                                            {
                                            @endphp
                                            <a href="{{route('admin.users.password',Auth::user()->uuid)}}"
                                                class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                                <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                                <span>Security</span>
                                            </a>
                                            @php }
                                            elseif ($currentRole === 2) {
                                            @endphp
                                            <a href="{{route('zone.users.password',Auth::user()->uuid)}}"
                                                class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                                <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                                <span>Security</span>
                                                @php } elseif ($currentRole === 3) { @endphp
                                                <a href="{{route('woreda.users.password',Auth::user()->uuid)}}"
                                                    class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                                    <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                                    <span>Security</span>
                                                    @php } elseif ($currentRole === 4) { @endphp
                                                    <a href="{{route('kebele.users.password',Auth::user()->uuid)}}"
                                                        class="dropdown-item d-flex align-items-center py-2" style="border-radius: 5px;">
                                                        <i class="mdi mdi-lock me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                                        <span>Security</span>
                                                        @php }
                                                        @endphp


                                                        <a href="{{route('logout')}}" class="dropdown-item d-flex align-items-center py-2"
                                                            style="border-radius: 5px;">
                                                            <i class="mdi mdi-power me-2" style="color: #e74c3c; font-size: 1.2rem;"></i>
                                                            <span>Sign Out</span>
                                                        </a>
                            </div>
                        </div>
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-bs-toggle="offcanvas" style="color: black;">
                    <span class="mdi mdi-menu" style="font-size: 1.8rem;"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('components.sidebar')
            <!-- partial -->

            <div class="main-panel">
                <div class="content-wrapper">
                    {{$slot}}
                </div>
            </div>

            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js')}}"></script>
    <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{ asset('assets/js/template.js')}}"></script>
    <script src="{{ asset('assets/js/settings.js')}}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{ asset('assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ asset('assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dashboard.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.all.min.js"></script>

    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        } <
        !-- < script src = "assets/js/Chart.roundedBarCharts.js" >
    </script>
    -->
    <!-- End custom js for this page-->
</body>

</html>