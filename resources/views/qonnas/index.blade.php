<x-layout>
    <div class="container">
        <h2>Galmee Daataa Qonna </h2>
        <a href="{{ route('qonnas.create') }}" class="btn btn-primary mb-3">Haaraa Dabali</a>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Haala Qonnaa</th>
                    <th>Lafa Qotame</th>
                    <th>Sadarkaa Qonnaa</th>
                    <th>Traaktara Hirmaate</th>
                    <th>Lafa Tiraakta Tokkoon Qotame</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($qonnas as $qonna)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $qonna->haala_qonnaa }}</td>
                        <td>{{ $qonna->lafa_qotame }}</td>
                        <td>{{ $qonna->sadarkaa_qonnaa }}</td>
                        <td>{{ $qonna->traaktara_hirmaate }}</td>
                        <td>{{ $qonna->lafa_tiraakta_tokkoon_qotame }}</td>
                        <td>
                            <a href="{{ route('qonnas.edit', $qonna->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('qonnas.destroy', $qonna->id) }}" method="POST" style="display:inline-block;" class="delete-form">
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
