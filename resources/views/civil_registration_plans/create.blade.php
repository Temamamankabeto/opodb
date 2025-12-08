<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Civil Registration Plan</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        {{ session('error') }}
                    </div>
                @endif

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
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Civil Registration Plan Information</h3>
                            </div>
                            
                            <form action="{{ route('sector.civil_registration_plans.store') }}" method="POST">
                                @csrf
                                
                                <div class="card-body">
                                    <!-- Year Selection -->
                                    <div class="form-group">
                                        <label for="yearSelect" class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control @error('year') is-invalid @enderror" 
                                                id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                                    {{ $year }} EC
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Civil Registration Activities</h5>
                                            <p class="text-muted">Select activities and fill in the details</p>
                                        </div>
                                    </div>

                                    @foreach($activities as $item)
                                        @php
                                            $selected = old("civil_registration_activities.{$item->id}.selected");
                                            $quantity = old("civil_registration_activities.{$item->id}.quantity", '');
                                        @endphp
                                        
                                        <div class="card card-outline card-info mb-3">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="civil_registration_activities[{{ $item->id }}][selected]" 
                                                           value="1" 
                                                           id="activity_{{ $item->id }}"
                                                           class="form-check-input activity-checkbox"
                                                           {{ $selected ? 'checked' : '' }}>
                                                    <label class="form-check-label font-weight-bold" for="activity_{{ $item->id }}">
                                                        {{ $item->name }}
                                                        @if($item->unit)
                                                            <small class="text-muted">(Unit: {{ $item->unit }})</small>
                                                        @endif
                                                    </label>
                                                </div>
                                                
                                                <div class="row mt-3 activity-fields" style="{{ $selected ? 'display: block;' : 'display: none;' }}">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Quantity <small class="text-muted">(Unit: {{ $item->unit }})</small></label>
                                                            <div class="input-group">
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       placeholder="Enter quantity" 
                                                                       name="civil_registration_activities[{{ $item->id }}][quantity]"
                                                                       value="{{ $quantity }}"
                                                                       class="form-control"
                                                                       min="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">{{ $item->unit }}</span>
                                                                </div>
                                                            </div>
                                                            @error("civil_registration_activities.{$item->id}.quantity")
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @error('civil_registration_activities')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Civil Registration Plan
                                    </button>
                                    <a href="{{ route('sector.civil_registration_plans.index') }}" class="btn btn-default">
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

 
<script>
    $(document).ready(function() {
        // Show/hide quantity fields when checkbox is checked
        $('.activity-checkbox').change(function() {
            var fields = $(this).closest('.card-body').find('.activity-fields');
            if ($(this).is(':checked')) {
                fields.show('fast');
            } else {
                fields.hide('fast');
                fields.find('input[type="number"]').val(''); // Clear values when unchecked
            }
        });

        // Initialize visibility for old values
        $('.activity-checkbox').each(function() {
            if ($(this).is(':checked')) {
                $(this).closest('.card-body').find('.activity-fields').show();
            }
        });
    });
</script>
 