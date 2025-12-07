<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add Institution</h3>
                            </div>
                            
                            <form action="{{ route('institutions.store') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Institution Name *</label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="Enter institution name" 
                                               value="{{ old('name') }}" 
                                               required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Institution Type *</label>
                                        <select name="institution_type_id" 
                                                class="form-control @error('institution_type_id') is-invalid @enderror" 
                                                required>
                                            <option value="">-- Select Type --</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}" @selected(old('institution_type_id') == $type->id)>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('institution_type_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                    <a href="{{ route('institutions.index') }}" class="btn btn-default">
                                        Cancel
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