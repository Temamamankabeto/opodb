<!-- Dashboard -->
<li class="nav-item">
    <a href="{{ url('zone/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>{{ __('Dashboard') }}</p>
    </a>
</li>

<hr>

 

 
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
                        <a href="{{ url('zones/gabaasa-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Midhaan') }}</p>
                        </a>

                    </li>
                    <li class="nav-item">
                        <a href="{{ url('zones/gabaasa/zone/fertilizer') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Calla Guddiftuu') }}</p>
                        </a>

                    </li>
                     <li class="nav-item">
                        <a href="{{ url('zones/zone-mechanization-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Mechinization') }}</p>
                        </a>

                    </li>
                      <li class="nav-item">
                        <a href="{{ url('zones/compost-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Gabbina Biyyoo') }}</p>
                        </a>

                    </li>
                    
                    
                   
                   
            </ul>
            </li>
        </ul>
</li>
 
