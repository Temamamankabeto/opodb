<x-layout>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Agricultural Inputs Report</h1>
            <div class="flex space-x-2">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Print Report
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm">Total Years</h3>
                <p class="text-2xl font-bold">{{ $years->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm">Latest Year</h3>
                <p class="text-2xl font-bold">{{ $years->max() }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
                <h3 class="text-gray-500 text-sm">Earliest Year</h3>
                <p class="text-2xl font-bold">{{ $years->min() }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
                <h3 class="text-gray-500 text-sm">Data Points</h3>
                <p class="text-2xl font-bold">{{ $inputs->count() }}</p>
            </div>
        </div>

        <!-- Main Report Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th colspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th colspan="4" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Chemical Fertilizer (Tons)</th>
                            <th colspan="4" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Selective Seed (Tons)</th>
                            <th colspan="4" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pesticide (Litre)</th>
                            <th colspan="4" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Spraying (Hectares)</th>
                        </tr>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baseline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                            
                            <!-- Chemical Fertilizer Headers -->
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ganna</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Bona</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Arfaasaa</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Idaama</th>
                            
                            <!-- Selective Seed Headers -->
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ganna</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Bona</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Arfaasaa</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Idaama</th>
                            
                            <!-- Pesticide Headers -->
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ganna</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Bona</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Arfaasaa</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Idaama</th>
                            
                            <!-- Spraying Headers -->
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Ganna</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Bona</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Arfaasaa</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Idaama</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($inputs as $input)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $input->baseline_year }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $input->plan_year }}</td>
                            
                            <!-- Chemical Fertilizer Data -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->chemical_fertilizer_ganna_baseline }}</span> / 
                                <span class="text-blue-600">{{ $input->chemical_fertilizer_ganna_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->chemical_fertilizer_bona_baseline }}</span> / 
                                <span class="text-blue-600">{{ $input->chemical_fertilizer_bona_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->chemical_fertilizer_arfaasaa_baseline }}</span> / 
                                <span class="text-blue-600">{{ $input->chemical_fertilizer_arfaasaa_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->chemical_fertilizer_idaama_baseline }}</span> / 
                                <span class="text-blue-600">{{ $input->chemical_fertilizer_idaama_plan }}</span>
                            </td>
                            
                            <!-- Selective Seed Data -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->selective_seed_ganna_baseline }}</span> / 
                                <span class="text-green-600">{{ $input->selective_seed_ganna_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->selective_seed_bona_baseline }}</span> / 
                                <span class="text-green-600">{{ $input->selective_seed_bona_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->selective_seed_arfaasaa_baseline }}</span> / 
                                <span class="text-green-600">{{ $input->selective_seed_arfaasaa_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->selective_seed_idaama_baseline }}</span> / 
                                <span class="text-green-600">{{ $input->selective_seed_idaama_plan }}</span>
                            </td>
                            
                            <!-- Pesticide Data -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->pesticide_ganna_baseline }}</span> / 
                                <span class="text-yellow-600">{{ $input->pesticide_ganna_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->pesticide_bona_baseline }}</span> / 
                                <span class="text-yellow-600">{{ $input->pesticide_bona_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->pesticide_arfaasaa_baseline }}</span> / 
                                <span class="text-yellow-600">{{ $input->pesticide_arfaasaa_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->pesticide_idaama_baseline }}</span> / 
                                <span class="text-yellow-600">{{ $input->pesticide_idaama_plan }}</span>
                            </td>
                            
                            <!-- Spraying Data -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->spraying_ganna_baseline }}</span> / 
                                <span class="text-purple-600">{{ $input->spraying_ganna_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->spraying_bona_baseline }}</span> / 
                                <span class="text-purple-600">{{ $input->spraying_bona_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->spraying_arfaasaa_baseline }}</span> / 
                                <span class="text-purple-600">{{ $input->spraying_arfaasaa_plan }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $input->spraying_idaama_baseline }}</span> / 
                                <span class="text-purple-600">{{ $input->spraying_idaama_plan }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Chemical Fertilizer Chart -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Chemical Fertilizer Usage (Tons)</h2>
                <canvas id="chemicalFertilizerChart" height="250"></canvas>
            </div>
            
            <!-- Selective Seed Chart -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Selective Seed Usage (Tons)</h2>
                <canvas id="selectiveSeedChart" height="250"></canvas>
            </div>
            
            <!-- Pesticide Chart -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Pesticide Usage (Litre)</h2>
                <canvas id="pesticideChart" height="250"></canvas>
            </div>
            
            <!-- Spraying Chart -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Spraying Coverage (Hectares)</h2>
                <canvas id="sprayingChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = @json($inputs);
            const years = @json($years);
            
            // Helper function to create datasets
            function createDatasets(inputs, fieldPrefix, label, borderColor) {
                return [
                    {
                        label: `${label} - Baseline`,
                        data: years.map(year => {
                            const input = inputs.find(i => i.plan_year === year);
                            return input ? input[`${fieldPrefix}_baseline`] : 0;
                        }),
                        backgroundColor: 'rgba(200, 200, 200, 0.5)',
                        borderColor: 'rgba(100, 100, 100, 1)',
                        borderWidth: 1
                    },
                    {
                        label: `${label} - Plan`,
                        data: years.map(year => {
                            const input = inputs.find(i => i.plan_year === year);
                            return input ? input[`${fieldPrefix}_plan`] : 0;
                        }),
                        backgroundColor: borderColor.replace(')', ', 0.5)').replace('rgb', 'rgba'),
                        borderColor: borderColor,
                        borderWidth: 1
                    }
                ];
            }
            
            // Chemical Fertilizer Chart
            new Chart(document.getElementById('chemicalFertilizerChart'), {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: [
                        ...createDatasets(inputs, 'chemical_fertilizer_ganna', 'Ganna', 'rgb(54, 162, 235)'),
                        ...createDatasets(inputs, 'chemical_fertilizer_bona', 'Bona', 'rgb(255, 99, 132)'),
                        ...createDatasets(inputs, 'chemical_fertilizer_arfaasaa', 'Arfaasaa', 'rgb(255, 206, 86)'),
                        ...createDatasets(inputs, 'chemical_fertilizer_idaama', 'Idaama', 'rgb(75, 192, 192)')
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Tons'
                            }
                        }
                    }
                }
            });
            
            // Selective Seed Chart
            new Chart(document.getElementById('selectiveSeedChart'), {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [
                        ...createDatasets(inputs, 'selective_seed_ganna', 'Ganna', 'rgb(54, 162, 235)'),
                        ...createDatasets(inputs, 'selective_seed_bona', 'Bona', 'rgb(255, 99, 132)'),
                        ...createDatasets(inputs, 'selective_seed_arfaasaa', 'Arfaasaa', 'rgb(255, 206, 86)'),
                        ...createDatasets(inputs, 'selective_seed_idaama', 'Idaama', 'rgb(75, 192, 192)')
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Tons'
                            }
                        }
                    }
                }
            });
            
            // Pesticide Chart
            new Chart(document.getElementById('pesticideChart'), {
                type: 'radar',
                data: {
                    labels: years,
                    datasets: [
                        ...createDatasets(inputs, 'pesticide_ganna', 'Ganna', 'rgb(54, 162, 235)'),
                        ...createDatasets(inputs, 'pesticide_bona', 'Bona', 'rgb(255, 99, 132)'),
                        ...createDatasets(inputs, 'pesticide_arfaasaa', 'Arfaasaa', 'rgb(255, 206, 86)'),
                        ...createDatasets(inputs, 'pesticide_idaama', 'Idaama', 'rgb(75, 192, 192)')
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Litre'
                            }
                        }
                    }
                }
            });
            
            // Spraying Chart
            new Chart(document.getElementById('sprayingChart'), {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: [
                        ...createDatasets(inputs, 'spraying_ganna', 'Ganna', 'rgb(54, 162, 235)'),
                        ...createDatasets(inputs, 'spraying_bona', 'Bona', 'rgb(255, 99, 132)'),
                        ...createDatasets(inputs, 'spraying_arfaasaa', 'Arfaasaa', 'rgb(255, 206, 86)'),
                        ...createDatasets(inputs, 'spraying_idaama', 'Idaama', 'rgb(75, 192, 192)')
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Hectares'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout>