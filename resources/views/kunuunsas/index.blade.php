<!-- resources/views/kunuunsas/index.blade.php -->
<x-layout>
    <div class="container">
        <h2>Kunuunsa Records</h2>
        <a href="{{ route('kunuunsas.create') }}" class="btn btn-primary mb-3">Add New Kunuunsa</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lafa Harkaan Aramame</th>
                    <th>Lafa Keemikaalaan Aramame</th>
                    <th>Hamma Keemikaalaa Litiraa</th>
                    <th>Lafa Dhibeen Tooatame</th>
                    <th>Lafa Ilbiisni Tooatame</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kunuunsas as $kunuunsa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kunuunsa->lafa_harkaan_aramame }}</td>
                        <td>{{ $kunuunsa->lafa_keemikaalaan_aramame }}</td>
                        <td>{{ $kunuunsa->hamma_keemikaalaa_litiraa }}</td>
                        <td>{{ $kunuunsa->lafa_dhibeen_tooatame }}</td>
                        <td>{{ $kunuunsa->lafa_ilbiisni_toatame }}</td>
                        <td>
                            <a href="{{ route('kunuunsas.edit', $kunuunsa->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            
                            <!-- Add the 'delete-form' class to the form for SweetAlert -->
                            <form action="{{ route('kunuunsas.destroy', $kunuunsa->id) }}" method="POST" class="delete-form" style="display:inline-block;">
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

    <!-- Add SweetAlert confirmation script -->
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
