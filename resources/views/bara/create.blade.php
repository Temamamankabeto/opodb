<x-layout>
    <title>Create Yearly Crop Plan</title>

    <div class="container mt-5">
        <div class="card shadow rounded">
            <div class="card-header bg-shadow text-dark">
                <h5 class="mb-0">Create New Yearly Crop Plan</h5>
            </div>
            @if ($errors->any())
    <div class="alert alert-danger">
        <strong>There were some problems with your input:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <div class="card-body">
                <form action="{{ route('yearly_crop_plans.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="production_year" class="form-label">Proction Year</label>
                            <input type="text" name="production_year" class="form-control @error('production_year') is-invalid @enderror" value="{{ old('production_year') }}">
                            @error('plan_year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="production_year" class="form-label">plan Year</label>
                            <input type="text" name="plan_year" class="form-control @error('production_year') is-invalid @enderror" value="{{ old('production_year') }}">
                            @error('plan_year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('yearly_crop_plans.index') }}" class="btn btn-secondary">Back</a>
                        <button class="btn btn-success">Save year</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
