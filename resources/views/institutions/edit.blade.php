<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Institution</h1>
                    </div>
                    <div class="col-sm-6">
                         
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Edit Institution Information</h3>
                            </div>
                            <!-- /.card-header -->
                            
                            <!-- form start -->
                            <form action="{{ route('institutions.update', $institution->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Institution Name *</label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name"
                                               value="{{ old('name', $institution->name) }}" 
                                               required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="institution_type_id">Institution Type *</label>
                                        <select name="institution_type_id" 
                                                class="form-control @error('institution_type_id') is-invalid @enderror" 
                                                id="institution_type_id"
                                                required>
                                            <option value="">-- Select Type --</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}" @selected(old('institution_type_id', $institution->institution_type_id) == $type->id)>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('institution_type_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                    <a href="{{ route('institutions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
</x-layout>