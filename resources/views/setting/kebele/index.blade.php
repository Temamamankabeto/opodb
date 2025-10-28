<x-layout>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3><i class="bi bi-building-fill"></i> Gandoota </h3>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Aanaa </th>
                    <th>Gandoota</th>
                    <th>Gocha</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($woredas as $woreda)
                    @php $kebeleCount = $woreda->kebeles->count(); @endphp

                    @foreach ($woreda->kebeles as $index => $kebele)
                        <tr>
                            @if ($index == 0)
                                <td rowspan="{{ $kebeleCount }}">{{ $loop->parent->iteration }}</td>
                                <td rowspan="{{ $kebeleCount }}">{{ $woreda->name }}</td>
                            @endif
                            <td>{{ $kebele->name }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if ($kebeleCount === 0)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $woreda->name }}</td>
                            <td colspan="2">No kebeles found</td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="4">No Woredas found.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $woredas->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-layout>
