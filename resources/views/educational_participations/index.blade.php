<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Educational Participations</h1>
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
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Educational Participations List</h3>
                                <div class="card-tools">
                                    <a href="{{ route('educational_participations.create') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus"></i> Add New
                                    </a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Student Type</th>
                                            <th width="20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $i => $item)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @php
                                                        $typeColors = [
                                                            'online' => 'badge bg-primary',
                                                            'offline' => 'badge bg-success',
                                                            'hybrid' => 'badge bg-warning'
                                                        ];
                                                    @endphp
                                                    <span class="{{ $typeColors[$item->type] ?? 'badge bg-secondary' }}">
                                                        {{ ucfirst($item->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $studentTypeColors = [
                                                            'regular' => 'badge bg-info',
                                                            'distance' => 'badge bg-purple',
                                                            'evening' => 'badge bg-dark',
                                                            'other' => 'badge bg-secondary'
                                                        ];
                                                    @endphp
                                                    <span class="{{ $studentTypeColors[$item->student_type] ?? 'badge bg-secondary' }}">
                                                        {{ ucfirst($item->student_type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('educational_participations.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <form action="{{ route('educational_participations.destroy', $item->id) }}"
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure?')">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
</x-layout>