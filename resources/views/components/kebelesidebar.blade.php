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

                {{-- <li class="nav-item">
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

<!-- Charts -->
{{-- <li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>
            {{ __('Charts') }}
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item"><a href="pages/charts/chartjs.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>ChartJS</p></a></li>
        <li class="nav-item"><a href="pages/charts/flot.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Flot</p></a></li>
        <li class="nav-item"><a href="pages/charts/inline.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Inline</p></a></li>
        <li class="nav-item"><a href="pages/charts/uplot.html" class="nav-link"><i class="far fa-circle nav-icon"></i><p>uPlot</p></a></li>
    </ul>
</li> --}}
