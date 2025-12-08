<x-layout>
<div class="content-header">

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Health</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            
            <div class="card card-primary">
              <div class="card-body">
                <form action="{{ route('health.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Unit</label>
                        <input type="text" name="unit" value="{{ old('unit') }}" class="form-control" required>
                        @error('unit') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                            <option value="">-- Select Type --</option>
                            <option value="dhimma Daa’immanii">dhimma Daa’immanii</option>
                            <option value="dhimma haadholee">dhimma haadholee</option>
                            <option value="other">other</option>
                        </select>
                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <button class="btn btn-success">Save</button>
                    <a href="{{ route('health.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </section>
</div>
</x-layout>
