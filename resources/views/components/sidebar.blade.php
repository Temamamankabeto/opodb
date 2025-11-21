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
        position: relative;
    }

    /* Collapsed state */
    nav.sidebar.collapsed {
        width: 60px;
        min-width: 60px;
    }

    /* Hide text when collapsed */
    nav.sidebar.collapsed .sidebar-text {
        display: none;
    }

    /* --- Toggle Button --- */
    .sidebar-toggle {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: #555;
        z-index: 10;
        padding: 5px;
        border-radius: 3px;
        transition: background-color 0.2s;
    }

    .sidebar-toggle:hover {
        background-color: rgba(0, 0, 0, 0.05);
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
        <!-- Toggle Button -->
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <ul class="sidebar-tree">
            <!-- super admin  -->
            @if (Auth::user()->role_id == 1)
                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('User Management') }}</span></span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a href="{{ url('admin/users') }}">
                                <span class="sidebar-text">{{ __('Zone Admin') }}</span></a></li>
                    </ul>
                </li>

                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Structure') }}</span></span>
                    <ul>
                        <li> <i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('admin/zones') }}"><span class="sidebar-text">{{ __('Zones') }}</span></a>
                        </li>
                        <li> <i class="fa-regular fa-file file-icon"></i><a href="/karoora-jia"><span class="sidebar-text">{{ __('Sectors') }}</span></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Report') }}</span></span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{url('admin/crop-plans')}}"><span class="sidebar-text">{{ __('Crop Plan') }}</span></a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia"><span class="sidebar-text">{{ __('Production Infrastructure') }}</span></a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia"><span class="sidebar-text">{{ __('Social Production') }}</span></a></li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia"><span class="sidebar-text">{{ __('Job Creation Opportunity') }}</span></a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia"><span class="sidebar-text">{{ __('Government Policies') }}</span></a></li>

                        <li style="color: #007bff;"><i class="fa-regular fa-file file-icon"></i><a
                                href="/karoora-jia"><span class="sidebar-text">{{ __('ALL Report') }}</span></a></li>
                    </ul>
                </li>

                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Configuration') }}</span></span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/livestock-supplies') }}"><span class="sidebar-text">{{ __('Livestock Supplies') }}</span></a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/livestock_product') }}"><span class="sidebar-text">{{ __('Livestock Products') }}</span></a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/naturals') }}"><span class="sidebar-text">{{ __('Natural Resources') }}</span></a></li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/enviromental-activity') }}"><span class="sidebar-text">{{ __('Enviromental Activity') }}</span></a>
                        </li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/institutions') }}"><span class="sidebar-text">{{ __('Institutions') }}</span></a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/educational-participations') }}"><span class="sidebar-text">{{ __('Education Participation') }}</span></a>
                        </li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/educational-effectiveness') }}"><span class="sidebar-text">{{ __('Education Effectiveness') }}</span></a>
                        </li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/student-meal-supplies') }}"><span class="sidebar-text">{{ __('Student Meal Supplies') }}</span></a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/health') }}"><span class="sidebar-text">{{ __('Health') }}</span></a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/cultural-revival') }}"><span class="sidebar-text">{{ __('Cultural Revival') }}</span></a>
                        </li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/civil-certificates') }}"><span class="sidebar-text">{{ __(' civil certificates') }}</span></a>
                        </li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/government-properties') }}"><span class="sidebar-text">{{ __('  Horataalee Mootummaa') }}</span></a>
                        </li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ url('admin/job-opportunities') }}"><span class="sidebar-text">{{ __('  Carraa Hojii') }}</span></a>
                        </li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href=" {{ route('languages.list') }}"><span class="sidebar-text">{{ __('Languages') }}</span></a></li>
                    </ul>
                </li>
            @endif

            <!-- Admin admin  -->
            @if (Auth::user()->role_id == 2)
                <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('User Management') }}</span></span>
                <ul>
                    <li>
                        <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Users') }}</span></span>
                        <ul>
                            <li><i class="fa-regular fa-file file-icon"></i><a
                                    href="{{ url('admin/users') }}"><span class="sidebar-text">{{ __('Woreda Users') }}</span></a></li>
                            <li><i class="fa-regular fa-file file-icon"></i><a
                                    href="/fertilizer-type"><span class="sidebar-text">{{ __('Kebele Users') }}</span></a>
                            </li>
                             <li><i class="fa-regular fa-file file-icon"></i><a
                                    href="{{ url('zones/crop-plans') }}"><span class="sidebar-text">{{ __('Crop Plan') }}</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
            <!-- Regional admin  -->
            @if (Auth::user()->role_id == 3)
                <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Reports') }}</span></span>
                <ul>
                    <li>
                        <span class="caret"><i
                                class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Crop Related Report') }}</span></span>
                        <ul>
                            <li><i class="fa-regular fa-file file-icon"></i><a
                                    href="{{ url('woredas/woreda/crop-plans/report') }}"><span class="sidebar-text">{{ __('Crop Plan') }}</span></a></li>
                        </ul>
                    </li>
                </ul>
            @endif

            <!-- kebele admin  -->
            @if (Auth::user()->role_id == 4)
                <li>
                    <h5><i class="fa-regular  ">
                            <a href="{{ url('kebele/dashboard') }}"><span class="sidebar-text">{{ __('Dashboard') }}</span></a>
                        </i> </h5>
                    <hr>
                </li>

                <li>
                    <span class="caret"><i
                            class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('User Management') }}</span></span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('kebele/sector-admins') }}"><span class="sidebar-text">{{ __('Sector Admins') }}</span></a></li>

                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('kebele/da-users') }}"><span class="sidebar-text">{{ __('DA Expert') }}</span></a></li>
                    </ul>
                </li>

                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Configuration') }}</span></span>
                    <ul>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('kebele/crop-configurations') }}"><span class="sidebar-text">{{ __('Productivity') }}</span></a></li>
                        <li><i class="fa-regular fa-file file-icon"></i><a
                                href="{{ url('kebele/associations') }}"><span class="sidebar-text">{{ __('Associations') }}</span></a></li>
                    </ul>
                </li>
            @endif

            <!-- sector admin  -->
            @if (in_array(Auth::user()->role_id, [4,5]))
                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Karoora') }}</span></span>
                    <ul>
                        <li>
                            <span class="caret"><i
                                    class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Karrora Waggaa') }}</span></span>
                            <ul>
                                <li>
                                    <span class="caret"><i
                                            class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Crop Production') }}</span></span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{ url('sector/crop-plans') }}"><span class="sidebar-text">{{ __('Crop') }}</span></a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href=" {{url('sector/crop-fertilizer')}}"><span class="sidebar-text">{{ __('Fertilizer Supply') }}</span></a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{url('sector/mechanizations')}}"><span class="sidebar-text">{{ __('Diyeessii') }}</span></a></li>
                                                   <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{url('sector/gabbina-biyyee')}}"><span class="sidebar-text">{{ __('Gabbina Biyyoo') }}</span></a></li>
                                               
                                        
                                       
                                    </ul>
                                </li>
                             
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Raawwii section -->
                <li>
                    <span class="caret"><i class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Raawwii') }}</span></span>
                    <ul>
                        <li>
                            <span class="caret"><i
                                    class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('Raawwii Waggaa') }}</span></span>
                            <ul>
                                <li>
                                    <span class="caret"><i
                                            class="fa-solid fa-folder folder-icon"></i><span class="sidebar-text">{{ __('sagantaa Yeroo raawwii Qonnaa') }}</span></span>
                                    <ul>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{ url('sector/activityplan-plans') }}"><span class="sidebar-text">{{ __('Qonna') }}</span></a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{url('sector/productiontime-plans')}}"><span class="sidebar-text">{{ __('oomisha') }}</span></a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href=" {{url('sector/fertilizer-plans')}}"><span class="sidebar-text">{{ __('fertilizer') }}</span></a></li>
                                        <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{url('sector/mechanizations/timeline')}}"><span class="sidebar-text">{{ __('Mechinization') }}</span></a>
                                        </li>
                                         <li><i class="fa-regular fa-file file-icon"></i><a
                                                href="{{url('sector/gabbina_biyyee/timeline')}}"><span class="sidebar-text">{{ __('Gabbina Birroo') }}</span></a>
                                        </li>
                                        

                                        
                                    </ul>
                                </li>
                                
                            </ul>
                        </li>
                    </ul>
                </li>

                
            @endif

            <!-- Additional sections for other user roles would go here -->
        </ul>
    </nav>

    <div class="sidebar-resizer"></div>
</div>

<script>
    /* --- Toggle Button Functionality --- */
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('nav.sidebar');
    const sidebarTexts = document.querySelectorAll('.sidebar-text');
    
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        
        // Change the icon based on state
        const icon = sidebarToggle.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        } else {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-left');
        }
    });

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