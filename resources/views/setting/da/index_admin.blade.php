<x-layout>
    <div class="container py-5">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Agricultural DA Users ({{ Auth::user()->kebele->name ?? 'N/A' }})</h4>
                <a href="{{ route('dausers.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Add DA User
                </a>
            </div>

            <div class="card-body">
                @if ($dausers->isEmpty())
                    <div class="alert alert-info mb-0">
                        No Agricultural DA users found for this kebele.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-success">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Sector</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dausers as $index => $dauser)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $dauser->name }}</td>
                                        <td>{{ $dauser->email }}</td>
                                        <td>{{ $dauser->phone }}</td>
                                        <td class="text-capitalize">{{ $dauser->gender ?? '-' }}</td>
                                        <td>
                                            {{ $dauser->sector?->sector?->name ?? ($dauser->sector?->name ?? 'N/A') }}
                                        </td>
                                        <td>{{ $dauser->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('dausers.edit', $dauser->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>


                                                <form action="{{ route('dausers.destroy', $dauser->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this DA user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
