<x-layout>
    @section('title', 'Edit Association Type')
    
    <div class="content-header">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-warning">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Association Type
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if ($errors->any())
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

                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-2"></i>
                            Edit Association Type
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('association_types.update', $type->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold">Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="Enter type name" 
                                               value="{{ old('name', $type->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i> Update Type
                                </button>
                                <a href="{{ route('association_types.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .card-success:not(.card-outline) > .card-header {
            background-color: #28a745;
            color: white;
        }
        
        .card-success:not(.card-outline) > .card-header .card-title {
            color: white;
        }
        
        /* Ensure all card headers are green */
        .card > .card-header {
            background-color: #28a745 !important;
            color: white !important;
        }
        
        .card > .card-header .card-title {
            color: white !important;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }
        
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>
</x-layout>