<x-layout>

<div class="content-header">

    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-12 text-center">
                    <h1 class="text-warning">
                        <i class="fas fa-seedling mr-2"></i>
                        {{ __('yearly plan') }}
                    </h1>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="content">

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> {{ __('validation.error_title') }}</h5>
                <ul class="pl-3 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if ($errors->has('crop_id'))
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-circle"></i> {{ __('crop_plan.duplicate_crop') }}</h5>
                <p>{!! $errors->first('crop_id') !!}</p>
            </div>
        @endif

        <div class="card card-warning">
            <div class="card-header">
                
            </div>

            <div class="card-body">

                <form action="{{ route('sector.crop-plans.update', $plan->id) }}" method="POST" id="cropPlanForm">
                    @csrf
                    @method('PUT')

                    <!-- BASIC INFO -->
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('Basic information') }}
                    </h5>

                    <div class="row">

                        <!-- Year -->
                        <div class="form-group col-md-4">
                            <label>{{ __('year') }}</label>
                            <input type="number" class="form-control" id="year" name="year"
                                   value="{{ $plan->year }}" required>
                        </div>

                        <!-- Crop Type -->
                        <div class="form-group col-md-4">
                            <label>{{ __('crop type') }}</label>
                            <select class="form-control" id="croptype_id" name="croptype_id" required>
                                <option value="">{{ __('common.select') }}</option>
                                @foreach ($cropTypes as $type)
                                    <option value="{{ $type->id }}" {{ $plan->croptype_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Crop -->
                        <div class="form-group col-md-4">
                            <label>{{ __('crop') }}</label>
                            <select class="form-control" id="crop_id" name="crop_id" required></select>

                            <small id="crop_loading" class="text-muted d-none">
                                <i class="fas fa-spinner fa-spin mr-1"></i> {{ __('common.loading') }}
                            </small>
                        </div>

                        <!-- Season -->
                        <div class="form-group col-md-4">
                            <label>{{ __('season') }}</label>
                            <select class="form-control" id="season" name="season" required>
                                <option value="">{{ __('common.select') }}</option>
                                <option value="Ganna" {{ $plan->season=='Ganna' ? 'selected' : '' }}>Ganna</option>
                                <option value="Bona" {{ $plan->season=='Bona' ? 'selected' : '' }}>Bona</option>
                                <option value="Arfaasaa" {{ $plan->season=='Arfaasaa' ? 'selected' : '' }}>Arfaasaa</option>
                            </select>
                        </div>

                        <!-- Area -->
                        <div class="form-group col-md-4">
                            <label>{{ __('area') }}</label>
                            <input type="number" step="0.01" class="form-control"
                                   name="area_to_plant" id="area_to_plant"
                                   value="{{ $plan->area_to_plant }}" required>
                        </div>

                        <!-- Seed Required -->
                        <div class="form-group col-md-4">
                            <label>{{ __('seed required') }}</label>
                            <input type="number" step="0.01"
                                   class="form-control"
                                   name="seed_required"
                                   value="{{ $plan->seed_required }}" required>
                        </div>

                    </div>

                    <!-- Yield Section -->
                    <h5 class="mt-4 mb-3 text-success">
                        <i class="fas fa-chart-line mr-2"></i>
                        {{ __('yield estimation') }}
                    </h5>

                    <div class="row">

                        <div class="form-group col-md-6">
                            <label>{{ __('productivity') }}</label>
                            <input type="number" step="0.01" class="form-control"
                                   id="productivity" name="productivity"
                                   value="{{ $plan->productivity }}" readonly>
                        </div>

                        <div class="form-group col-md-6">
                            <label>{{ __('expected yield') }}</label>
                            <input type="number" step="0.01" class="form-control"
                                   id="expected_yield" name="expected_yield"
                                   value="{{ $plan->expected_yield }}" readonly>
                        </div>

                    </div>
                    <div id="fertilizer_list" class="mt-4"></div>
                    <!-- Form Buttons -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> {{ __('back') }}
                        </a>

                        <div>
                            <a href="{{ route('sector.crop-plans.index') }}" class="btn btn-danger">
                                <i class="fas fa-times mr-1"></i> {{ __('cancel') }}
                            </a>

                            <button class="btn btn-warning" id="submitBtn">
                                <i class="fas fa-save mr-1"></i> {{ __('update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
{{-- JS --}}
<script>
    let cropData = [];
    const selectedCropId = {{ $plan->crop_id }};

    $(document).ready(function () {
        loadCrops($('#year').val(), $('#croptype_id').val(), selectedCropId);
        updateExpectedYield();
    });

    function loadCrops(year, typeId, selectedId = null) {

        if (!year || !typeId) return;

        $('#crop_loading').removeClass('d-none');

        $.get(`/sector/crops-by-year-type/${year}/${typeId}`, function (data) {

            $('#crop_loading').addClass('d-none');
            cropData = data;

            $('#crop_id').html(`<option value="">{{ __('common.select') }}</option>`);

            data.forEach(crop => {
                $('#crop_id').append(
                    `<option value="${crop.id}" ${selectedId == crop.id ? 'selected' : ''}>
                        ${crop.name}
                    </option>`
                );
            });

            if (selectedId) {
                $('#crop_id').trigger('change');
            }
        });
    }

    $('#year, #croptype_id').change(function () {
        loadCrops($('#year').val(), $('#croptype_id').val());
    });

    $('#crop_id').change(function () {
        const crop = cropData.find(x => x.id == $(this).val());

        if (!crop) return;

        $('#productivity').val(crop.productivity);
        updateExpectedYield();

        if (crop.fertilizers) {
            let arr = typeof crop.fertilizers === "string" ? JSON.parse(crop.fertilizers) : crop.fertilizers;

            let html = `
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('crop_plan.fertilizer_requirements') }}</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead class="bg-success">
                                <tr>
                                    <th>{{ __('crop_plan.fertilizer_type') }}</th>
                                    <th class="text-center">{{ __('crop_plan.qty_per_ha') }}</th>
                                    <th class="text-center">{{ __('crop_plan.total_required') }}</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            let area = parseFloat($('#area_to_plant').val()) || 0;

            arr.forEach(f => {
                let total = (f.quantity_per_ha * area).toFixed(2);

                html += `
                    <tr>
                        <td>${f.type}</td>
                        <td class="text-center">${f.quantity_per_ha} kg/ha</td>
                        <td class="text-center"><strong class="text-success">${total} kg</strong></td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>`;

            $('#fertilizer_list').html(html);
        }
    });

    $('#area_to_plant').on('input', updateExpectedYield);

    function updateExpectedYield() {
        let area = parseFloat($('#area_to_plant').val()) || 0;
        let prod = parseFloat($('#productivity').val()) || 0;
        let result = area * prod;

        $('#expected_yield').val(result.toFixed(2));

        $('#yieldCalculation').text(`${area} × ${prod} = ${result}`);
    }

    $('#cropPlanForm').submit(function () {
        $('#submitBtn').html(`
            <i class="fas fa-spinner fa-spin mr-1"></i> {{ __('common.processing') }}
        `).prop('disabled', true);
    });

</script>

</x-layout>
