<x-layout>
    <div class="container">
        <h2>Haamaa Records</h2>
        <a href="{{ route('haamaas.create') }}" class="btn btn-primary mb-3">Add New Haamaa</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lafa Maashinaan Haamame</th>
                    <th>Maashina Haamaa Hirmaate</th>
                    <th>Lafa Harkaan Haamame</th>
                    <th>Qotee Bulaa Dhi</th>
                    <th>Qotee Bulaa Du</th>
                    <th>Kan Tumame</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($haamaas as $haamaa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $haamaa->lafa_maashinaan_haamame }}</td>
                        <td>{{ $haamaa->maashina_haamaa_hirmaate }}</td>
                        <td>{{ $haamaa->lafa_harkaan_haamame }}</td>
                        <td>{{ $haamaa->qotee_bulaa_dhi_haamame }}</td>
                        <td>{{ $haamaa->qotee_bulaa_du_haamame }}</td>
                        <td>{{ $haamaa->kan_tumame }}</td>
                        <td>
                            <a href="{{ route('haamaas.edit', $haamaa->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('haamaas.destroy', $haamaa->id) }}" method="POST" style="display:inline-block;" class="delete-form">
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
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if the user confirmed
                    }
                });
            });
        });
    </script>
</x-layout>
