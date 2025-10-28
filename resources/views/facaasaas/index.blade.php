<x-layout>
    <div class="container">
        <h2>Facaasaa Records</h2>
        <a href="{{ route('facaasaas.create') }}" class="btn btn-primary mb-3">Add New Facaasaa</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lafa Facae</th>
                    <th>Mala Facaasaa</th>
                    <th>Qonnaan Bulaa Dhi</th>
                    <th>Qonnaan Bulaa Du</th>
                    <th>Qonnaan Bulaa Idaama</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facaasaas as $facaasaa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $facaasaa->lafa_facae }}</td>
                        <td>{{ $facaasaa->mala_facaasaa }}</td>
                        <td>{{ $facaasaa->qonnaan_bulaa_dhi }}</td>
                        <td>{{ $facaasaa->qonnaan_bulaa_du }}</td>
                        <td>{{ $facaasaa->qonnaan_bulaa_idaama }}</td>
                        <td>
                            <a href="{{ route('facaasaas.edit', $facaasaa->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('facaasaas.destroy', $facaasaa->id) }}" method="POST" class="delete-form" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add SweetAlert confirmation before form submission
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting immediately
                const form = this;
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if the user confirmed
                    }
                });
            });
        });
    </script>
</x-layout>
