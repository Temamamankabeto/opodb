<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Barnoota Reports</h1>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-check"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Barnoota List</h3>
                                <div class="card-tools">
                                    <a href="{{ route('sector.barnoota.create') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Add New Barnoota
                                    </a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Year</th>
                                                <th>Hirmaannaa Items</th>
                                                <th>Bu'aa Items</th>
                                                <th>Nyaata Items</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($barnootas as $barnoota)
                                                @php
                                                    // Decode JSON data
                                                    $hirmaannaaData = json_decode($barnoota->Hirmaannaa_itti_fufiinsa, true) ?? [];
                                                    $buaaData = json_decode($barnoota->Bu_aa_qabeessummaa, true) ?? [];
                                                    $nyaataData = json_decode($barnoota->Dhiyeesii_nyaata_barattootaa, true) ?? [];
                                                @endphp
                                                <tr>
                                                    <td>{{ $barnoota->year }}</td>
                                                    
                                                    <!-- Hirmaannaa Items Column -->
                                                    <td>
                                                        @if(count($hirmaannaaData) > 0)
                                                            <table class="table table-sm mb-0">
                                                                <tbody>
                                                                    @foreach($hirmaannaaData as $item)
                                                                        <tr>
                                                                            <td class="border-0 p-1">
                                                                                <small>
                                                                                    {{ $item['name'] ?? 'Item' }}: 
                                                                                    {{ number_format($item['amount'] ?? 0, 2) }} Lakk
                                                                                    ({{ ucfirst($item['student_type'] ?? 'N/A') }})
                                                                                </small>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <span class="text-muted">No items</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <!-- Bu'aa Items Column -->
                                                    <td>
                                                        @if(count($buaaData) > 0)
                                                            <table class="table table-sm mb-0">
                                                                <tbody>
                                                                    @foreach($buaaData as $item)
                                                                        <tr>
                                                                            <td class="border-0 p-1">
                                                                                <small>
                                                                                    {{ $item['name'] ?? 'Item' }}: 
                                                                                    {{ number_format($item['amount'] ?? 0, 2) }} Lakk
                                                                                    ({{ ucfirst($item['student_type'] ?? 'N/A') }})
                                                                                </small>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <span class="text-muted">No items</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <!-- Nyaata Items Column -->
                                                    <td>
                                                        @if(count($nyaataData) > 0)
                                                            <table class="table table-sm mb-0">
                                                                <tbody>
                                                                    @foreach($nyaataData as $item)
                                                                        <tr>
                                                                            <td class="border-0 p-1">
                                                                                <small>
                                                                                    {{ number_format($item['amount'] ?? 0, 2) }} Kunt 
                                                                                    ({{ $item['source'] ?? 'N/A' }}) - 
                                                                                    {{ number_format($item['cost'] ?? 0, 2) }} Birr
                                                                                </small>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <span class="text-muted">No items</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <!-- Status Column -->
                                                    <td>
                                                        @if($barnoota->status == 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif($barnoota->status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <!-- Actions Column -->
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" 
                                                                    class="btn btn-info btn-sm" 
                                                                    title="View Details"
                                                                    data-toggle="modal" 
                                                                    data-target="#viewModal{{ $barnoota->id }}">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <a href="{{ route('sector.barnoota.edit', $barnoota->id) }}" 
                                                               class="btn btn-primary btn-sm" 
                                                               title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('sector.barnoota.destroy', $barnoota->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-danger btn-sm" 
                                                                        title="Delete"
                                                                        onclick="return confirm('Are you sure you want to delete this barnoota?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        <i class="fas fa-database fa-3x mb-3"></i>
                                                        <h5>No Barnoota Reports Found</h5>
                                                        <p>Click the "Add New Barnoota" button to create your first report.</p>
                                                        <a href="{{ route('sector.barnoota.create') }}" class="btn btn-success">
                                                            <i class="fas fa-plus"></i> Create First Report
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    {{-- View Modals --}}
    @foreach($barnootas as $barnoota)
        @php
            $hirmaannaaData = json_decode($barnoota->Hirmaannaa_itti_fufiinsa, true) ?? [];
            $buaaData = json_decode($barnoota->Bu_aa_qabeessummaa, true) ?? [];
            $nyaataData = json_decode($barnoota->Dhiyeesii_nyaata_barattootaa, true) ?? [];
            $totalCost = array_sum(array_column($nyaataData, 'cost'));
        @endphp

        <div class="modal fade" id="viewModal{{ $barnoota->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-info">
                        <h5 class="modal-title">Barnoota Report Details</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        {{-- Basic Information --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <th width="40%">Report ID:</th>
                                        <td>{{ $barnoota->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Year:</th>
                                        <td>{{ $barnoota->year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $barnoota->status == 'approved' ? 'success' :
                                                ($barnoota->status == 'rejected' ? 'danger' : 'warning')
                                            }}">
                                                {{ ucfirst($barnoota->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $barnoota->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <th>Region:</th>
                                        <td>{{ $barnoota->region->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Zone:</th>
                                        <td>{{ $barnoota->zone->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Woreda:</th>
                                        <td>{{ $barnoota->woreda->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kebele:</th>
                                        <td>{{ $barnoota->kebele->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sector:</th>
                                        <td>{{ $barnoota->sector->name ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- Hirmaannaa --}}
                        <div class="card card-info mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Hirmaannaa fi Ittifufiinsa</h6>
                            </div>

                            <div class="card-body p-0">
                                @if(count($hirmaannaaData))
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Type</th>
                                                <th>Student Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($hirmaannaaData as $item)
                                            <tr>
                                                <td>{{ $item['name'] ?? 'N/A' }}</td>
                                                <td>{{ $item['type'] ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($item['student_type'] ?? 'N/A') }}</td>
                                                <td>{{ number_format($item['amount'] ?? 0, 2) }} Lakk</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="p-3 mb-0 text-muted">No data available</p>
                                @endif
                            </div>
                        </div>

                        {{-- Bu'aa --}}
                        <div class="card card-warning mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Bu'aa Qabeessummaa Barnootaa</h6>
                            </div>

                            <div class="card-body p-0">
                                @if(count($buaaData))
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Type</th>
                                                <th>Student Type</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($buaaData as $item)
                                            <tr>
                                                <td>{{ $item['name'] ?? 'N/A' }}</td>
                                                <td>{{ $item['type'] ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($item['student_type'] ?? 'N/A') }}</td>
                                                <td>{{ number_format($item['amount'] ?? 0, 2) }} Lakk</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="p-3 mb-0 text-muted">No data available</p>
                                @endif
                            </div>
                        </div>

                        {{-- Nyaata --}}
                        <div class="card card-success">
                            <div class="card-header">
                                <h6 class="mb-0">Dhiyeesii Nyaata Barattootaa</h6>
                            </div>

                            <div class="card-body p-0">
                                @if(count($nyaataData))
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Source</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($nyaataData as $item)
                                            <tr>
                                                <td>{{ number_format($item['amount'] ?? 0, 2) }} Kunt</td>
                                                <td>{{ $item['source'] ?? 'N/A' }}</td>
                                                <td>{{ number_format($item['cost'] ?? 0, 2) }} Birr</td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                        {{-- Total Cost --}}
                                        <tfoot>
                                            <tr>
                                                <th>Total:</th>
                                                <th></th>
                                                <th>{{ number_format($totalCost, 2) }} Birr</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @else
                                    <p class="p-3 mb-0 text-muted">No data available</p>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <a href="{{ route('sector.barnoota.edit', $barnoota->id) }}"
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Report
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Barnoota index page loaded');
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const closeButton = alert.querySelector('.close');
                    if (closeButton) {
                        closeButton.click();
                    }
                });
            }, 5000);
            
            // Initialize tooltips
            $('[title]').tooltip();
        });
    </script>
    @endpush
</x-layout>