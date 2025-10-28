 <x-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Gabaa Hojii Uumuu Naannoo</h2>
    </x-slot>

    {{-- Filter Form --}}
    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Bara</label>
            <input 
                id="year" 
                type="number" 
                name="year" 
                class="input w-full" 
                placeholder="Bara" 
                value="{{ request('year') }}"
            >
        </div>

        <div>
            <label for="enterprise_type" class="block text-sm font-medium text-gray-700 mb-1">Akaakuu Interpiraayizii</label>
            <select id="enterprise_type" name="enterprise_type" class="input w-full">
                <option value="">-- Hunda --</option>
                <option value="kan gurmaa’e" {{ request('enterprise_type') == 'kan gurmaa’e' ? 'selected' : '' }}>
                    Kan Gurmaa'e
                </option>
                <option value="kan hojii tti gale" {{ request('enterprise_type') == 'kan hojii tti gale' ? 'selected' : '' }}>
                    Kan Hojii Tti Gale
                </option>
            </select>
        </div>

        <div>
            <label for="job_type_id" class="block text-sm font-medium text-gray-700 mb-1">Gosa Hojii</label>
            <select id="job_type_id" name="job_type_id" class="input w-full">
                <option value="">-- Hunda --</option>
                @foreach($jobTypes as $type)
                    <option value="{{ $type->id }}" {{ request('job_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-1">Godina</label>
            <select id="zone-select" name="zone_id" class="input w-full">
                <option value="">-- Hunda --</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                        {{ $zone->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="woreda_id" class="block text-sm font-medium text-gray-700 mb-1">Aanaa</label>
            <select id="woreda-select" name="woreda_id" class="input w-full">
                <option value="">-- Hunda --</option>
                @foreach($woredas as $woreda)
                    <option value="{{ $woreda->id }}" {{ request('woreda_id') == $woreda->id ? 'selected' : '' }}>
                        {{ $woreda->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-5 flex justify-end gap-2">
            <button type="submit" class="btn bg-blue-600 text-white">
                <i class="fas fa-filter mr-2"></i> Filadhu
            </button>
            <a href="{{ route('region.reports.job_report') }}" class="btn bg-gray-200 text-gray-800">
                <i class="fas fa-sync-alt mr-2"></i> Haquu
            </a>
        </div>
    </form>

    {{-- Report Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($jobCreations->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-info-circle fa-2x mb-2"></i>
                <p>Filannoo kana irratti hundaa'uun carraa hojii hin argamne</p>
            </div>
        @else
            @foreach($groupedData as $zoneName => $woredaGroups)
                <section class="border-b last:border-b-0">
                    <header class="bg-blue-50 px-4 py-2 font-bold text-blue-800 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i> Godina {{ $zoneName }}
                    </header>

                    @foreach($woredaGroups as $woredaName => $kebeleGroups)
                        <article class="border-b last:border-b-0">
                            <header class="bg-gray-50 px-6 py-2 font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-map-marker"></i> Aanaa {{ $woredaName }}
                            </header>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Godina</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aanaa</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Akaakuu Interpiraayizii</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gosa Hojii</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dhiira</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dubartoota</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waliigala</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($flatData as $index => $row)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $row['zone'] }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $row['woreda'] }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $row['enterprise_type'] }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $row['job_type'] }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $row['male'] }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $row['female'] }}</td>
                                                <td class="px-4 py-2 text-sm font-bold">{{ $row['sum'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endforeach
        @endif
    </div>

    {{-- Chart Section --}}
    @if($jobCreations->isNotEmpty())
        <section class="mt-8 bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4 text-center">Carraa Hojii Naannoo keessatti Uumame</h3>
            <div class="chart-container" style="height: 400px;">
                <canvas id="jobsChart"></canvas>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('jobsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData->keys()) !!},
                    datasets: [
                        {
                            label: 'Dhiira',
                            data: {!! json_encode($chartData->pluck('male')->toArray()) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Dubartoota',
                            data: {!! json_encode($chartData->pluck('female')->toArray()) !!},
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Waliigala',
                            data: {!! json_encode($chartData->pluck('total')->toArray()) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.7)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Dynamic woreda dropdown based on zone selection
            document.getElementById('zone-select').addEventListener('change', function() {
                const zoneId = this.value;
                const woredaSelect = document.getElementById('woreda-select');

                if (zoneId) {
                    fetch(`/api/woredas?zone_id=${zoneId}`)
                        .then(response => response.json())
                        .then(data => {
                            woredaSelect.innerHTML = '<option value="">-- Hunda --</option>';
                            data.forEach(woreda => {
                                const option = document.createElement('option');
                                option.value = woreda.id;
                                option.textContent = woreda.name;
                                woredaSelect.appendChild(option);
                            });
                        });
                } else {
                    woredaSelect.innerHTML = '<option value="">-- Hunda --</option>';
                    @foreach($woredas as $woreda)
                        const option = document.createElement('option');
                        option.value = '{{ $woreda->id }}';
                        option.textContent = '{{ $woreda->name }}';
                        woredaSelect.appendChild(option);
                    @endforeach
                }
            });
        </script>
    @endif
</x-layout>
