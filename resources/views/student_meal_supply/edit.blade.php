<x-layout>
    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Student Meal Supply</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('student_meal_supply.index') }}">Student Meal Supply</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Student Meal Supply</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('student_meal_supply.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ammount">Hanga Nyaataa Dhiyaatu *</label>
                                                <div class="input-group">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           name="ammount" 
                                                           id="ammount"
                                                           value="{{ old('ammount', $item->ammount) }}" 
                                                           class="form-control @error('ammount') is-invalid @enderror" 
                                                           placeholder="Enter amount" 
                                                           required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Kunt</span>
                                                    </div>
                                                </div>
                                                @error('ammount')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cost">Cost *</label>
                                                <div class="input-group">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           name="cost" 
                                                           id="cost"
                                                           value="{{ old('cost', $item->cost) }}" 
                                                           class="form-control @error('cost') is-invalid @enderror" 
                                                           placeholder="Enter cost" 
                                                           required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Birr</span>
                                                    </div>
                                                </div>
                                                @error('cost')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="source">Source *</label>
                                                <select name="source" 
                                                        id="source"
                                                        class="form-control @error('source') is-invalid @enderror" 
                                                        required>
                                                    <option value="">Select Source</option>
                                                    <option value="Kan manni barumsaa oomishe" {{ old('source', $item->source) == 'Kan manni barumsaa oomishe' ? 'selected' : '' }}>
                                                        Kan manni barumsaa oomishe
                                                    </option>
                                                    <option value="Kan hawaasarraa sassaabame" {{ old('source', $item->source) == 'Kan hawaasarraa sassaabame' ? 'selected' : '' }}>
                                                        Kan hawaasarraa sassaabame
                                                    </option>
                                                </select>
                                                @error('source')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden fields to store units -->
                                    <input type="hidden" name="unit" value="Kunt">
                                    <input type="hidden" name="cost_unit" value="Birr">
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                    <a href="{{ route('student_meal_supply.index') }}" class="btn btn-secondary">
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