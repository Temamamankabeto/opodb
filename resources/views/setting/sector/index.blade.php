<x-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm"
                        role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Header Section -->
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h1 class="h3 fw-bold text-primary mb-1">Kebele Sectors</h1>
                        <p class="text-muted">Manage all sectors within your kebele</p>
                    </div>
                    <a href="{{ route('sectors.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Add New Sector
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-white-50">Total Sectors</h6>
                                        <h3 class="fw-bold">{{ $sectors->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-grid-3x3-gap display-6 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-white-50">Active</h6>
                                        <h3 class="fw-bold">{{ $sectors->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-circle display-6 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-info text-white shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title text-white-50">Recently Added</h6>
                                        <h3 class="fw-bold">{{ $sectors->count() > 0 ? 1 : 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock display-6 opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="bi bi-list-ul text-primary me-2"></i>
                            Sector List
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($sectors->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>Sector Name</th>
                                            <th>Created At</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sectors as $sector)
                                            <tr>
                                                <td class="ps-4 fw-bold">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                            style="width: 36px; height: 36px;">
                                                            <i class="bi bi-building text-white"></i>
                                                        </div>
                                                        <span class="fw-medium">{{ $sector->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-calendar3 text-muted me-2"></i>
                                                        <span>{{ $sector->created_at->format('d-M-Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group" role="group">
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('sectors.edit', $sector->id) }}" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Edit Sector">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        
                                                        <!-- Delete Button -->
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $sector->id }}"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Sector">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Delete Confirmation Modal -->
                                                    <div class="modal fade" id="deleteModal{{ $sector->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $sector->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel{{ $sector->id }}">Confirm Delete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete the sector <strong>"{{ $sector->name }}"</strong>?</p>
                                                                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <form action="{{ route('sectors.destroy', $sector->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete Sector</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-1 text-muted"></i>
                                </div>
                                <h4 class="text-muted">No sectors added yet</h4>
                                <p class="text-muted mb-4">Get started by adding your first sector</p>
                                <a href="{{ route('sectors.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i> Add New Sector
                                </a>
                            </div>
                        @endif
                    </div>
                    @if ($sectors->count() > 0)
                        <div class="card-footer bg-white py-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing <span class="fw-bold">{{ $sectors->count() }}</span> sectors
                                </div>
                                <!-- Remove pagination since we're not using it, or add proper pagination later -->
                                <div class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>All sectors displayed
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Initialize Bootstrap tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>

    <style>
        .card {
            border-radius: 0.75rem;
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-group .btn {
            margin-left: 0.25rem;
        }

        @media (max-width: 768px) {
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .btn-group .btn {
                border-radius: 0.375rem;
                margin-left: 0;
            }
        }
    </style>
</x-layout>