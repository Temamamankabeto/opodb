<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Registration of Civil Certificates</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Civil Certificates</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Civil Certificates List</h3>
                                <div class="card-tools">
                                    <a href="{{ route('civil.create') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Add New
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th width="80">ID</th>
                                                <th>Name</th>
                                                <th width="150">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($items as $i)
                                                <tr>
                                                    <td>{{ $i->id }}</td>
                                                    <td>{{ $i->name }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('civil.edit', $i->id) }}" 
                                                               class="btn btn-warning btn-sm"
                                                               title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            
                                                            <form action="{{ route('civil.destroy', $i->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-danger btn-sm" 
                                                                        onclick="return confirm('Are you sure you want to delete this civil certificate?')"
                                                                        title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                        <div class="text-muted py-4">
                                                            <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                                            <p>No civil certificates found.</p>
                                                            <a href="{{ route('civil.create') }}" class="btn btn-success">
                                                                <i class="fas fa-plus"></i> Add Your First Certificate
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
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>

<style>
    .btn-group {
        display: flex;
        gap: 5px;
    }
    
    .btn-group form {
        margin: 0;
    }
    
    .table thead.thead-dark {
        background-color: #343a40;
        color: white;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
    
    .card-tools {
        float: right;
        margin-right: -.625rem;
    }
    
    .card-tools .btn {
        margin-left: .5rem;
    }
</style>

 
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[title]').tooltip();
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
 