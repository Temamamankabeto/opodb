<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Language Switcher -->
        <li class="nav-item dropdown mr-2">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fas fa-language"></i> 
                {{ strtoupper(app()->getLocale()) }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach (config('app.languages') as $langCode => $langName)
                    <a class="dropdown-item" href="{{ route('set.language', ['language' => $langCode]) }}">
                        {{ $langName }}
                        @if (app()->getLocale() === $langCode)
                            <i class="fas fa-check text-primary float-right"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </li>

        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">No any Notifications</span>
            </div>
        </li>

        <!-- User Dropdown -->
        @if ($user)
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                     
                    <span class="d-none d-md-inline">{{ $user->name }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    <!-- User info -->
                    <li class="user-header bg-primary">
                        <img src="{{ $profileImage }}" 
                             class="img-circle elevation-2" 
                             alt="User Image">
                        <p>
                            {{ $user->name }}
                            <small>{{ $user->phone }}</small>
                        </p>
                    </li>

                    <!-- Menu Body -->
                    <li class="user-body px-3 py-2">
                        <a class="btn btn-default btn-flat btn-block">
                            <i class="fas fa-user mr-2"></i> {{ $user->kebele->name ?? '' }} {{ $user->role?->name }}
                        </a>
                    </li>

                    <!-- Menu Footer-->
                    <li class="user-footer">
                        @if ($profileRoute)
                            <a href="{{ route($profileRoute, $user->uuid) }}" class="btn btn-default btn-flat">
                                <i class="fas fa-id-card mr-2"></i> Profile
                            </a>
                        @endif

                        @if ($passwordRoute)
                            <a href="{{ route($passwordRoute, $user->uuid) }}" class="btn btn-default btn-flat">
                                <i class="fas fa-lock mr-2 text-danger"></i> Security
                            </a>
                        @endif

                        <a href="{{ route('logout') }}" class="btn btn-danger btn-flat float-right">
                            <i class="fas fa-power-off mr-2"></i> Sign Out
                        </a>
                    </li>

                </ul>
            </li>
        @endif

        <!-- Control Sidebar -->
        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li> --}}

    </ul>
</nav>
<!-- /.navbar -->
