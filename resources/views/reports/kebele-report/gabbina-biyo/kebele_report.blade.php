<x-layout>
<div class="container">
    <h2 class="mb-4">Gabaasa Fayyadama Kompostii</h2>

    <form method="GET" action="{{ route('compost.report') }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="year" class="form-label">Bara Filadhu:</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @if($selectedYear && $baselineYear && count($yearsData) > 0)
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2">Gosa Compostii</th>
                        <th colspan="2">Raawwii Bara {{ $baselineYear }}</th>
                        <th colspan="2">Karoora Bara {{ $selectedYear }}</th>
                    </tr>
                    <tr>
                        <th>Value</th>
                        <th>Unit</th>
                        <th>Value</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compostTypes as $type)
                        @php
                            $typeName = $type->name;
                            $baseline = $yearsData[$baselineYear][$typeName] ?? null;
                            $current = $yearsData[$selectedYear][$typeName] ?? null;

                            $getValue = fn($data, $key) => $data ? ($data->$key ?? 0) : 0;
                        @endphp

                        {{-- Compost type header row --}}
                        <tr class="table-secondary fw-bold">
                            <td colspan="5">{{ $typeName }}</td>
                        </tr>

                        {{-- Common --}}
                        <tr>
                            <td>Common</td>
                            <td>{{ $getValue($baseline, 'common') }}</td>
                            <td>Q</td>
                            <td>{{ $getValue($current, 'common') }}</td>
                            <td>Q</td>
                        </tr>

                        {{-- Individual --}}
                        <tr>
                            <td>Individual</td>
                            <td>{{ $getValue($baseline, 'individual') }}</td>
                            <td>Q</td>
                            <td>{{ $getValue($current, 'individual') }}</td>
                            <td>Q</td>
                        </tr>

                        {{-- Total --}}
                        <tr>
                            <td>Total</td>
                            <td>{{ $getValue($baseline, 'common') + $getValue($baseline, 'individual') }}</td>
                            <td>Q</td>
                            <td>{{ $getValue($current, 'common') + $getValue($current, 'individual') }}</td>
                            <td>Q</td>
                        </tr>

                        {{-- Land (only for specific type) --}}
                        @if($typeName === 'Nooraa Hojiirra oole')
                        <tr>
                            <td>Land (ha)</td>
                            <td>{{ number_format($getValue($baseline, 'land_area'), 2) }}</td>
                            <td>ha</td>
                            <td>{{ number_format($getValue($current, 'land_area'), 2) }}</td>
                            <td>ha</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Odeeffannoon gabaasaaf hin jiruu.
        </div>
    @endif
</div>
</x-layout>
