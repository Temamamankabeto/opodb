<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-success mb-3">Add Job Opportunity</h3>

                <form action="{{ route('job.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Job Name</label>
                        <input type="text" name="name" class="form-control" required>

                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('job.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </section>
    </div>
</x-layout>