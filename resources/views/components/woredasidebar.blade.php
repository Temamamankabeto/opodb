<!-- Dashboard -->
<li class="nav-item">
    <a href="{{ url('woreda/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>{{ __('Dashboard') }}</p>
    </a>
</li>

<hr>

{{-- woredas/kebele/users --}}
<li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                {{ __('User Management ') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">

            <!-- Misooma Midhaanii (Crop Development) -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-seedling nav-icon"></i>
                    <p>
                        {{ __('Kebele Users') }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">

                  
                     
                    <li class="nav-item">
                        <a href="{{ url('woredas/kebele/users') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('User List') }}</p>
                        </a>
            </ul>
            </li>
        </ul>
</li>

 
 {{-- gabaasa woggaa  --}}
<li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                {{ __('Report ') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">

            <!-- Misooma Midhaanii (Crop Development) -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-seedling nav-icon"></i>
                    <p>
                        {{ __('Crop Development') }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="{{ url('woredas/gabaasa-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Crop') }}</p>
                        </a>
                    </li>
                     
                    <li class="nav-item">
                        <a href="{{ url('woredas/fertilizer-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Fertilizer') }}</p>
                        </a>

                    </li>

                     <li class="nav-item">
                        <a href="{{ url('woredas/mechanization-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Mechinization') }}</p>
                        </a>

                    </li>
                       <li class="nav-item">
                        <a href="{{ url('woredas/compost-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Soil Development') }}</p>
                        </a>
                    </li>
                 <li class="nav-item">
                        <a href="{{ url('woredas/gabaasa_torbanii/employee/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Employee Plans') }}</p>
                        </a>
                    </li>

                        
            </ul>
            </li>
        </ul>
</li>
 
