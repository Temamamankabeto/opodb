<x-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Yearly Crop Plans</h1>

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Kebele</th>
                    <th class="border px-4 py-2">Plan Year</th>
                    <th class="border px-4 py-2">Baseline Year</th>
                    <th class="border px-4 py-2">Crop Type</th>
                    <th class="border px-4 py-2">Crop</th>
                    <th class="border px-4 py-2">Land (Ganna)</th>
                    <th class="border px-4 py-2">Production (Ganna)</th>
                    <th class="border px-4 py-2">Productivity (Ganna)</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                    <tr class="border-t">
                        <td class="border px-4 py-2">{{ $plan->kebele->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $plan->plan_year }}</td>
                        <td class="border px-4 py-2">{{ $plan->baseline_year }}</td>
                        <td class="border px-4 py-2">{{ $plan->cropType->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $plan->crop->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $plan->land_area_plan_ganna ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $plan->production_plan_ganna ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $plan->productivity_plan_ganna ?? '-' }}</td>
                        <td class="border px-4 py-2">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">No crop plans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $plans->links() }}
        </div>
    </div>
</x-layout>
