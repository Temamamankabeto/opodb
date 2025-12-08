<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Kebele Government Property</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add New Property</h3>
                            </div>
                            
                            <form action="{{ route('govprop.store') }}" method="POST">
                                @csrf
                                
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Property Name</label>
                                        <input type="text" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               required
                                               placeholder="Enter property name">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Unit</label>
                                        <input type="text" 
                                               name="unit" 
                                               value="{{ old('unit') }}" 
                                               class="form-control"
                                               placeholder="e.g., m², piece, hectare, etc.">
                                        @error('unit')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Optional: Enter the unit of measurement</small>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                    <a href="{{ route('govprop.index') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>