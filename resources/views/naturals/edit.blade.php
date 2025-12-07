<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-success">
                            <i class="fas fa-edit mr-2"></i>Edit Natural Resource
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Edit Resource Details</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('naturals.update', $naturals->id) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- Keep this for PUT method -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Resource Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $naturals->name) }}" 
                                               required
                                               placeholder="Enter resource name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit" class="form-label">Default Unit</label>
                                        <select name="unit" 
                                                id="unit" 
                                                class="form-control @error('unit') is-invalid @enderror">
                                            <option value="">-- Select Unit --</option>
                                            <option value="M" {{ old('unit', $naturals->unit) == 'M' ? 'selected' : '' }}>M (Meter)</option>
                                            <option value="M2" {{ old('unit', $naturals->unit) == 'M2' ? 'selected' : '' }}>M² (Square Meter)</option>
                                            <option value="hec" {{ old('unit', $naturals->unit) == 'hec' ? 'selected' : '' }}>hec (Hectare)</option>
                                            <option value="Lakk" {{ old('unit', $naturals->unit) == 'Lakk' ? 'selected' : '' }}>Lakk</option>
                                            <option value="km" {{ old('unit', $naturals->unit) == 'km' ? 'selected' : '' }}>km (Kilometer)</option>
                                            <option value="km²" {{ old('unit', $naturals->unit) == 'km²' ? 'selected' : '' }}>km² (Square Kilometer)</option>
                                            <option value="ac" {{ old('unit', $naturals->unit) == 'ac' ? 'selected' : '' }}>ac (Acre)</option>
                                        </select>
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            This will be used as the default unit when creating plans
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save mr-2"></i> Update Resource
                                </button>
                                <a href="{{ route('naturals.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout>