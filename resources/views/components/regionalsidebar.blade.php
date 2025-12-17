<!-- Dashboard -->
<li class="nav-item">
    <a href="{{ url('zone/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>{{ __('Dashboard') }}</p>
    </a>
</li>
<hr>


<li class="nav-item  ">
    <a href="#" class="nav-link ">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            {{ __('User Management') }}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ url('admin/users') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Users') }}</p>
            </a>
        </li>
         
    </ul>
</li>

 {{-- gabaasa woggaa  --}}
<li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                {{ __('Gabaasa ') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">

            <!-- Misooma Midhaanii (Crop Development) -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-seedling nav-icon"></i>
                    <p>
                        {{ __('Misooma Midhaanii') }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">  
                    <li class="nav-item">
                        <a href="{{ url('admin/gabaasa-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Midhaan') }}</p>
                        </a>
                        </li>
  <li class="nav-item">
                        <a href="{{ url('admin/gabaasa/region/fertilizer') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Calla Guddiftuu') }}</p>
                        </a>
                        </li>

                        <li class="nav-item">
                        <a href="{{ url('admin/regional-mechanization-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Mechinization') }}</p>
                        </a>
                        </li>

                         <li class="nav-item">
                        <a href="{{ url('admin/compost-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Gabbina Biyyoo') }}</p>
                        </a>
                        </li>
                        
                       
                        
            </ul>
            </li>
        </ul>
</li>
<!-- Dashboard -->
<li class="nav-item  ">
    <a href="#" class="nav-link ">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            {{ __('Settings') }}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ url('admin/languages') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Languages') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('admin/naturals') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Natural Activity') }}</p>
            </a>
        </li>


        <li class="nav-item">
            <a href=" {{ url('admin/association-types') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Akaakuu Woldaalee') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('admin/institutions') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Institutions') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/educational-participations') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Educational Participations') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/student-meal-supplies') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Student Meal Supplies') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('admin/health') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Health') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/cultural-revival') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Cultural Revival') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/civil-certificates') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Civil Certificates') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/government-properties') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Government Properties') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('admin/job-opportunities') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Job Opportunities') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('fertilizers/type') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Job Opportunities') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('fertilizers') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Job Opportunities') }}</p>
            </a>
        </li>











    </ul>
</li>



