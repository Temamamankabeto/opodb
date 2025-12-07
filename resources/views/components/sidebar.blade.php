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
              <!-- Dashboard -->
              <li class="nav-item menu-open">
                  <a href="#" class="nav-link active nav-modern-link">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                          Settings
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{url('admin/naturals')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Natural Activity</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href=" {{url('admin/association-types')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Akaakuu Woldaalee</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{url('admin/institutions')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Dhaabbilee</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{url('admin/educational-participations')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Gochaalee Barnootaa</p>
                          </a>
                      </li>

                        <li class="nav-item">
                          <a href="{{url('admin/student-meal-supplies')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Nyaata Barattootaa</p>
                          </a>
                      </li>
                      
                  </ul>
              </li>

              {{-- <!-- Widgets -->
              <li class="nav-item">
                  <a href="pages/widgets.html" class="nav-link nav-modern-link">
                      <i class="nav-icon fas fa-th"></i>
                      <p>Widgets <span class="right badge badge-danger">New</span></p>
                  </a>
              </li>

              <!-- Layout Options -->
              <li class="nav-item">
                  <a href="#" class="nav-link nav-modern-link">
                      <i class="nav-icon fas fa-copy"></i>
                      <p>
                          Layout Options
                          <i class="fas fa-angle-left right"></i>
                          <span class="badge badge-info right">6</span>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item"><a href="pages/layout/top-nav.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Top Navigation</p></a></li>
                      <li class="nav-item"><a href="pages/layout/top-nav-sidebar.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Top Navigation + Sidebar</p></a></li>
                      <li class="nav-item"><a href="pages/layout/boxed.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Boxed</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-sidebar.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Fixed Sidebar</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-sidebar-custom.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Fixed Sidebar + Custom</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-topnav.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Fixed Navbar</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-footer.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Fixed Footer</p></a></li>
                      <li class="nav-item"><a href="pages/layout/collapsed-sidebar.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Collapsed Sidebar</p></a></li>
                  </ul>
              </li>

              <!-- Charts -->
              <li class="nav-item">
                  <a href="#" class="nav-link nav-modern-link">
                      <i class="nav-icon fas fa-chart-pie"></i>
                      <p>Charts <i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item"><a href="pages/charts/chartjs.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ChartJS</p></a></li>
                      <li class="nav-item"><a href="pages/charts/flot.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Flot</p></a></li>
                      <li class="nav-item"><a href="pages/charts/inline.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Inline</p></a></li>
                      <li class="nav-item"><a href="pages/charts/uplot.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>uPlot</p></a></li>
                  </ul>
              </li> --}}


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
