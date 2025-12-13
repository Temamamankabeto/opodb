<!-- Main Sidebar Container -->
<aside class="main-sidebar modern-sidebar resizable-sidebar elevation-4 sidebar-scrollable">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link brand-custom">
    <img src="{{ asset('assets/uploads/logo.png') }}" 
         alt="{{ __('Logo') }}" 
         class="brand-image img-circle elevation-3"
         style="opacity: .9;">
    <span class="brand-text font-weight-light"> </span>
  </a>

  <!-- Sidebar with scrollable wrapper -->
  <div class="sidebar">
    <!-- Scrollable content wrapper -->
    <div class="sidebar-scroll-container">
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
                          {{ __('Settings') }}
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">

                     <li class="nav-item">
                          <a href="{{url('admin/languages')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Languages') }}</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{url('admin/naturals')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Natural Activity') }}</p>
                          </a>
                      </li>
                     
                      
                      <li class="nav-item">
                          <a href=" {{url('admin/association-types')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Akaakuu Woldaalee') }}</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{url('admin/institutions')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Institutions') }}</p>
                          </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{url('admin/educational-participations')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Educational Participations') }}</p>
                          </a>
                      </li>

                        <li class="nav-item">
                          <a href="{{url('admin/student-meal-supplies')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Student Meal Supplies') }}</p>
                          </a>
                      </li>
                       <li class="nav-item">
                          <a href="{{url('admin/health')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Health') }}</p>
                          </a>
                      </li>

 <li class="nav-item">
                          <a href="{{url('admin/cultural-revival')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Cultural Revival') }}</p>
                          </a>
                      </li>

                       <li class="nav-item">
                          <a href="{{url('admin/civil-certificates')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Civil Certificates') }}</p>
                          </a>
                      </li>

                        <li class="nav-item">
                          <a href="{{url('admin/government-properties')}}" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Government Properties') }}</p>
                          </a>
                      </li>
                       
                          <li class="nav-item">
                              <a href="{{url('admin/job-opportunities')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Job Opportunities') }}</p>
                              </a>  
                            </li>


  

                     
                      
                      
                      
                  </ul>
              </li>

              {{-- <!-- Widgets -->
              <li class="nav-item">
                  <a href="pages/widgets.html" class="nav-link nav-modern-link">
                      <i class="nav-icon fas fa-th"></i>
                      <p>{{ __('Widgets') }} <span class="right badge badge-danger">{{ __('New') }}</span></p>
                  </a>
              </li>

              <!-- Layout Options -->
              <li class="nav-item">
                  <a href="#" class="nav-link nav-modern-link">
                      <i class="nav-icon fas fa-copy"></i>
                      <p>
                          {{ __('Layout Options') }}
                          <i class="fas fa-angle-left right"></i>
                          <span class="badge badge-info right">6</span>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item"><a href="pages/layout/top-nav.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Top Navigation') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/top-nav-sidebar.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Top Navigation + Sidebar') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/boxed.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Boxed') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-sidebar.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Fixed Sidebar') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-sidebar-custom.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Fixed Sidebar + Custom') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-topnav.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Fixed Navbar') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/fixed-footer.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Fixed Footer') }}</p></a></li>
                      <li class="nav-item"><a href="pages/layout/collapsed-sidebar.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Collapsed Sidebar') }}</p></a></li>
                  </ul>
              </li>

              <!-- Charts -->
              <li class="nav-item">
                  <a href="#" class="nav-link nav-modern-link">
                      <i class="nav-icon fas fa-chart-pie"></i>
                      <p>{{ __('Charts') }} <i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item"><a href="pages/charts/chartjs.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('ChartJS') }}</p></a></li>
                      <li class="nav-item"><a href="pages/charts/flot.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Flot') }}</p></a></li>
                      <li class="nav-item"><a href="pages/charts/inline.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('Inline') }}</p></a></li>
                      <li class="nav-item"><a href="pages/charts/uplot.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{ __('uPlot') }}</p></a></li>
                  </ul>
              </li> --}}


            @endif

            @if (Auth::user()->role_id == 2)
                <li class="nav-item">
                    <a href="#" class="nav-link nav-modern-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p> {{ __('Kebele User Menu') }} <span class="right badge badge-danger">{{ __('New') }}</span></p>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role_id == 3)
                <li class="nav-item">
                    <a href="#" class="nav-link nav-modern-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p> {{ __('Resident User Menu') }} <span class="right badge badge-danger">{{ __('New') }}</span></p>
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
                        <p> {{ __('Auditor User Menu') }} <span class="right badge badge-danger">{{ __('New') }}</span></p>
                    </a>
                </li>
            @endif





          </ul>
      </nav>
  </div>
</aside>