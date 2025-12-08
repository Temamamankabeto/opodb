<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Cultural Revival</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Cultural Revival Activity</h3>
                            </div>
                            
                            <form action="{{ route('cultural_revival.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Name</label>
                                        <input type="text" name="name" value="{{ old('name', $item->name) }}" class="form-control" required>
                                        @error('name') 
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Unit</label>
                                        <input type="text" name="unit" value="{{ old('unit', $item->unit) }}" class="form-control" placeholder="e.g., kg, liter, person, etc.">
                                        @error('unit') 
                                            <span class="text-danger">{{ $message }}</span> 
                                        @enderror
                                        <small class="text-muted">Optional: Enter the unit of measurement for this cultural activity</small>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                    <a href="{{ route('cultural_revival.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
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