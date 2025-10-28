<x-layout>
    <h2 class="text-xl font-semibold mb-4">Add Zonal Devision</h2>

    <form action="{{ route('zonal_devisions.store') }}" method="POST" class="space-y-4">
        @csrf

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="zone_id" class="block font-medium">Zone</label>
            <select name="zone_id" id="zone_id" class="form-select w-full border p-2" required>
                <option value="">-- Select Zone --</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="production_year" class="block font-medium">Production Year</label>
            <select name="production_year" id="production_year" class="form-select w-full border p-2" required>
                <option value="">-- Select Year --</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="crop_id" class="block font-medium">Crop</label>
            <select name="crop_id" id="crop_id" class="form-select w-full border p-2" required>
                <option value="">-- Select Crop --</option>
                @foreach ($crops as $crop)
                    <option value="{{ $crop->id }}">{{ $crop->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="total_land_area_plan" class="block font-medium">Remaining Land Area Plan</label>
            <input type="text" name="total_land_area_plan" id="total_land_area_plan" class="form-input w-full border p-2" readonly>
        </div>

        <div class="mb-3">
            <label for="total_production_plan" class="block font-medium">Remaining Production Plan</label>
            <input type="text" name="total_production_plan" id="total_production_plan" class="form-input w-full border p-2" readonly>
        </div>

        <div class="mb-3">
            <label for="ammount_of_land_for_zone" class="block font-medium">Land for Zone</label>
            <input type="number" step="0.01" name="ammount_of_land_for_zone" id="ammount_of_land_for_zone" class="form-input w-full border p-2" value="{{ old('ammount_of_land_for_zone') }}">
        </div>

        <div class="mb-3">
            <label for="ammount_of_prucuction_for" class="block font-medium">Production for Zone</label>
            <input type="number" step="0.01" name="ammount_of_prucuction_for" id="ammount_of_prucuction_for" class="form-input w-full border p-2" value="{{ old('ammount_of_prucuction_for') }}">
        </div>

        <div class="mb-3">
            <label for="productivity" class="block font-medium">Productivity</label>
            <input type="text" name="productivity" id="productivity" class="form-input w-full border p-2" value="{{ old('productivity') }}">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cropSelect = document.getElementById('crop_id');
            const yearSelect = document.getElementById('production_year');
            const landZoneInput = document.getElementById('ammount_of_land_for_zone');
            const productionZoneInput = document.getElementById('ammount_of_prucuction_for');

            let originalLand = 0;
            let originalProduction = 0;

            function fetchPlanData() {
                const cropId = cropSelect.value;
                const year = yearSelect.value;

                if (cropId && year) {
                    fetch(`/fetch-crop-plan?crop_id=${cropId}&production_year=${year}`)
                        .then(res => res.json())
                        .then(data => {
                            originalLand = parseFloat(data.total_land_area_plan) || 0;
                            originalProduction = parseFloat(data.total_production_plan) || 0;

                            updateRemaining();
                        });
                }
            }

            function updateRemaining() {
                const usedLand = parseFloat(landZoneInput.value) || 0;
                const usedProduction = parseFloat(productionZoneInput.value) || 0;

                const remainingLand = Math.max(originalLand - usedLand, 0);
                const remainingProduction = Math.max(originalProduction - usedProduction, 0);

                document.getElementById('total_land_area_plan').value = remainingLand.toFixed(2);
                document.getElementById('total_production_plan').value = remainingProduction.toFixed(2);
            }

            cropSelect.addEventListener('change', fetchPlanData);
            yearSelect.addEventListener('change', fetchPlanData);
            landZoneInput.addEventListener('input', updateRemaining);
            productionZoneInput.addEventListener('input', updateRemaining);
        });
    </script>
</x-layout>
