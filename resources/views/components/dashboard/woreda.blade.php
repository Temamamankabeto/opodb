<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background-color: #f7f8fa; 
            margin: 0;
            padding: 0;
        }
        
        .main-panel { 
            padding: 0px;
            min-height: 100vh;
             width: 80vw;
        }
        
        .navbar { 
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
        }
        
        /* Header Tabs */
        .header-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 0px;
            background: white;
            padding: 0 20px;
        }
        
        .header-tabs .nav-link {
            border: none;
            padding: 0.75rem 1.25rem;
            color: #495057;
            font-weight: 500;
            cursor: pointer;
        }
        
        .header-tabs .nav-link.active {
            color: #4361ee;
            border-bottom: 3px solid #4361ee;
            background: transparent;
        }
        
        /* Cards */
        .statistics-card, .chart-container { 
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        /* Charts */
        .chart-container { 
            height: 300px; 
            position: relative; 
        }
        
        .chart-title { 
            font-weight: 600; 
            margin-bottom: 15px; 
            color: #2c3e50; 
        }
        
        /* Tables */
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        
        .data-table th { 
            background-color: #f8f9fa; 
            padding: 10px 15px; 
        }
        
        .data-table td { 
            padding: 10px 15px; 
            border-top: 1px solid #dee2e6; 
        }
        
        .product-info { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        }
        
        .product-img { 
            width: 30px; 
            height: 30px; 
            border-radius: 50%; 
        }
        
        /* Tab Content */
        .tab-content { 
            display: none; 
        }
        
        .tab-content.active { 
            display: block; 
        }
    </style>

    <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 rounded">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">Dashboard</a>
                <div class="d-flex ms-auto">
                    <button class="btn btn-outline-secondary me-2"><i class="bi bi-brightness-high"></i></button>
                    <button class="btn btn-outline-secondary me-2"><i class="bi bi-bell"></i></button>
                    <button class="btn btn-outline-secondary"><i class="bi bi-person-circle"></i></button>
                </div>
            </div>
        </nav>

        <!-- Header Tabs -->
        <div class="d-flex justify-content-between align-items-center header-tabs">
            <ul class="nav nav-tabs border-0" id="dashboardTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-tab="overview">Misooma Qonnaa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="audiences">Bu'uuraalee Misoomaa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="demographics">Misooma Hawaasummaa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="government">Horataalee Mootummaa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="workflow">Carraa Hojii Uumu</a>
                </li>
            </ul>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-share"></i> Share</button>
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Print</button>
                <button class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Export</button>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class=" ">
            <!-- Overview Tab (Default Active) -->
            <div id="overview" class="tab-content active">
                <!-- Statistics Cards -->
                <div class="statistics-card d-flex justify-content-between flex-wrap">
                    <div class="text-center mb-3" style="min-width: 150px;">
                        <p class="text-muted mb-1">Midhaan Woliigalaa</p>
                        <h4 class="text-danger">32.53% <small class="fs-6">-0.5%</small></h4>
                    </div>
                     <div class="text-center mb-3 d-none d-md-block" style="min-width: 150px;">
                        <p class="text-muted mb-1">Lafa </p>
                        <h4>2m:35s <small class="fs-6 text-success">+0.8%</small></h4>
                    </div>
                    <div class="text-center mb-3" style="min-width: 150px;">
                        <p class="text-muted mb-1">Biqiltuuwwan</p>
                        <h4 class="text-success">7,682 <small class="fs-6">+0.1%</small></h4>
                    </div>
                    <div class="text-center mb-3" style="min-width: 150px;">
                        <p class="text-muted mb-1">Beeyladaa Fi Bu'aa isaa</p>
                        <h4>68.8 <small class="fs-6 text-danger">-1.2%</small></h4>
                    </div>
                   
                </div>
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <!-- Date Range Filter -->
            <div class="col-md-3">
                <label for="dateRange" class="form-label">Bara</label>
                <select class="form-select" id="dateRange">
                    <option selected>All Time</option>
                   
                </select>
            </div>
            
            
            
            <!-- Action Buttons -->
            <div class="col-12 d-flex justify-content-end gap-2">
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
                <button class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>


<div class="row flex-grow">
        <div class="col-12 grid-margin stretch-card">
            <div class="card card-rounded">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-start">
                        
                        <div>
                                
                        </div>
                    </div>
                    
                    <!-- Search Box -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search all columns...">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive mt-1">
                        <table class="table select-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" aria-checked="false" id="check-all">
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </th>
                                    <th class="sortable" data-sort="customer">Customer <i class="bi bi-arrow-down-up"></i></th>
                                    <th class="sortable" data-sort="company">Company <i class="bi bi-arrow-down-up"></i></th>
                                    <th class="sortable" data-sort="progress">Progress <i class="bi bi-arrow-down-up"></i></th>
                                    <th class="sortable" data-sort="status">Status <i class="bi bi-arrow-down-up"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" aria-checked="false">
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <img src="assets/images/faces/face1.jpg" alt="">
                                            <div>
                                                <h6>Brandon Washington</h6>
                                                <p>Head admin</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6>Company name 1</h6>
                                        <p>company type</p>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                <p class="text-success">79%</p>
                                                <p>85/162</p>
                                            </div>
                                            <div class="progress progress-md">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-opacity-warning">In progress</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" aria-checked="false">
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <img src="assets/images/faces/face2.jpg" alt="">
                                            <div>
                                                <h6>Laura Brooks</h6>
                                                <p>Head admin</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6>Company name 1</h6>
                                        <p>company type</p>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                <p class="text-success">65%</p>
                                                <p>85/162</p>
                                            </div>
                                            <div class="progress progress-md">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-opacity-warning">In progress</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" aria-checked="false">
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <img src="assets/images/faces/face3.jpg" alt="">
                                            <div>
                                                <h6>Wayne Murphy</h6>
                                                <p>Head admin</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6>Company name 1</h6>
                                        <p>company type</p>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                <p class="text-success">65%</p>
                                                <p>85/162</p>
                                            </div>
                                            <div class="progress progress-md">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 38%" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-opacity-warning">In progress</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" aria-checked="false">
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <img src="assets/images/faces/face4.jpg" alt="">
                                            <div>
                                                <h6>Matthew Bailey</h6>
                                                <p>Head admin</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6>Company name 1</h6>
                                        <p>company type</p>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                <p class="text-success">65%</p>
                                                <p>85/162</p>
                                            </div>
                                            <div class="progress progress-md">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-opacity-danger">Pending</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" aria-checked="false">
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <img src="assets/images/faces/face5.jpg" alt="">
                                            <div>
                                                <h6>Katherine Butler</h6>
                                                <p>Head admin</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6>Company name 1</h6>
                                        <p>company type</p>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">
                                                <p class="text-success">65%</p>
                                                <p>85/162</p>
                                            </div>
                                            <div class="progress progress-md">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-opacity-success">Completed</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="form-group mb-0">
                                <select class="form-select form-select-sm" id="rowsPerPage">
                                    <option value="5">5 rows</option>
                                    <option value="10" selected>10 rows</option>
                                    <option value="25">25 rows</option>
                                    <option value="50">50 rows</option>
                                </select>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <!-- Charts Row -->
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="chart-container">
                            <h5 class="chart-title">Midhaan</h5>
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-container">
                       <div style="display: flex; align-items: flex-start; gap: 20px;">
    <!-- Chart (left side) -->
    <div style="width: 200px; height: 200px;">
        <canvas id="pieChart"></canvas>
    </div>

    <!-- Data (right side) -->
    <div id="chartData">
        <h4>Data Summary</h4>
        <ul style="list-style: none; padding-left: 0;">
            <li><span style="color:#4361ee;">■</span> Midhaan Dheedhii: 1200</li>
            <li><span style="color:#3a0ca3;">■</span> Midhaan Agadaa: 700</li>
            <li><span style="color:#7209b7;">■</span> Midhaan Dibataa: 500</li>
            <li><span style="color:#f72585;">■</span> Midhaan Biilaa: 300</li>
        </ul>
    </div>
</div>

                    </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row g-3 mt-2">
                    <div class="col-lg-8">
                        <div class="chart-container">
                            <h5 class="chart-title">Sales by Age</h5>
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                   <div class="col-lg-4">
 
</div>


                </div>
            </div>

            <!-- Audiences Tab -->
            <div id="audiences" class="tab-content">
                <div class="chart-container">
                    <h5 class="chart-title">Audience Demographics</h5>
                    <div class="alert alert-info">
                        <h4>Bu'uuraalee Misoomaa Content</h4>
                        <p>This section will contain detailed audience analysis data.</p>
                        <ul>
                            <li>Audience segmentation</li>
                            <li>Engagement metrics</li>
                            <li>Behavior patterns</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Demographics Tab -->
            <div id="demographics" class="tab-content">
                <div class="chart-container">
                    <h5 class="chart-title">Population Statistics</h5>
                    <div class="alert alert-info">
                        <h4>Misooma Hawaasummaa Content</h4>
                        <p>This section will contain demographic distribution data.</p>
                        <ul>
                            <li>Age distribution</li>
                            <li>Geographic distribution</li>
                            <li>Education levels</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Government Tab -->
            <div id="government" class="tab-content">
                <div class="chart-container">
                    <h5 class="chart-title">Government Relations</h5>
                    <div class="alert alert-info">
                        <h4>Horataalee Mootummaa Content</h4>
                        <p>This section will contain government-related data.</p>
                        <ul>
                            <li>Policy impacts</li>
                            <li>Regulatory compliance</li>
                            <li>Public sector engagement</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Workflow Tab -->
            <div id="workflow" class="tab-content">
                <div class="chart-container">
                    <h5 class="chart-title">Workflow Processes</h5>
                    <div class="alert alert-info">
                        <h4>Carraa Hojii Uumu Content</h4>
                        <p>This section will contain workflow and process data.</p>
                        <ul>
                            <li>Process timelines</li>
                            <li>Efficiency metrics</li>
                            <li>Workflow optimization</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Line Chart
          new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Midhaan Dheedhii', 'Midhaan Agadaa', 'Midhaan Dibataa', 'Midhaan Biilaa', 'Midhaan Biroo'],
        datasets: [{
            label: 'Midhaan',
            data: [12000, 19000, 3000, 5000, 20000],
            borderColor: '#4361ee',
            backgroundColor: 'rgba(67, 97, 238, 0.1)',
            tension: 0,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    }
});


