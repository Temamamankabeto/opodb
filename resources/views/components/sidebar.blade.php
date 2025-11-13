<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* --- Sidebar Container --- */
    .sidebar-container {
        display: flex;
        position: relative;
        height: 100vh;
    }

    /* --- Sidebar Styles --- */
    nav.sidebar {
        width: 280px;
        min-width: 220px;
        max-width: 600px;
        background: #f8f9fa;
        padding: 10px;
        border-right: 1px solid #ddd;
        font-family: "Segoe UI", sans-serif;
        white-space: nowrap;
        transition: width 0.1s linear;
        overflow-x: hidden;
    }

    /* --- Resizer Bar --- */
    .sidebar-resizer {
        width: 6px;
        cursor: ew-resize;
        background: #e0e0e0;
        transition: background 0.2s;
    }

    .sidebar-resizer:hover {
        background: #c0c0c0;
    }

    /* --- Sidebar Tree Styles --- */
    .sidebar-tree {
        list-style: none;
        padding-left: 0.5rem;
        font-size: 15px;
    }

    .sidebar-tree li {
        position: relative;
        padding-left: 1.2rem;
        cursor: pointer;
        user-select: none;
        margin: 2px 0;
    }

    /* Lines */
    .sidebar-tree li::before {
        content: "";
        position: absolute;
        top: 0.8em;
        left: 0;
        width: 0.7rem;
        height: 1px;
        background: #ccc;
    }

    .sidebar-tree li::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0.35rem;
        width: 1px;
        height: 100%;
        background: #ccc;
    }

    /* Nested ULs */
    .sidebar-tree ul {
        list-style: none;
        margin-left: 1rem;
        padding-left: 0.8rem;
        border-left: 1px dashed #ccc;
        display: none;
    }

    /* Arrows */
    .sidebar-tree .caret::before {
        content: "▶";
        display: inline-block;
        margin-right: 6px;
        transition: transform 0.2s;
        font-size: 12px;
    }

    .sidebar-tree .caret-down::before {
        transform: rotate(90deg);
    }

    /* Icons */
    .sidebar-tree .folder-icon {
        color: #d39e00;
        margin-right: 5px;
    }

    .sidebar-tree .file-icon {
        color: #6c757d;
        margin-right: 5px;
    }

    /* Links */
    .sidebar-tree a {
        text-decoration: none;
        color: #333;
        padding: 3px 6px;
        border-radius: 4px;
        display: inline-block;
        width: calc(100% - 20px);
    }

    .sidebar-tree a:hover {
        background: #f1f1f1;
    }

    /* Active Highlight */
    .sidebar-tree a.active {
        background: #007bff;
        color: white;
        font-weight: 500;
    }

    /* Highlight open folders */
    .sidebar-tree .active-folder>.caret {
        font-weight: 600;
        color: #007bff;
    }
</style>

