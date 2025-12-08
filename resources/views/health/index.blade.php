<x-layout>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Health Items List</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('health.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Item
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    @php
                        // Group items by type in the view
                        $groupedItems = $items->groupBy('type');
                    @endphp
                    
                    @if($groupedItems->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">No health items found. <a href="{{ route('health.create') }}">Add your first item</a></p>
                        </div>
                    @else
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="70">ID</th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Type</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($groupedItems as $type => $group)
                                    <!-- Group Header -->
                                    <tr class="bg-light">
                                        <td colspan="5" class="font-weight-bold">
                                            <i class="fas fa-folder mr-2"></i>{{ ucfirst($type) }} Category
                                        </td>
                                    </tr>

                                    @foreach($group as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @if($item->unit)
                                                    <span class="badge badge-info">{{ $item->unit }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $item->type }}</span>
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('health.edit', $item->id) }}"
                                                       class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('health.delete', $item->id) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm delete-btn" 
                                                                data-id="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    const itemName = this.getAttribute('data-name');
                    
                    if (confirm(`Are you sure you want to delete "${itemName}"?`)) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush

</x-layout>