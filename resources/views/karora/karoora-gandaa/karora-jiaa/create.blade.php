<x-layout>
    <div class="max-w-6xl mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-4">Break Down Yearly Land Plan into Weekly Land Plan</h2>

        <form id="weekly-plan-form" action="{{ route('monthly-plans.store') }}" method="POST">
            @csrf
            {{-- Crop Dropdown --}}
<div class="mb-4">
    <label for="crop_id" class="block font-medium">Select Crop</label>
    <select id="crop_id" class="w-full border rounded p-2" required>
        <option value="">-- Choose --</option>
        @foreach ($crops as $crop)
            <option value="{{ $crop->id }}">{{ $crop->name }}</option>
        @endforeach
    </select>
</div>

{{-- Season Dropdown --}}
<div class="mb-4">
    <label for="season" class="block font-medium">Select Season</label>
    <select id="season" class="w-full border rounded p-2" required>
        <option value="">-- Choose --</option>
        <option value="spring">Spring</option>
        <option value="summer">Summer</option>
        <option value="autumn">Autumn</option>
        <option value="winter">Winter</option>
    </select>
</div>

{{-- Filtered Yearly Plans Dropdown --}}
<div class="mb-4">
    <label for="yearly_plan_id" class="block font-medium">Select Yearly Plan</label>
    <select name="yearly_plan_id" id="yearly_plan_id" class="w-full border rounded p-2" required>
        <option value="">-- Choose crop, season first --</option>
    </select>
</div>

            {{-- Yearly Plan Selection --}}
            <div class="mb-4">
                <label for="yearly_plan_id" class="block font-medium">Select Yearly Plan</label>
                <select name="yearly_plan_id" id="yearly_plan_id" class="w-full border rounded p-2" required>
                    <option value="">-- Choose --</option>
                    @foreach ($yearlyPlans as $plan)
                        <option value="{{ $plan->id }}">
                            {{ $plan->year }} - {{ $plan->maqaa_midhaanii }} ({{ $plan->total_land_hectares }} ha)
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Display total land --}}
            <div class="mb-6">
                <label class="block font-medium">Total Land Area (ha)</label>
                <input type="text" id="total_land_hectares" class="w-full border rounded p-2 bg-gray-100" readonly>
            </div>

            {{-- Weekly Inputs --}}
            <h3 class="text-lg font-semibold mb-4">Weekly Land Breakdown (Ethiopian Calendar)</h3>

            @php
                $ethiopianMonths = [
                    'Meskerem', 'Tikimt', 'Hidar', 'Tahsas',
                    'Tir', 'Yekatit', 'Megabit', 'Miyazya',
                    'Ginbot', 'Sene', 'Hamle', 'Nehase'
                ];
            @endphp

            @foreach ($ethiopianMonths as $month)
                <div class="bg-gray-100 rounded p-4 mb-4">
                    <h4 class="font-bold text-md mb-2">{{ $month }}</h4>
                    <div class="grid grid-cols-4 gap-4">
                        @for ($week = 1; $week <= 4; $week++)
                            <div>
                                <label class="block text-sm font-medium">{{ $month }} - Week {{ $week }} Land (ha)</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="weekly_land[{{ $month }}][{{ $week }}]" 
                                    class="w-full border rounded p-2 week-input" 
                                    data-month="{{ $month }}" 
                                    data-week="{{ $week }}">
                            </div>
                        @endfor
                    </div>
                    {{-- Monthly total row --}}
                    <div class="mt-4">
                        <label class="font-semibold">Total for {{ $month }} (ha):</label>
                        <input 
                            type="text" 
                            id="monthly-total-{{ $month }}" 
                            class="w-48 border rounded p-2 bg-gray-200" 
                            readonly>
                    </div>
                </div>
            @endforeach

            {{-- Grand total and warning --}}
            <div class="mt-6">
                <label class="font-bold text-lg">Grand Total for Year (ha):</label>
                <input type="text" id="yearly-total" class="w-48 border rounded p-2 bg-gray-300 font-bold" readonly>
                <p id="grand-total-warning" class="text-red-600 font-semibold mt-2 hidden">
                    ⚠️ Grand total exceeds total land planned!
                </p>
            </div>

            <div class="mt-6">
                <button type="submit" id="submit-btn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Weekly Land Plan
                </button>
            </div>
        </form>
    </div>

    {{-- Highlight styles --}}
    <style>
        .overflow-input {
            border-color: red !important;
            background-color: #ffe5e5 !important;
        }
    </style>

    {{-- Auto-fill total land --}}
    <script>
        const yearlyPlans = @json($yearlyPlans);
        const months = @json($ethiopianMonths);

        document.getElementById('yearly_plan_id').addEventListener('change', function () {
            const selected = this.value;
            const plan = yearlyPlans.find(p => p.id == selected);
            document.getElementById('total_land_hectares').value = plan ? plan.total_land_hectares : '';
            calculateMonthlyTotals();
        });
    </script>

    {{-- Calculation + validation --}}
    <script>
        function calculateMonthlyTotals() {
            let yearlyTotal = 0;
            document.querySelectorAll('.week-input').forEach(input => input.classList.remove('overflow-input'));

            months.forEach(month => {
                let monthlyTotal = 0;
                const inputs = document.querySelectorAll(`input[data-month="${month}"]`);
                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value)) monthlyTotal += value;
                });
                document.getElementById(`monthly-total-${month}`).value = monthlyTotal.toFixed(2);
                yearlyTotal += monthlyTotal;
            });

            document.getElementById('yearly-total').value = yearlyTotal.toFixed(2);

            const allowed = parseFloat(document.getElementById('total_land_hectares').value);
            const warning = document.getElementById('grand-total-warning');
            const submitBtn = document.getElementById('submit-btn');

            if (!isNaN(allowed) && yearlyTotal > allowed) {
                warning.classList.remove('hidden');
                submitBtn.disabled = true;

                // Highlight field that pushed over
                let runningTotal = 0;
                outer: for (let month of months) {
                    const inputs = document.querySelectorAll(`input[data-month="${month}"]`);
                    for (let input of inputs) {
                        const val = parseFloat(input.value);
                        if (!isNaN(val)) {
                            runningTotal += val;
                            if (runningTotal > allowed) {
                                input.classList.add('overflow-input');
                                break outer;
                            }
                        }
                    }
                }

            } else {
                warning.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        document.querySelectorAll('.week-input').forEach(input => {
            input.addEventListener('input', calculateMonthlyTotals);
        });
    </script>



<script>
    async function fetchYearlyPlans() {
        const cropId = document.getElementById('crop_id').value;
        const season = document.getElementById('season').value;

        if (cropId && season) {
            const res = await fetch(`{{ route('api.yearly-plans') }}?crop_id=${cropId}&season=${season}`);
            const plans = await res.json();

            const select = document.getElementById('yearly_plan_id');
            select.innerHTML = `<option value="">-- Choose --</option>`;

            plans.forEach(plan => {
                const option = document.createElement('option');
                option.value = plan.id;
                option.textContent = `${plan.year} - ${plan.maqaa_midhaanii} (${plan.total_land_hectares} ha)`;
                option.dataset.total = plan.total_land_hectares;
                select.appendChild(option);
            });

            document.getElementById('total_land_hectares').value = '';
        }
    }

    document.getElementById('crop_id').addEventListener('change', fetchYearlyPlans);
    document.getElementById('season').addEventListener('change', fetchYearlyPlans);

    document.getElementById('yearly_plan_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('total_land_hectares').value = selectedOption.dataset.total || '';
        calculateMonthlyTotals();
    });
</script>

</x-layout>