<div class="sidebar-container">
    <nav class="sidebar">
        <ul class="sidebar-tree">
            <!-- super admin  -->
            @if (Auth::user()->role_id == 1)
                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('User Management') }}</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="{{ url('admin/users') }}"> {{ __('Zone Admin') }}</a></li>


                    </ul>
                </li>


                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Structure') }}</span>
                    <ul>

                        <li> <i class="fa-regular fa-file file-icon"></i><a href="{{ url('admin/zones') }}">{{ __('Zones') }}</a>
                        </li>
                        <li> <i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">{{ __('Sectors') }}</a></li>

                    </ul>
                </li>

                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Report') }}</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-wagga">{{ __('Crop Production') }}</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">{{ __("Production Infrastructure") }}</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">{{ __('Social Production') }}</a></li>

                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">{{ __('Job Creation Opportunity') }}</a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">{{ __('Government Policies') }}</a></li>

                        <li style="color: #007bff;"><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia">{{ __('ALL Report') }}</a></li>
                    </ul>
                </li>


                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Configuration') }}</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/livestock-supplies') }}">{{ __('Livestock Supplies') }}</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/livestock_product') }}">{{ __("Livestock Products") }}</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href=" {{ url('admin/naturals') }}">{{ __('Natural Resources') }}</a></li>

                                       <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/naturals') }}">{{ __('Natural Resources') }}</a></li>

                                 <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/enviromental-activity') }}">{{ __('Enviromental Activity') }}</a></li>



                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/enviromental-activity') }}">{{ __('Enviromental Activity') }}</a></li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ route('languages.list') }}">{{ __('Languages') }}</a></li>
                    </ul>
                </li>
            @endif

            <!-- Admin admin  -->

            @if (Auth::user()->role_id == 2)
                <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('User Management') }}</span>
                <ul>
                    <li>
                        <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Users') }}</span>
                        <ul>
                            <li><i class="fa-regular fa-file file-icon"></i><a href="{{ url('admin/users') }}">{{ __('Woreda Users') }}</a></li>
                            <li><i class="fa-regular fa-file file-icon"></i><a href="/fertilizer-type">{{ __('Kebele Users') }}</a>
                            </li>

                        </ul>
                    </li>


                </ul>
            @endif
            <!-- Regional admin  -->

            @if (Auth::user()->role_id == 5)
            @endif
            <!-- zonal  admin  -->

            @if (Auth::user()->role_id == 5)
            @endif
            <!-- Woreda admin  -->

            @if (Auth::user()->role_id == 5)
            @endif
            <!-- kebele admin  -->

            @if (Auth::user()->role_id == 4)
                <li>
                    <h5><i class="fa-regular  ">
                            <a href="{{ url('kebele/dashboard') }}">{{ __('Dashboard') }}</a>
                        </i> </h5>
                    <hr>
                </li>

                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('User Management') }}</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('kebele/sector-admins') }}">{{ __('Sector Admins') }}</a></li>

                        <li><i class="fa-regular fa-file file-icon"></i><a href="{{ url('kebele/da-users') }}">{{ __('DA Expert') }}</a></li>

                    </ul>
                </li>

                <li>

                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Configuration') }}</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('kebele/crop-configurations') }}">{{ __('Productivity') }}</a></li>


                    </ul>

                </li>
            @endif
            <!-- sector admin  -->

            @if (in_array(Auth::user()->role_id, [4]))
                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Karoora') }}</span>
                    <ul>
                        <li>
                            <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Karrora Waggaa') }}</span>
                            <ul>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Crop Production') }}</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{ url('sector/crop-plans') }}">{{ __('Crop') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Fertilizer Supply') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/fertilizer-type">{{ __('Soil Management Work') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Seedling Preparation and Storage') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Livestock Supplies') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Livestock Products') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Livestock Production') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/fertilizer-type">{{ __('Natural Resources') }}</a></li>

                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>{{ __('Production Infrastructure') }} </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">{{ __('Construction Institutions') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Renovation of Institutions') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Facility Construction') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Construction of Economic Infrastructure Facilities') }}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Hawaasummaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Hirmaannaa
                                                Fi Itti Fufiinsa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Bu'aqabeessummaa Barnootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Nyaata Barattootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Aadaa
                                                Fi Aartii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Galmeessa Sivilii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Fayyaa</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Carraa Hojii
                                        Uumuu</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Carraa
                                                Hojii Uumuu</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Horataalee
                                        Motummaa</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Horataalee
                                                Motummaa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Karrora
                                Kurmaanaa</span>
                            <ul>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Midhaanii</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Midhaan</a>
                                        </li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii LQ</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Hojii
                                                Gabbina Biyyoo</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Qophii Fi Dhaabbii Biqiltuuwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha fi Bu'aa Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Hojii
                                                {{ __('Natural Resources') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Bu'uuraalee
                                        Misooma </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Ijaarsa
                                                Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Haaromsa Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwan Cie'uumsa diinagdee</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Hawaasummaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Hirmaannaa
                                                Fi Itti Fufiinsa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Bu'aqabeessummaa Barnootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Nyaata Barattootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Aadaa Fi Aartii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Galmeessa Sivilii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Fayyaa</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Carraa Hojii
                                        Uumuu</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Carraa
                                                Hojii Uumuu</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Horataalee
                                        Motummaa</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Horataalee Motummaa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Karrora Ji'aa</span>
                            <ul>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Midhaanii</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Midhaan</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii LQ</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Hojii Gabbina Biyyoo</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Qophii Fi Dhaabbii Biqiltuuwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha fi Bu'aa Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Natural Resources Products') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Bu'uuraalee
                                        Misooma </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Ijaarsa
                                                Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Haaromsa Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwan Cie'uumsa diinagdee</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Hawaasummaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Hirmaannaa Fi Itti Fufiinsa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Bu'aqabeessummaa Barnootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Nyaata Barattootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Aadaa Fi Aartii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Galmeessa Sivilii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Fayyaa</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Carraa Hojii
                                        Uumuu</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Carraa
                                                Hojii Uumuu</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Horataalee
                                        Motummaa</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Horataalee Motummaa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>

                </li>


                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Gabaasaa</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-wagga">Misoomaa
                                Midhaanii</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Bu'uuraalee
                                Misoomaa</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Misoomaa
                                Hawaasummaa</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Carraaa Hojii Uumuu</a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Horataalee
                                Mootummaa</a></li>
                        <li style="color: #007bff;"><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia">Gabasa Woliigalaa</a></li>
                    </ul>
                </li>
            @endif



            @if (Auth::user()->role_id == 5 && Auth::user()->sector_id == 1)
                <li>
                    <h5><i class="fa-regular  ">
                            <a href="{{ url('sector/dashboard/sector') }}">Dashboard</a>
                        </i> </h5>
                    <hr>
                </li>
                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Karoora</span>
                    <ul>
                        <li>
                            <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Karrora Waggaa</span>
                            <ul>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Midhaanii</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{ url('sector/crop-plans') }}">Karoora Midhaan</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{ url('sector/mechanizations') }}">Liqii fi
                                                mekaanaayizeeshinii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{ url('sector/gabbina-biyyee') }}">Hojii
                                                Gabbina Biyyoo</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href=" {{ url('sector/seedling-preparations') }}">Qophii Fi Dhaabbii
                                                Biqiltuuwwanii</a></li>

                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Beeyladaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href=" {{ url('sector/livestock-plans') }}">Dhiyeessiiwwan Fi Bu'aa
                                                beeyladaa </a></li>


                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Qabeenya
                                        Uumamaa fi Eegumsa Naannoo</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Hojii
                                                {{ __('Natural Resources Products') }}</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href=" {{url('sector/environmental-plans')}}">Eegumsa Naannoo fi Misooma Sululaa </a></li>

                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Babal’ina
                                        Waldaalee Hojii Gamtaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Waldaalee
                                                Hojii Gamtaa</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Karrora
                                Kurmaanaa</span>
                            <ul>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Midhaanii</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Midhaan</a>
                                        </li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii LQ</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Hojii
                                                Gabbina Biyyoo</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Qophii Fi Dhaabbii Biqiltuuwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha fi Bu'aa Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Natural Resources Products') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Bu'uuraalee
                                        Misooma </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Ijaarsa
                                                Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Haaromsa Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwan Cie'uumsa diinagdee</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Hawaasummaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Hirmaannaa
                                                Fi Itti Fufiinsa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Bu'aqabeessummaa Barnootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Nyaata Barattootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Aadaa Fi Aartii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Galmeessa Sivilii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Fayyaa</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Carraa Hojii
                                        Uumuu</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Carraa
                                                Hojii Uumuu</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Horataalee
                                        Motummaa</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Horataalee Motummaa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Karrora Ji'aa</span>
                            <ul>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Midhaanii</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Midhaan</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii LQ</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Hojii Gabbina Biyyoo</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Qophii Fi Dhaabbii Biqiltuuwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha fi Bu'aa Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Oomisha Beeyladaa Beeyladaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">{{ __('Natural Resources Products') }}</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Bu'uuraalee
                                        Misooma </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Ijaarsa
                                                Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Haaromsa Dhaabbilee</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwanii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Ijaarsa Faasiliitiwwan Cie'uumsa diinagdee</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Misooma
                                        Hawaasummaa </span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Hirmaannaa Fi Itti Fufiinsa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Bu'aqabeessummaa Barnootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Dhiyeessii Nyaata Barattootaa</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Aadaa Fi Aartii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Galmeessa Sivilii</a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/fertilizer-type">Fayyaa</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Carraa Hojii
                                        Uumuu</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a href="/crop-plan">Carraa
                                                Hojii Uumuu</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Horataalee
                                        Motummaa</span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="/crop-plan">Horataalee Motummaa</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>

                </li>


                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i>Gabaasaa</span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-wagga">Misoomaa
                                Midhaanii</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Bu'uuraalee
                                Misoomaa</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Misoomaa
                                Hawaasummaa</a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Carraaa Hojii Uumuu</a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia">Horataalee
                                Mootummaa</a></li>
                        <li style="color: #007bff;"><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia">Gabasa Woliigalaa</a></li>
                    </ul>
                </li>
            @endif

        </ul>
    </nav>

    <div class="sidebar-resizer"></div>
