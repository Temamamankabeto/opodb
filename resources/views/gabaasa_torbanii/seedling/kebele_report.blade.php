<x-layout>
    <div class="container-fluid py-4">
        <!-- Header & Year Filter -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-success fw-bold">
                <i class="fas fa-seedling me-2"></i>Kebele Level Seedling Report
            </h4>
            <form method="GET" class="d-flex align-items-center">
                <label class="me-2 fw-semibold">Year:</label>
                <select name="year" class="form-select form-select-sm me-2">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </form>
        </div>

        <!-- Report Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="thead-light sticky-top">
                            <tr>
                                <th>Kebele</th>
                                <th colspan="3" class="text-center">{{ $selectedYear }} (Preparation)</th>
                                <th colspan="3" class="text-center">{{ $selectedYear }} (Planting)</th>
                                <th colspan="3" class="text-center">{{ $previousYear }} (Preparation)</th>
                                <th colspan="3" class="text-center">{{ $previousYear }} (Planting)</th>
                            </tr>
                            <tr>
                                @for ($i = 0; $i < 4; $i++)
                                    <th>Hanga</th>
                                    <th>Participants</th>
                                    <th>Days</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $data)
                                <tr>
                                    <td class="fw-bold">{{ $data['kebele_name'] }}</td>

                                    <td>{{ $data['current']['preparation']['hanga'] ?? 0 }}</td>
                                    <td>{{ $data['current']['preparation']['participants'] ?? 0 }}</td>
                                    <td>{{ $data['current']['preparation']['days'] ?? 0 }}</td>

                                    <td>{{ $data['current']['planting']['hanga'] ?? 0 }}</td>
                                    <td>{{ $data['current']['planting']['participants'] ?? 0 }}</td>
                                    <td>{{ $data['current']['planting']['days'] ?? 0 }}</td>

                                    <td>{{ $data['previous']['preparation']['hanga'] ?? 0 }}</td>
                                    <td>{{ $data['previous']['preparation']['participants'] ?? 0 }}</td>
                                    <td>{{ $data['previous']['preparation']['days'] ?? 0 }}</td>

                                    <td>{{ $data['previous']['planting']['hanga'] ?? 0 }}</td>
                                    <td>{{ $data['previous']['planting']['participants'] ?? 0 }}</td>
                                    <td>{{ $data['previous']['planting']['days'] ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i>No data found for selected year
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>
