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
            {{ __('Karrora Waggaa') }}
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
                    <a href="{{ url('sector/crop-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Midhaan') }}</p>
                    </a>
                </li>

                <li class="nav-item">
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
                </li>

            </ul>
        </li>


         <!-- Misooma Beeyladaa  -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Misooma Beeyladaa') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/livestock-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Beeylada') }}</p>
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


         <!-- Qabeeny uumamaa  -->
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Qabeeny Uumamaa') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/natural-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Qaabeenya uumanaa') }}</p>
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
                    {{ __('Woldaalee Gamtaa') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/association-expansion-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Babal`ina Woldaalee') }}</p>
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
                    {{ __('Ijaarsa Dhaabbilee') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/building-community-institution-plans') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Dhaabbilee Hawaasaa') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/building-power-institution-plans') }}" class="nav-link">
                        <i class="fas fa-flask nav-icon"></i>
                        <p>{{ __('Dhaabbilee Anniisaa') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/building-economic-institution-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Ce`uumsa Diinagdee') }}</p>
                    </a>
                </li>
 

            </ul>
        </li>       {{-- Misooma Hawaasummaa  --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-seedling nav-icon"></i>
                <p>
                    {{ __('Misooma Hawaasummaa') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ url('sector/barnoota') }}" class="nav-link">
                        <i class="fas fa-leaf nav-icon"></i>
                        <p>{{ __('Barnootaa') }}</p>
                    </a>
                </li>

                

                  <li class="nav-item">
                    <a href="{{ url('sector/health-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Fayyaa') }}</p>
                    </a>
                </li>

                  <li class="nav-item">
                    <a href="{{ url('sector/cultural-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Aadaa Fi Aartii') }}</p>
                    </a>
                </li>

                   <li class="nav-item">
                    <a href="{{ url('sector/civil-registration-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Ragaalee Civilii') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('sector/kebele-asset-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Horata Mootummaa') }}</p>
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="{{ url('sector/employment-opportunity-plans') }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>{{ __('Carraa Hojii') }}</p>
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
                {{ __('Karrora Torbanii') }}
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
                        <a href="{{ url('sector/karoora-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Midhaan') }}</p>
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
                {{ __('Raawii Torbanii') }}
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
                        <a href="{{ url('sector/rawwii-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Midhaan') }}</p>
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
            {{ __('Yeroo Raawwii') }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ url('sector/activityplan-plans') }}" class="nav-link">
                <i class="fas fa-tractor nav-icon"></i>
                <p>{{ __('Qonna') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/productiontime-plans') }}" class="nav-link">
                <i class="fas fa-industry nav-icon"></i>
                <p>{{ __('Oomisha') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/fertilizer-plans') }}" class="nav-link">
                <i class="fas fa-flask nav-icon"></i>
                <p>{{ __('Calla Guddiftuu') }}</p>
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
                <p>{{ __('Gabbina Biyyoo') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('sector/seedling-preparations/timeline') }}" class="nav-link">
                <i class="fas fa-tree nav-icon"></i>
                <p>{{ __('Biqiltuuwwan') }}</p>
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
                        <a href="{{ url('sector/gabaasa-torbanii/midhaan') }}" class="nav-link">
                            <i class="fas fa-leaf nav-icon"></i>
                            <p>{{ __('Midhaan') }}</p>
                        </a>
                    </li>
                     
                    <li class="nav-item">
                        <a href="{{ url('sector/gabaasa-torbanii/fertilizer') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Calla Guddiftuu') }}</p>
                        </a>
                        </li>
 <li class="nav-item">
                        <a href="{{ url('kebele/mechanization-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Calla Guddiftuu') }}</p>
                        </a>
                        </li>
                         <li class="nav-item">
                        <a href="{{ url('kebele/compost-report') }}" class="nav-link">
                            <i class="fas fa-flask nav-icon"></i>
                            <p>{{ __('Gabbina Biyyoo') }}</p>
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

 