</div>

<script>
    /* --- Folder Toggle --- */
    document.querySelectorAll(".caret").forEach(caret => {
        caret.addEventListener("click", e => {
            e.stopPropagation();
            const nested = caret.parentElement.querySelector(":scope > ul");
            const icon = caret.querySelector(".folder-icon");
            caret.classList.toggle("caret-down");
            if (nested) {
                const open = nested.style.display === "block";
                nested.style.display = open ? "none" : "block";
                if (icon) {
                    icon.classList.toggle("fa-folder", open);
                    icon.classList.toggle("fa-folder-open", !open);
                }
            }
        });
    });

    /* --- Active Link Highlight --- */
    const currentUrl = window.location.pathname;
    document.querySelectorAll(".sidebar-tree a").forEach(link => {
        if (link.getAttribute("href") === currentUrl) {
            link.classList.add("active");
            let parent = link.closest("ul");
            while (parent && parent.closest("li")) {
                const parentLi = parent.closest("li");
                const caret = parentLi.querySelector(":scope > .caret");
                const folderIcon = caret ? caret.querySelector(".folder-icon") : null;
                if (caret) {
                    caret.classList.add("caret-down");
                    parent.style.display = "block";
                    parentLi.classList.add("active-folder");
                    if (folderIcon) {
                        folderIcon.classList.remove("fa-folder");
                        folderIcon.classList.add("fa-folder-open");
                    }
                }
                parent = parentLi.closest("ul");
            }
        }
    });

    /* --- Draggable Resizer --- */
    const sidebar = document.querySelector("nav.sidebar");
    const resizer = document.querySelector(".sidebar-resizer");

    let isResizing = false;

    resizer.addEventListener("mousedown", e => {
        isResizing = true;
        document.body.style.cursor = "ew-resize";
        e.preventDefault();
    });

    document.addEventListener("mousemove", e => {
        if (!isResizing) return;
        let newWidth = e.clientX;
        if (newWidth < 220) newWidth = 220;
        if (newWidth > 600) newWidth = 600;
        sidebar.style.width = newWidth + "px";
    });

    document.addEventListener("mouseup", () => {
        isResizing = false;
        document.body.style.cursor = "default";
    });
</script>
