<x-layout>
    <div class="container-fluid py-4 mechanizations-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="page-header">
                <h2 class="mb-0"><i class="mdi mdi-tractor text-success me-2"></i> Mechanizations Management</h2>
                <p class="text-muted mb-0 mt-1">Track and manage all mechanization equipment with loan information</p>
            </div>
            <a href="{{ route('sector.mechanizations.create') }}" class="btn btn-success btn-add-mechanization">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Mechanization
            </a>
            
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-check-circle-outline me-2 fs-5"></i>
                    <span class="flex-grow-1">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        

        <!-- Search Section -->
        <div class="search-container mb-4">
            <div class="input-group search-input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="mdi mdi-magnify text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0"
                    placeholder="Search by equipment name, type, or loan information...">
            </div>
        </div>

        @if ($mechanizations->isEmpty())
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <i class="mdi mdi-information-outline display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No mechanizations found</h4>
                <p class="text-muted mb-4">Get started by adding your first mechanization record</p>
                <a href="{{ route('sector.mechanizations.create') }}" class="btn btn-success">
                    <i class="mdi mdi-plus-circle-outline me-2"></i> Add Mechanization
                </a>
            </div>
        @else
            @php
                // Group mechanizations by year
                $groupedData = [];
                foreach ($mechanizations->groupBy('year') as $year => $yearMechanizations) {
                    foreach ($yearMechanizations as $mechanization) {
                        $groupedData[] = [
                            'year' => $year,
                            'mechanization' => $mechanization
                        ];
                    }
                }
                
                // Calculate rowspans for each year
                $yearRowspans = [];
                foreach ($mechanizations->groupBy('year') as $year => $yearMechanizations) {
                    $yearRowspans[$year] = $yearMechanizations->count();
                }
            @endphp

            <!-- Data Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="mdi mdi-table me-2 text-primary"></i>
                        Mechanizations Overview (Grouped by Year)
                    </h5>
                    <div class="table-controls">
                        <small class="text-muted me-3">{{ $mechanizations->count() }} records found</small>
                        <button class="btn btn-sm btn-outline-secondary" onclick="scrollToTop('mechanizationsTable')">
                            <i class="mdi mdi-arrow-up"></i> Top
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover align-middle mb-0" id="mechanizationsTable">
                            <thead class="table-light sticky-header">
                                <tr>
                                    <th class="ps-4" style="width: 10%">Year</th>
                                    <th style="width: 15%">Equipment</th>
                                    <th style="width: 12%">Type</th>
                                    <th class="text-end" style="width: 10%">Amount</th>
                                    <th style="width: 8%">Unit</th>
                                    <th style="width: 25%">Loan Information</th>
                                    <th class="text-center" style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentYearIndex = null;
                                    $yearCounter = 0;
                                @endphp

                                @foreach($groupedData as $index => $data)
                                    @php
                                        $year = $data['year'];
                                        $m = $data['mechanization'];
                                        
                                        // Calculate rowspans dynamically
                                        if ($currentYearIndex !== $year) {
                                            $currentYearIndex = $year;
                                            $yearCounter = 0;
                                        }
                                        $yearCounter++;
                                    @endphp
                                    
                                    <tr class="data-row hover-row" 
                                        data-year="{{ $year }}"
                                        data-name="{{ $m->name }}"
                                        data-type="{{ $m->mechanization_type }}"
                                        data-loan="{{ $m->loan_name }}">
                                        
                                        <!-- Year Column -->
                                        @if ($yearCounter === 1)
                                            <td class="year-cell align-middle text-center fw-bold" 
                                                rowspan="{{ $yearRowspans[$year] }}"
                                                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                <div class="year-content">
                                                    <div class="year-value fs-5 text-primary mb-1">
                                                        {{ $year }} EC
                                                    </div>
                                                    @if($year == date('Y'))
                                                        <span class="badge bg-success bg-opacity-20 text-success border border-success">
                                                            Current
                                                        </span>
                                                    @endif
                                                    <div class="year-total mt-2">
                                                        <small class="text-muted d-block">Year Total:</small>
                                                        <div class="fw-bold text-success">
                                                            {{ $yearMechanizations->count() }} items
                                                        </div>
                                                        <div class="fw-bold text-primary">
                                                        
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Equipment Details -->
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="equipment-icon me-2">
                                                    <i class="mdi mdi-tractor text-success"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-dark">{{ $m->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            @if ($m->mechanization_type)
                                                <span class="badge bg-primary">{{ $m->mechanization_type }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-end fw-semibold text-primary">
                                            @if ($m->amount)
                                                {{ number_format($m->amount, 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if ($m->unit)
                                                <span class="badge bg-light text-dark">{{ $m->unit }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @php
                                                $loans = [];
                                                // Check if we have loan_details JSON
                                                if ($m->loan_details) {
                                                    $loans = json_decode($m->loan_details, true);
                                                }
                                                // If no loan_details but we have individual loan columns, use those
                                                elseif ($m->loan_name || $m->loan_type || $m->loan_amount) {
                                                    $loans = [
                                                        [
                                                            'loan_name' => $m->loan_name,
                                                            'loan_type' => $m->loan_type,
                                                            'loan_amount' => $m->loan_amount,
                                                            'loan_duration' => $m->loan_duration,
                                                            'loan_interest' => $m->loan_interest,
                                                        ],
                                                    ];
                                                }
                                            @endphp

                                            @if (!empty($loans))
                                                <div class="loan-info">
                                                    @foreach ($loans as $loan)
                                                        @if (!empty($loan['loan_type']) || !empty($loan['loan_name']))
                                                            <div class="loan-item mb-1">
                                                                <div class="d-flex align-items-center">
                                                                    <small class="fw-semibold text-dark">
                                                                        {{ $loan['loan_name'] ?: $loan['loan_type'] }}
                                                                    </small>
                                                                    @if ($loan['loan_amount'])
                                                                        <span class="badge bg-success ms-2">
                                                                            ETB
                                                                            {{ number_format($loan['loan_amount'], 2) }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                @if ($loan['loan_duration'] || $loan['loan_interest'])
                                                                    <div class="d-flex gap-2 mt-1">
                                                                        @if ($loan['loan_duration'])
                                                                            <small class="text-muted">
                                                                                <i
                                                                                    class="mdi mdi-clock me-1"></i>{{ $loan['loan_duration'] }}mo
                                                                            </small>
                                                                        @endif
                                                                        @if ($loan['loan_interest'])
                                                                            <small class="text-muted">
                                                                                <i
                                                                                    class="mdi mdi-percent me-1"></i>{{ $loan['loan_interest'] }}%
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No loan</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('sector.mechanizations.edit', $m->id) }}"
                                                   class="btn btn-warning btn-sm action-btn mb-1"
                                                   title="Edit Mechanization"
                                                   data-mechanization-id="{{ $m->id }}">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                 <a href="{{ route('sector.mechanizations.plan', $m->id) }}"
                                                   class="btn btn-warning btn-sm action-btn mb-1"
                                                   title="  Mechanization implementation schedule"
                                                   data-mechanization-id="{{ $m->id }}">
                                                    <i class="mdi mdi-pencil"></i> schedule
                                                </a>
                                                
                                                <form action="{{ route('sector.mechanizations.destroy', $m->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this mechanization record? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm action-btn" title="Delete">
                                                        <i class="mdi mdi-delete"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if ($mechanizations->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $mechanizations->firstItem() ?? 0 }} to {{ $mechanizations->lastItem() ?? 0 }}
                        of {{ $mechanizations->total() }} entries
                    </div>
                    <nav>
                        {{ $mechanizations->links() }}
                    </nav>
                </div>
            @endif
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#mechanizationsTable tbody tr.data-row');
                    
                    rows.forEach(function(row) {
                        const name = row.getAttribute('data-name').toLowerCase();
                        const type = row.getAttribute('data-type').toLowerCase();
                        const loan = row.getAttribute('data-loan').toLowerCase();
                        const year = row.getAttribute('data-year').toLowerCase();
                        
                        const show = name.includes(value) || type.includes(value) || loan.includes(value) || year.includes(value);
                        row.style.display = show ? '' : 'none';
                        
                        // Handle year rowspan visibility
                        if (show) {
                            const yearCell = row.querySelector('.year-cell');
                            if (yearCell) {
                                yearCell.style.display = '';
                            }
                        }
                    });
                    
                    // Hide empty year groups
                    updateYearGroupVisibility();
                });
            }

            // Update year group visibility based on filtered rows
            function updateYearGroupVisibility() {
                const yearCells = document.querySelectorAll('.year-cell');
                yearCells.forEach(cell => {
                    const year = cell.closest('tr').getAttribute('data-year');
                    const visibleRows = document.querySelectorAll(`tr.data-row[data-year="${year}"]:not([style*="display: none"])`);
                    
                    if (visibleRows.length === 0) {
                        cell.style.display = 'none';
                    } else {
                        cell.style.display = '';
                        cell.rowSpan = visibleRows.length;
                    }
                });
            }

            // Add hover effects to table rows
            document.querySelectorAll('.hover-row').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.background = 'linear-gradient(135deg, #f0f8ff 0%, #e7f3ff 100%)';
                    this.style.borderLeft = '3px solid #007bff';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.background = '';
                    this.style.borderLeft = '';
                });
            });

            // Add loading states to action buttons
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Only add loading for non-form buttons (edit buttons)
                    if (this.tagName === 'A') {
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
                        this.disabled = true;
                        
                        setTimeout(() => {
                            if (this.disabled) {
                                this.innerHTML = originalHtml;
                                this.disabled = false;
                            }
                        }, 2000);
                    }
                });
            });

            // Initialize table scrolling indicators
            initializeTableScrolling();
        });

        function scrollToTop(tableId) {
            const tableContainer = document.querySelector(`#${tableId}`).closest('.table-container');
            tableContainer.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function initializeTableScrolling() {
            const tableContainers = document.querySelectorAll('.table-container');
            
            tableContainers.forEach(container => {
                container.addEventListener('scroll', function() {
                    if (this.scrollTop > 100) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            });
        }
    </script>

    <style>
        .mechanizations-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Table Styles */
        .table-container {
            position: relative;
            max-height: 70vh;
            overflow: auto;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        .sticky-header th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Year Cell Styles */
        .year-cell {
            border-right: 2px solid #dee2e6;
            vertical-align: middle !important;
        }

        .year-content {
            padding: 0.5rem;
        }

        .year-total {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }

        .year-total small {
            font-size: 0.7rem;
        }

        /* Equipment icon */
        .equipment-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Table row styling */
        .table td {
            padding: 0.6rem;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.85rem;
        }

        /* Loan information styling */
        .loan-info .loan-item {
            border-left: 3px solid #198754;
            padding-left: 8px;
        }

        .loan-info .loan-item:not(:last-child) {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }

        /* Performance Cards */
        .performance-card {
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .performance-card:hover {
            transform: translateY(-5px);
        }

        .performance-growth {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
        }

        .performance-decline {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #f5c6cb;
        }

        .performance-new {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
        }

        .performance-total {
            background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
            border: 1px solid #b3d9ff;
        }

        .performance-value {
            margin: 0.5rem 0;
        }

        .performance-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .action-btn {
            font-size: 0.7rem;
            padding: 0.3rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            min-width: 30px;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Scrollbar */
        .table-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-container {
                font-size: 0.75rem;
            }
            
            .action-btn {
                font-size: 0.65rem;
                padding: 0.25rem;
            }
            
            .year-value {
                font-size: 0.9rem !important;
            }
            
            .performance-card {
                padding: 1rem;
            }
        }

        /* Animations */
        .mdi-loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Hover effects */
        .hover-row {
            transition: all 0.3s ease;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Button styles */
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #ff8c00 0%, #ff5722 100%);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
        }

        /* Ensure proper vertical alignment for rowspan cells */
        .year-cell {
            vertical-align: middle !important;
        }

        /* Fix for rowspan borders */
        .table > tbody > tr > td {
            border: 1px solid #dee2e6;
        }
    </style>
</x-layout>