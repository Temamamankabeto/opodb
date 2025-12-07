<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-success">
                            <i class="fas fa-tree mr-2"></i>Natural Resources
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Natural Resources List</h3>
                        <div class="card-tools">
                            <a href="{{ route('naturals.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus mr-1"></i> Add New Resource
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-success">
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($naturals as $resource)
                                    <tr>
                                        <td>{{ $resource->id }}</td>
                                        <td>{{ $resource->name }}</td>
                                        <td>
                                            @if($resource->unit)
                                                <span class="badge badge-info">{{ $resource->unit }}</span>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('naturals.edit', $resource->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <!-- Change this form to use DELETE method -->
                                            <form action="{{ route('naturals.destroy', $resource->id) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fas fa-tree fa-2x mb-2 d-block"></i>
                                            No natural resources found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>