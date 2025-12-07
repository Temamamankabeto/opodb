<x-layout>
    @section('title', 'Institutions')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-success">
                            <i class="fas fa-building mr-2"></i>
                            Institutions
                        </h1>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Institutions List
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('institutions.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus-circle mr-1"></i> Add New Institution
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-hover m-0">
                                <thead class="bg-success">
                                    <tr>
                                        <th width="10%" class="text-center">#</th>
                                        <th>Name</th>
                                        <th>Institution Type</th>
                                        <th width="25%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($institutions as $i => $institution)
                                        <tr>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ $institution->name }}</td>
                                            <td>
                                                @if($institution->type)
                                                    <span class="badge badge-info">{{ $institution->type->name }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('institutions.edit', $institution->id) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <form action="{{ route('institutions.destroy', $institution->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Delete this institution?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash mr-1"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-building fa-2x mb-2 d-block"></i>
                                                No institutions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .bg-success {
            background-color: #28a745 !important;
            color: white;
        }
        
        .table-responsive {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .badge-info {
            background-color: #17a2b8;
        }
        
        .table > tbody > tr > td {
            vertical-align: middle;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Card header styling */
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .card-title {
            color: #495057;
            font-weight: 600;
        }
        
        .card-success:not(.card-outline) > .card-header {
            background-color: #28a745;
            color: white;
        }
        
        .card-success:not(.card-outline) > .card-header .card-title {
            color: white;
        }
        
        /* Hover effect for table rows */
        .table-hover > tbody > tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        /* Scrollbar styling */
        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</x-layout>