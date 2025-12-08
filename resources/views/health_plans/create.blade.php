<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Health Plan</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Health Plan Information</h3>
                            </div>
                            
                            <form action="{{ route('sector.health_plans.store') }}" method="POST">
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
                                            <h5>Health Activities</h5>
                                            <p class="text-muted">Select activities and fill in the details</p>
                                        </div>
                                    </div>

                                    @foreach($activities as $item)
                                        <div class="card card-outline card-info mb-3">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="health_activity_name[{{ $item->id }}][selected]" 
                                                           value="1" 
                                                           id="activity_{{ $item->id }}"
                                                           class="form-check-input activity-checkbox">
                                                    <label class="form-check-label font-weight-bold" for="activity_{{ $item->id }}">
                                                        {{ $item->name }}
                                                        @if($item->type)
                                                            <small class="text-muted">({{ $item->type }})</small>
                                                        @endif
                                                    </label>
                                                </div>
                                                
                                                <div class="row mt-3 activity-fields" style="display: none;">
                                                    <!-- Hidden type field -->
                                                    <input type="hidden" 
                                                           name="health_activity_name[{{ $item->id }}][type]" 
                                                           value="{{ $item->type }}">
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Amount <small class="text-muted">(Unit: {{ $item->unit }})</small></label>
                                                            <div class="input-group">
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       placeholder="Enter amount" 
                                                                       name="health_activity_name[{{ $item->id }}][amount]" 
                                                                       class="form-control">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">{{ $item->unit }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Budget <small class="text-muted">(in Birr)</small></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">ETB</span>
                                                                </div>
                                                                <input type="number" 
                                                                       step="0.01" 
                                                                       placeholder="Enter budget" 
                                                                       name="health_activity_name[{{ $item->id }}][budget]" 
                                                                       class="form-control">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">Birr</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @error('health_activity_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Health Plan
                                    </button>
                                    <a href="{{ route('sector.health_plans.index') }}" class="btn btn-default">
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
        // Show/hide amount and budget fields when checkbox is checked
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
 