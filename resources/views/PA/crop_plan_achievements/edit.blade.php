<x-layout>
    <div class="container mt-3">
        <div class="card shadow rounded">
            <div class="card-header text-center">
                <h2>Sirreessi Karoora Misooma Midhaanii</h2>
            </div>

            <div class="card-body">
                <form action="{{ route('crop_plan_achievements.update', $achievement->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label>Gosa Midhaanii</label>
                            <select name="crop_type_id" id="crop_type_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($cropTypes as $type)
                                    <option value="{{ $type->id }}" {{ $achievement->crop_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label>Maqaa Midhaanii</label>
                            <select name="maqaa_midhaanii" id="maqaa_midhaanii" class="form-control" required>
                                <option value="{{ $achievement->maqaa_midhaanii }}">{{ $achievement->maqaa_midhaanii }}</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label>Bara</label>
                            <input type="text" name="year" id="yearPicker" class="form-control" value="{{ old('year', $achievement->year) }}" required>
                        </div>

                        @php
                            $fields = [
                                'kblg' => 'Balína Lafaa Gannaa',
                                'kblb' => 'Balína Lafaa Bonaa',
                                'kbla' => 'Balína Lafaa Arfaasaa',
                                'kog'  => 'Oomisha Gannaa',
                                'kob'  => 'Oomisha Bonaa',
                                'koa'  => 'Oomisha Arfaasaa',
                            ];
                        @endphp

                        @foreach ($fields as $name => $label)
                            <div class="mb-3 col-md-4">
                                <label>{{ $label }}</label>
                                <input type="text" name="{{ $name }}" class="form-control" value="{{ old($name, $achievement->$name) }}" required>
                            </div>
                        @endforeach

                        <div class="mb-3 col-md-4">
                            <label>Balína Woliigalaa</label>
                            <input type="text" name="ikbl" class="form-control" value="{{ old('ikbl', $achievement->ikbl) }}" style="color: burlywood;" readonly>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label>Oomisha Woliigalaa</label>
                            <input type="text" name="iKo" class="form-control" value="{{ old('iKo', $achievement->iKo) }}" style="color: burlywood;" readonly>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('crop_plan_achievements.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kblg = document.querySelector('input[name="kblg"]');
    const kblb = document.querySelector('input[name="kblb"]');
    const kbla = document.querySelector('input[name="kbla"]');
    const kog = document.querySelector('input[name="kog"]');
    const kob = document.querySelector('input[name="kob"]');
    const koa = document.querySelector('input[name="koa"]');
    const ikbl = document.querySelector('input[name="ikbl"]');
    const iKo = document.querySelector('input[name="iKo"]');

    function calculateSums() {
        const sumKbl = [kblg, kblb, kbla].reduce((acc, input) => acc + (parseFloat(input.value) || 0), 0);
        const sumKo = [kog, kob, koa].reduce((acc, input) => acc + (parseFloat(input.value) || 0), 0);
        ikbl.value = sumKbl;
        iKo.value = sumKo;
    }

    [kblg, kblb, kbla, kog, kob, koa].forEach(input => {
        input.addEventListener('input', calculateSums);
    });

    $('#yearPicker').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    });

    const cropTypeSelect = document.getElementById('crop_type_id');
    const maqaaSelect = document.getElementById('maqaa_midhaanii');

    cropTypeSelect.addEventListener('change', function () {
        const cropTypeId = this.value;
        maqaaSelect.innerHTML = '<option value="">-- Loading --</option>';
        if (cropTypeId) {
            fetch(`/get-crops-by-type/${cropTypeId}`)
                .then(response => response.json())
                .then(data => {
                    maqaaSelect.innerHTML = '<option value="">-- Select --</option>';
                    data.forEach(crop => {
                        maqaaSelect.innerHTML += `<option value="${crop.name}">${crop.name}</option>`;
                    });
                });
        } else {
            maqaaSelect.innerHTML = '<option value="">-- Select --</option>';
        }
    });
});
</script>
</x-layout>
