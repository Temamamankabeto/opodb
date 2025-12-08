<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Kebele Asset Plan</h1>
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
                                <h3 class="card-title">Edit Kebele Asset Plan Information</h3>
                            </div>
                            
                            <form action="{{ route('sector.kebele-asset-plans.update', $plan->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="card-body">
                                    <!-- Year Selection -->
                                    <div class="form-group">
                                        <label for="yearSelect" class="font-weight-bold">Year <span class="text-danger">*</span></label>
                                        <select name="year" class="form-control @error('year') is-invalid @enderror" 
                                                id="yearSelect" required>
                                            <option value="">-- Select Year --</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" {{ old('year', $plan->year) == $year ? 'selected' : '' }}>
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
                                            <h5>Government Properties</h5>
                                            <p class="text-muted">Select properties and fill in the details</p>
                                        </div>
                                    </div>

                                    @foreach($activities as $item)
                                        @php
                                            $saved = $plan->kebele_asset_plan[$item->id] ?? null;
                                            $selected = old("kebele_asset_plan.{$item->id}.selected", $saved ? '1' : '');
                                        @endphp
                                        
                                        <div class="card card-outline card-info mb-3">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="kebele_asset_plan[{{ $item->id }}][selected]" 
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
                                                    <!-- Galii Mootummaa - Amount only -->
                                                    @if($item->name == 'Galii Mootummaa')
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Amount <small class="text-muted">(in Birr)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter amount" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][amount]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.amount", $saved['amount'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.amount")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    
                                                    <!-- Buusaa Gonofaa - Multiple fields -->
                                                    @elseif($item->name == 'Buusaa Gonofaa')
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Miseensa Buusaa Gonofaa <small class="text-muted">(in lakk)</small></label>
                                                                <div class="input-group">
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter members" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][members]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.members", $saved['members'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">lakk</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.members")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Buusii Miseensummaa Sassaabame <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter membership fee" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][membership_fee]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.membership_fee", $saved['membership_fee'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.membership_fee")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Gumaata Buusaa Gonofaa midhaan</label>
                                                                <input type="text" 
                                                                       placeholder="Enter contribution in kind" 
                                                                       name="kebele_asset_plan[{{ $item->id }}][contribution_kind]"
                                                                       value="{{ old("kebele_asset_plan.{$item->id}.contribution_kind", $saved['contribution_kind'] ?? '') }}"
                                                                       class="form-control">
                                                                @error("kebele_asset_plan.{$item->id}.contribution_kind")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Gumaata Buusaa Gonofaa Maallaqatti yoo jijjiiramu <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter monetary contribution" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][monetary_contribution]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.monetary_contribution", $saved['monetary_contribution'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.monetary_contribution")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    
                                                    <!-- Gaachena Sirnaa - Two fields -->
                                                    @elseif($item->name == 'Gaachena Sirnaa')
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Nama Leenji'e <small class="text-muted">(in lakk)</small></label>
                                                                <div class="input-group">
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter transferred persons" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][transferred_persons]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.transferred_persons", $saved['transferred_persons'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">lakk</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.transferred_persons")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Guyya-guyyaan nama bobbii fudhatu <small class="text-muted">(in lakk)</small></label>
                                                                <div class="input-group">
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter daily served persons" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][daily_served_persons]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.daily_served_persons", $saved['daily_served_persons'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">lakk</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.daily_served_persons")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    
                                                    <!-- Hojiiwwan Tajaajila Lammummaa - Amount only -->
                                                    @elseif($item->name == 'Hojiiwwan Tajaajila Lammummaa')
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Amount <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter amount" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][amount]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.amount", $saved['amount'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.amount")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    
                                                    <!-- Fudhannaa Liqi - Three fields -->
                                                    @elseif($item->name == 'Fudhannaa Liqi')
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Kan bara kana fudhatame <small class="text-muted">(in ETC)</small></label>
                                                                <div class="input-group">
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter amount received" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][received_this_year]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.received_this_year", $saved['received_this_year'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">ETC</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.received_this_year")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Kan bara kana deebi'e <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter amount returned" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][returned_this_year]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.returned_this_year", $saved['returned_this_year'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.returned_this_year")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Kan Hin Deebiín <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter amount not returned" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][not_returned]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.not_returned", $saved['not_returned'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.not_returned")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    
                                                    <!-- Qusannaa Baankii Siinqee - Two fields -->
                                                    @elseif($item->name == 'Qusannaa Baankii Siinqee')
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Kan Maatii <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter family amount" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][family_amount]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.family_amount", $saved['family_amount'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.family_amount")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Kan Waldaa <small class="text-muted">(in ETB)</small></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">ETB</span>
                                                                    </div>
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter association amount" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][association_amount]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.association_amount", $saved['association_amount'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Birr</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.association_amount")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    
                                                    <!-- Default case for other properties -->
                                                    @else
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Quantity <small class="text-muted">(Unit: {{ $item->unit }})</small></label>
                                                                <div class="input-group">
                                                                    <input type="number" 
                                                                           step="0.01" 
                                                                           placeholder="Enter quantity" 
                                                                           name="kebele_asset_plan[{{ $item->id }}][quantity]"
                                                                           value="{{ old("kebele_asset_plan.{$item->id}.quantity", $saved['quantity'] ?? '') }}"
                                                                           class="form-control"
                                                                           min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">{{ $item->unit }}</span>
                                                                    </div>
                                                                </div>
                                                                @error("kebele_asset_plan.{$item->id}.quantity")
                                                                    <span class="text-danger small">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @error('kebele_asset_plan')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Kebele Asset Plan
                                    </button>
                                    <a href="{{ route('sector.kebele-asset-plans.index') }}" class="btn btn-default">
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
        // Show/hide fields when checkbox is checked
        $('.activity-checkbox').change(function() {
            var fields = $(this).closest('.card-body').find('.activity-fields');
            if ($(this).is(':checked')) {
                fields.show('fast');
            } else {
                fields.hide('fast');
                // Don't clear values during validation errors
                if (!$('.alert-danger').length) {
                    fields.find('input[type="number"], input[type="text"]').val('');
                }
            }
        });

        // Initialize visibility for old values
        $('.activity-checkbox').each(function() {
            if ($(this).is(':checked')) {
                $(this).closest('.card-body').find('.activity-fields').show();
            } else {
                // Also check if fields have values (for validation errors)
                var hasValues = false;
                $(this).closest('.card-body').find('.activity-fields').find('input').each(function() {
                    if ($(this).val() !== '') {
                        hasValues = true;
                        return false;
                    }
                });
                
                if (hasValues) {
                    $(this).prop('checked', true);
                    $(this).closest('.card-body').find('.activity-fields').show();
                }
            }
        });
    });
</script>