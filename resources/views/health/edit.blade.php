<x-layout>
<div class="content-header">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Health</h1>
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

                <form action="{{ route('health_plans.update', $item->id) }}" method="POST">
                    @csrf
                    

                    {{-- Name --}}
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $item->name) }}"
                               class="form-control"
                               required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Unit --}}
                    <div class="form-group">
                        <label>Unit</label>
                        <input type="text"
                               name="unit"
                               value="{{ old('unit', $item->unit) }}"
                               class="form-control"
                               required>
                        @error('unit')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Type Dropdown --}}
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                            <option value="dhimma Daa’immanii" {{ $item->type == 'dhimma Daa’immanii' ? 'selected' : '' }}>
                                dhimma Daa’immanii
                            </option>

                            <option value="dhimma haadholee" {{ $item->type == 'dhimma haadholee' ? 'selected' : '' }}>
                                dhimma haadholee
                            </option>

                            <option value="other" {{ $item->type == 'other' ? 'selected' : '' }}>
                                other
                            </option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Update</button>
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
