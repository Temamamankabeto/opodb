<!-- Dashboard -->
<li class="nav-item">
    <a href="{{ url('kebele/dashboard') }}" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>{{ __('Dashboard') }}</p>
    </a>
</li>

<hr>

<!-- Karrora Waggaa (Annual Plan) -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>
            {{ __('Yearly Plan') }}
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
                    <a href="{{ url('sector/crop-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Crop') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/crop-fertilizer') }}" class="nav-link">
                        <i class="fas fa-flask nav-icon"></i>
                        <p>{{ __('Fertilizer') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/mechanizations') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Mechinization') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/gabbina-biyyee') }}" class="nav-link">
                        <i class="fas fa-mountain nav-icon"></i>
                        <p>{{ __('Soil Development') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/seedling-preparations') }}" class="nav-link">
                        <i class="fas fa-tree nav-icon"></i>
                        <p>{{ __('Seedling') }}</p>
                    </a>
                </li>

            </ul>
        </li>


         <!-- Misooma Beeyladaa  -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Livestock Development') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/livestock-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Livestock') }}</p>
                    </a>
                </li>

                 

            </ul>
        </li>


         <!-- Qabeeny uumamaa  -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Natural Resources') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/natural-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Natural resources') }}</p>
                    </a>
                </li>

                

            </ul>
        </li>


{{-- Baballina Woldaalee  --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Cooperative Societies') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/association-expansion-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Expansion of Churches') }}</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ url('sector/crop-fertilizer') }}" class="nav-link">
                        <i class="fas fa-flask nav-icon"></i>
                        <p>{{ __('Calla Guddiftuu') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/mechanizations') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Makaanaayizeeshinii') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/gabbina-biyyee') }}" class="nav-link">
                        <i class="fas fa-mountain nav-icon"></i>
                        <p>{{ __('Gabbina Biyyoo') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/seedling-preparations') }}" class="nav-link">
                        <i class="fas fa-tree nav-icon"></i>
                        <p>{{ __('Biqiltuuwwan') }}</p>
                    </a>
                </li> --}}

            </ul>
        </li>
{{-- Baballina Woldaalee  --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Construction of Institutions') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/building-community-institution-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Community Organizations') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/building-power-institution-plans') }}" class="nav-link">
                        <i class="fas fa-flask nav-icon"></i>
                        <p>{{ __('Energy Companies') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/building-economic-institution-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Economic Transition') }}</p>
                    </a>
                </li>
 

            </ul>
        </li>       {{-- Misooma Hawaasummaa  --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Social Development') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/barnoota') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Education') }}</p>
                    </a>
                </li>

                

                  <li class="nav-item">
                    <a href="{{ url('sector/health-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Health') }}</p>
                    </a>
                </li>

                  <li class="nav-item">
                    <a href="{{ url('sector/cultural-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Cultural Revival') }}</p>
                    </a>
                </li>

                   <li class="nav-item">
                    <a href="{{ url('sector/civil-registration-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Civil Certificates') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/kebele-asset-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Government Properties') }}</p>
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="{{ url('sector/employment-opportunity-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Job Opportunities') }}</p>
                    </a>
                </li>
 

            </ul>
        </li>
    </ul>
</li>

{{-- karora  torbanii  --}}

<li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                {{ __('Weekly Plan') }}
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
                        <a href="{{ url('sector/karoora-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Crop') }}</p>
                        </a>
                    </li>
            </ul>
            </li>
        </ul>
</li>
        
{{-- Raawwii Torbanii  --}}

<li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                {{ __('Weekly Performance') }}
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
                        <a href="{{ url('sector/rawwii-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Crop') }}</p>
                        </a>
                    </li>
            </ul>
            </li>
        </ul>
</li>

<!-- Yeroo Raawwii (Execution Time) -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-clock"></i>
        <p>
            {{ __('Execution Time ') }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ url('sector/activityplan-plans') }}" class="nav-link">
                <i class="fas fa-tractor nav-icon"></i>
                <p>{{ __('Land') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/productiontime-plans') }}" class="nav-link">
                <i class="fas fa-industry nav-icon"></i>
                <p>{{ __('Production') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/fertilizer-plans') }}" class="nav-link">
                <i class="fas fa-flask nav-icon"></i>
                <p>{{ __('Fertilizer') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/mechanizations/timeline') }}" class="nav-link">
                <i class="fas fa-cogs nav-icon"></i>
                <p>{{ __('Mechinization') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/gabbina_biyyee/timeline') }}" class="nav-link">
                <i class="fas fa-mountain nav-icon"></i>
                <p>{{ __('Soil Development') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/seedling-preparations/timeline') }}" class="nav-link">
                <i class="fas fa-tree nav-icon"></i>
                <p>{{ __('Seedling') }}</p>
            </a>
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
                        <a href="{{ url('sector/gabaasa-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Crop') }}</p>
                        </a>
                    </li>
                     
                    <li class="nav-item">
                        <a href="{{ url('sector/gabaasa-torbanii/fertilizer') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Fertilizer') }}</p>
                        </a>
                        </li>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/mechanization-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Mechinization') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/compost-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Soil Development') }}</p>
                        </a>
                    </li>
                        
                    <li class="nav-item">
                        <a href="{{ url('kebele/seedling-reports/kebele') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Seedling') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/livestock/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Livestock') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/natural-resources/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Natural Resources') }}</p>
                        </a>
                    </li>
                      
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/association-expansion/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Association Expansion') }}</p>
                        </a>
                    </li>  
                        
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/building-institution/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Building Institution') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/power-institution/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Power Institution') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/economic-transition-facilities/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Economic Transition Facilities') }}</p>
                        </a>
                    </li>  
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/barnoota/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Education') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/healthy/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Health') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/cultural/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Cultural Revival') }}</p>
                        </a>
                    </li>   
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/asset/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Asset') }}</p>
                        </a>
                    </li>   
                    <li class="nav-item">
                        <a href="{{ url('kebele/gabaasa_torbanii/employee/plans') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Employee') }}</p>
                        </a>
                    </li>
            </ul>
            </li>
        </ul>
</li>

 

<li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
                {{ __('Configuration ') }}
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>

        <ul class="nav nav-treeview">

            <!-- Misooma Midhaanii (Crop Development) -->
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-seedling nav-icon"></i>
                    <p>
                        {{ __('Crop Configuration') }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="{{ url('kebele/crop-configurations') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Productivity') }}</p>
                        </a>
                    </li>
                     
                   
            </ul>
            </li>
        </ul>
</li>

 
