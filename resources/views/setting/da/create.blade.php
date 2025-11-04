<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Add New Kebele Sector</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('sectors.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Sector Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter sector name" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Sector
                            </button>
                        </form>

                    </div>
                </div>

                <!-- Optional: Display user's current area info -->
                <div class="mt-4 p-3 bg-light border rounded">
                    <h6 class="text-muted mb-2"><i class="bi bi-geo-alt"></i> Your Current Location</h6>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Region:</strong> {{ Auth::user()->region->name ?? 'N/A' }}</li>
                        <li><strong>Zone:</strong> {{ Auth::user()->zone->name ?? 'N/A' }}</li>
                        <li><strong>Woreda:</strong> {{ Auth::user()->woreda->name ?? 'N/A' }}</li>
                        <li><strong>Kebele:</strong> {{ Auth::user()->kebele->name ?? 'N/A' }}</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-layout>