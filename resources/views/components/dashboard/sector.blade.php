<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table thead th {
            white-space: nowrap;
            position: relative;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-refunded {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-canceled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }

        .search-box input {
            padding-left: 40px;
        }

        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2rem;
            opacity: 0.7;
        }

        .progress-thin {
            height: 6px;
        }

        .crop-card {
            border-left: 4px solid;
        }

        .crop-card.wheat {
            border-left-color: #f9a825;
        }

        .crop-card.corn {
            border-left-color: #7cb342;
        }

        .crop-card.barley {
            border-left-color: #5d4037;
        }

        .crop-card.teff {
            border-left-color: #4e342e;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 px-md-4">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">
                            {{ Auth::user()->sector->name ?? 'Sector Dashboard' }}
                        </a>

                        <div class="d-flex ms-auto align-items-center">
                            <button class="btn btn-outline-secondary me-2"><i class="bi bi-brightness-high"></i></button>
                            <button class="btn btn-outline-secondary me-2"><i class="bi bi-bell"></i></button>
                            <button class="btn btn-outline-secondary"><i class="bi bi-person-circle"></i></button>
                        </div>
                    </div>
                </nav>

                <!-- Dashboard Cards -->

            </main>
        </div>
    </div>

</x-layout>