new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        // labels: ['Midhaan Dheedhii', 'Midhaan Agadaa', 'Midhaan Dibataa', 'Midhaan Biilaa'],
        datasets: [{
            data: [1200, 700, 500, 300],
            backgroundColor: ['#4361ee', '#3a0ca3', '#7209b7', '#f72585']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});


            // Bar Chart
            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: ['18-24', '25-34', '35-44', '45-54', '55+'],
                    datasets: [{
                        label: 'Sales',
                        data: [300, 500, 400, 200, 100],
                        backgroundColor: '#4cc9f0'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });

            // Tab Switching Functionality
            const tabs = document.querySelectorAll('[data-tab]');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and content
                    document.querySelectorAll('.nav-link').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('dataTable');
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const rowsPerPage = document.getElementById('rowsPerPage');
    const pagination = document.getElementById('pagination');
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    });
    
    // Clear search
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => row.style.display = '');
    });
    
    // Sorting functionality
    const sortableHeaders = document.querySelectorAll('.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const columnIndex = this.cellIndex;
            const sortDirection = this.classList.contains('asc') ? 'desc' : 'asc';
            
            // Reset all headers
            sortableHeaders.forEach(h => {
                h.classList.remove('asc', 'desc');
                h.querySelector('i').className = 'bi bi-arrow-down-up';
            });
            
            // Set current header state
            this.classList.add(sortDirection);
            const icon = this.querySelector('i');
            icon.className = sortDirection === 'asc' ? 'bi bi-arrow-up' : 'bi bi-arrow-down';
            
            // Sort the table
            sortTable(columnIndex, sortDirection);
        });
    });
    
    function sortTable(columnIndex, sortDirection) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            // For progress column (special handling)
            if (columnIndex === 3) {
                const aProgress = parseFloat(aValue.split('%')[0]);
                const bProgress = parseFloat(bValue.split('%')[0]);
                return sortDirection === 'asc' ? aProgress - bProgress : bProgress - aProgress;
            }
            
            // For other columns
            return sortDirection === 'asc' 
                ? aValue.localeCompare(bValue) 
                : bValue.localeCompare(aValue);
        });
        
        // Re-add rows in sorted order
        rows.forEach(row => tbody.appendChild(row));
    }
    
    // Pagination functionality
    rowsPerPage.addEventListener('change', function() {
        updatePagination();
    });
    
    function updatePagination() {
        // Implement pagination logic here
        // This would involve showing/hiding rows based on current page and rows per page
    }
});
</script>

<style>
    /* Add these styles to your existing style section */
    .sortable {
        cursor: pointer;
        position: relative;
    }
    .sortable:hover {
        background-color: #f8f9fa;
    }
    .sortable.asc::after, .sortable.desc::after {
        margin-left: 5px;
    }
    .sortable.asc {
        background-color: rgba(67, 97, 238, 0.1);
    }
    .sortable.desc {
        background-color: rgba(67, 97, 238, 0.1);
    }
    #searchInput {
        max-width: 250px;
    }
    .pagination .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    .pagination .page-link {
        color: #4361ee;
    }
</style>
</x-layout>