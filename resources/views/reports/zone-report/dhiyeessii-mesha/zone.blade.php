<x-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mb-4">
                <img src="{{ asset('path/to/your/logo.png') }}" alt="Logo" class="h-20 mx-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Gabaasa Dhiyeessii Qabeenya fi Makaanaayizeeshinii Liqii</h1>
            <h2 class="text-xl text-gray-600">Haala Waliigalaa Aanaa Zoonii Keessa Jiran Hunda</h2>
            <div class="border-t-2 border-b-2 border-blue-500 py-2 my-4">
                <p class="text-gray-700 font-medium">Waggaa: {{ $year }}</p>
            </div>
        </div>

        <!-- Year Selection -->
        <div class="flex justify-center mb-8">
            <form method="GET" class="flex items-center bg-white rounded-lg shadow p-4">
                <label for="year" class="mr-3 font-medium text-gray-700">Waggaa Filadhu:</label>
                <select name="year" id="year" onchange="this.form.submit()"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-4">
                    @for ($y = now()->year; $y >= now()->year - 10; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-blue-600 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">Dhiyeessii Qabeenya ({{ $prevYear }} vs {{ $year }})</h3>
                </div>
                <div class="p-4 h-80">
                    <canvas id="materialChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-green-600 px-4 py-3">
                    <h3 class="text-lg font-semibold text-white">Makaanaayizeeshinii Liqii ({{ $prevYear }} vs {{ $year }})</h3>
                </div>
                <div class="p-4 h-80">
                    <canvas id="loanChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Material Supplies Table with Merged Woredas -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="bg-blue-600 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">Dhiyeessii Qabeenya Waraanni Aanaa Irratti</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aanaa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Maatiiriyelii</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">{{ $prevYear }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">{{ $year }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Jijjiirama (%)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $currentWoreda = null;
                            $groupedMaterials = [];
                            foreach ($materialByWoreda as $record) {
                                $groupedMaterials[$record->woreda_name][] = $record;
                            }
                        @endphp
                        @foreach ($groupedMaterials as $woredaName => $materials)
                            @foreach ($materials as $index => $record)
                                <tr @if($index === 0) class="border-t-2 border-gray-300" @endif>
                                    @if($index === 0)
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900" rowspan="{{ count($materials) }}">
                                            {{ $woredaName }}
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $record->material_name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($record->prev_qty, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ $record->current_qty > $record->prev_qty ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($record->current_qty, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ ($record->prev_qty ? (($record->current_qty - $record->prev_qty)/$record->prev_qty)*100 : ($record->current_qty > 0 ? 100 : 0)) > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($record->prev_qty ? (($record->current_qty - $record->prev_qty)/$record->prev_qty)*100 : ($record->current_qty > 0 ? 100 : 0), 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Loan Mechanizations Table with Merged Woredas -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-green-600 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">Makaanaayizeeshinii Liqii Waraanni Aanaa Irratti</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aanaa</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Gosa Liqii</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">{{ $prevYear }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">{{ $year }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Jijjiirama (%)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $currentWoreda = null;
                            $groupedLoans = [];
                            foreach ($loanByWoreda as $record) {
                                $groupedLoans[$record->woreda_name][] = $record;
                            }
                        @endphp
                        @foreach ($groupedLoans as $woredaName => $loans)
                            @foreach ($loans as $index => $record)
                                <tr @if($index === 0) class="border-t-2 border-gray-300" @endif>
                                    @if($index === 0)
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900" rowspan="{{ count($loans) }}">
                                            {{ $woredaName }}
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $record->loan_type }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-600">{{ number_format($record->prev_qty, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ $record->current_qty > $record->prev_qty ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($record->current_qty, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium {{ ($record->prev_qty ? (($record->current_qty - $record->prev_qty)/$record->prev_qty)*100 : ($record->current_qty > 0 ? 100 : 0)) > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($record->prev_qty ? (($record->current_qty - $record->prev_qty)/$record->prev_qty)*100 : ($record->current_qty > 0 ? 100 : 0), 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configure charts to be responsive and maintain aspect ratio
        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.responsive = true;

        // Material Chart
        const materialCtx = document.getElementById('materialChart');
        new Chart(materialCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($materialData)) !!},
                datasets: [
                    {
                        label: '{{ $prevYear }}',
                        data: {!! json_encode(array_column($materialData, 'previous')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ $year }}',
                        data: {!! json_encode(array_column($materialData, 'current')) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Loan Chart
        const loanCtx = document.getElementById('loanChart');
        new Chart(loanCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($loanData)) !!},
                datasets: [
                    {
                        label: '{{ $prevYear }}',
                        data: {!! json_encode(array_column($loanData, 'previous')) !!},
                        backgroundColor: 'rgba(234, 179, 8, 0.7)',
                        borderColor: 'rgba(234, 179, 8, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ $year }}',
                        data: {!! json_encode(array_column($loanData, 'current')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-layout>