<!-- Main Sidebar Container -->
<aside class="main-sidebar modern-sidebar resizable-sidebar elevation-4">


<!-- Brand Logo -->
<a href="index3.html" class="brand-link brand-custom">
    <img src="{{ asset('assets/uploads/logo.png') }}" 
         alt="Logo" 
         class="brand-image img-circle elevation-3"
         style="opacity: .9;">
    <span class="brand-text font-weight-light"> </span>
</a>


  <!-- Sidebar -->
  <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-3">
          <ul class="nav nav-pills nav-sidebar flex-column sidebar-modern-menu" data-widget="treeview" role="menu" data-accordion="false">
<!-- super admin  -->
            @if (Auth::user()->role_id == 1)
              


            @endif

            @if (Auth::user()->role_id == 2)
                <li class="nav-item">
                    <a href="#" class="nav-link nav-modern-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p> Kebele User Menu <span class="right badge badge-danger">New</span></p>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role_id == 3)
                <li class="nav-item">
                    <a href="#" class="nav-link nav-modern-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p> Resident User Menu <span class="right badge badge-danger">New</span></p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role_id == 4)
               @include('components.kebelesidebar')
            @endif
            @if (Auth::user()->role_id == 5)
                <li class="nav-item">
                    <a href="#" class="nav-link nav-modern-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p> Auditor User Menu <span class="right badge badge-danger">New</span></p>
                    </a>
                </li>
            @endif





          </ul>
      </nav>
  </div>
</aside>
