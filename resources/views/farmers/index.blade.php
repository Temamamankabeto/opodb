<x-layout>
<div class="container-fluid px-4 py-5 farmer-management">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1 text-primary">Farmers Management</h2>
                    <p class="text-muted">Manage all farmers in your system</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="search-container position-relative">
                        <i class="bi bi-search position-absolute search-icon"></i>
                        <input type="text" id="searchInput" class="form-control search-input ps-5" placeholder="Search farmers...">
                    </div>
                    <a href="{{ route('farmers.create') }}" class="btn btn-success btn-add">
                        <i class="bi bi-plus-circle me-2"></i> Add New Farmer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Farmers</h5>
                            <h2 class="mb-0">{{ count($farmers) }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Active</h5>
                            <h2 class="mb-0">{{ count($farmers) }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Associations</h5>
                            <h2 class="mb-0">{{ $farmers->pluck('association_id')->unique()->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Zones</h5>
                            <h2 class="mb-0">{{ $farmers->pluck('zone_id')->unique()->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farmers Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 text-dark">
                <i class="bi bi-table me-2"></i> Farmers List
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Farmer Name</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Zone</th>
                            <th>Woreda</th>
                            <th>Kebele</th>
                            <th>Union</th>
                            <th>Association</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="farmerTable">
                        @forelse ($farmers as $index => $farmer)
                            <tr class="farmer-row">
                                <td class="ps-4 fw-bold text-primary">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person-fill text-primary"></i>
                                        </div>
                                        <span class="fw-medium">{{ $farmer->farmer_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge gender-badge {{ $farmer->gender === 'male' ? 'bg-primary' : 'bg-pink' }}">
                                        {{ ucfirst($farmer->gender) }}
                                    </span>
                                </td>
                                <td>
                                    @if($farmer->phone_number)
                                        <a href="tel:{{ $farmer->phone_number }}" class="text-decoration-none">
                                            <i class="bi bi-telephone-fill me-1 text-muted"></i>
                                            {{ $farmer->phone_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $farmer->zone->name ?? '-' }}</td>
                                <td>{{ $farmer->woreda->name ?? '-' }}</td>
                                <td>{{ $farmer->kebele->name ?? '-' }}</td>
                                <td>{{ $farmer->union->name ?? '-' }}</td>
                                <td>
                                    @if($farmer->association->name ?? false)
                                        <span class="badge bg-light text-dark">{{ $farmer->association->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('farmers.edit', $farmer->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('farmers.destroy', $farmer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this farmer?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-people display-4 text-muted"></i>
                                        <h4 class="mt-3 text-muted">No farmers found</h4>
                                        <p class="text-muted">Get started by adding your first farmer</p>
                                        <a href="{{ route('farmers.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-circle me-2"></i> Add Farmer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($farmers->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Showing {{ $farmers->firstItem() ?? 0 }} to {{ $farmers->lastItem() ?? 0 }} of {{ $farmers->total() }} entries
        </div>
        <div>
            {{ $farmers->links() }}
        </div>
    </div>
    @endif
</div>

<style>
.farmer-management {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.card {
    border: none;
    border-radius: 12px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.stat-card {
    border-radius: 12px;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.7;
}

.search-container {
    width: 300px;
}

.search-input {
    border-radius: 50px;
    border: 1px solid #e0e0e0;
    padding: 10px 20px 10px 45px;
    transition: all 0.3s;
}

.search-input:focus {
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    border-color: #2e7d32;
}

.search-icon {
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.btn-add {
    border-radius: 50px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(46, 125, 50, 0.3);
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    padding: 15px 12px;
}

.table td {
    padding: 15px 12px;
    vertical-align: middle;
}

.farmer-row:hover {
    background-color: rgba(46, 125, 50, 0.05);
}

.avatar-sm {
    width: 36px;
    height: 36px;
}

.gender-badge {
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 50px;
}

.bg-pink {
    background-color: #e91e63 !important;
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
    transition: all 0.2s;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
}

.empty-state {
    padding: 40px 20px;
}

.alert {
    border: none;
    border-radius: 10px;
    padding: 16px 20px;
}

.pagination .page-link {
    border-radius: 8px;
    margin: 0 3px;
    border: none;
    color: #495057;
}

.pagination .page-item.active .page-link {
    background-color: #2e7d32;
    border-color: #2e7d32;
}

@media (max-width: 768px) {
    .search-container {
        width: 100%;
        margin-bottom: 15px;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .btn-add {
        width: 100%;
        margin-top: 10px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 5px 8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#farmerTable tr');
    
    searchInput.addEventListener('keyup', function() {
        const query = this.value.toLowerCase().trim();
        
        tableRows.forEach(row => {
            if (row.classList.contains('farmer-row')) {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(query) ? '' : 'none';
            }
        });
        
        // Show empty state if no results
        const visibleRows = document.querySelectorAll('#farmerTable tr.farmer-row[style=""]');
        const emptyState = document.querySelector('.empty-state');
        
        if (emptyState && visibleRows.length === 0 && query !== '') {
            emptyState.innerHTML = `
                <i class="bi bi-search display-4 text-muted"></i>
                <h4 class="mt-3 text-muted">No farmers found</h4>
                <p class="text-muted">Try adjusting your search terms</p>
            `;
        }
    });
    
    // Add animation to table rows
    tableRows.forEach((row, index) => {
        if (row.classList.contains('farmer-row')) {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                row.style.transition = 'opacity 0.3s, transform 0.3s';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        }
    });
});
</script>
</x-layout>