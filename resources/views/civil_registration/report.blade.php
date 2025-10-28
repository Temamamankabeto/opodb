<x-layout>
    <div class="container">
        <h2>Civil Registration Report ({{ $years[0] }} vs {{ $years[1] }})</h2>

        {{-- Filter by year only --}}
        <form method="GET" action="{{ route('civil_registration.report') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="year">Select Year (N):</label>
                    <input type="number" name="year" class="form-control" value="{{ $currentYear }}" required>
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('civil_registration.report') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        {{-- Data Table --}}
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Attribute</th>
                @foreach($years as $year)
                    <th>{{ $year }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @php
                $attributes = ['birth_cert', 'marriage_cert', 'adoption', 'divorce_cert', 'death_cert'];
            @endphp
            @foreach($attributes as $attr)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $attr)) }}</td>
                    @foreach($years as $year)
                        <td>{{ $data[$year]->$attr ?? 0 }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        {{-- Chart Section --}}
        <canvas id="barChart" height="100" class="mt-4"></canvas>
        <canvas id="lineChart" height="100" class="mt-4"></canvas>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const chartLabels = {!! json_encode(['Birth Cert', 'Marriage Cert', 'Adoption', 'Divorce Cert', 'Death Cert']) !!};
            const yearLabels = {!! json_encode($years) !!};

            const yearData = {
                {{ $years[0] }}: [
                    {{ $data[$years[0]]->birth_cert ?? 0 }},
                    {{ $data[$years[0]]->marriage_cert ?? 0 }},
                    {{ $data[$years[0]]->adoption ?? 0 }},
                    {{ $data[$years[0]]->divorce_cert ?? 0 }},
                    {{ $data[$years[0]]->death_cert ?? 0 }},
                ],
                {{ $years[1] }}: [
                    {{ $data[$years[1]]->birth_cert ?? 0 }},
                    {{ $data[$years[1]]->marriage_cert ?? 0 }},
                    {{ $data[$years[1]]->adoption ?? 0 }},
                    {{ $data[$years[1]]->divorce_cert ?? 0 }},
                    {{ $data[$years[1]]->death_cert ?? 0 }},
                ]
            };

            new Chart(document.getElementById("barChart"), {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: "{{ $years[0] }}",
                            backgroundColor: '#4e73df',
                            data: yearData[{{ $years[0] }}]
                        },
                        {
                            label: "{{ $years[1] }}",
                            backgroundColor: '#1cc88a',
                            data: yearData[{{ $years[1] }}]
                        }
                    ]
                }
            });

            new Chart(document.getElementById("lineChart"), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: "{{ $years[0] }}",
                            borderColor: '#4e73df',
                            fill: false,
                            data: yearData[{{ $years[0] }}]
                        },
                        {
                            label: "{{ $years[1] }}",
                            borderColor: '#1cc88a',
                            fill: false,
                            data: yearData[{{ $years[1] }}]
                        }
                    ]
                }
            });
        </script>
</x-layout>
