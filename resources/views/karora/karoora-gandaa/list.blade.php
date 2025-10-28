<x-layout>
    <div class="container py-4">
        <h1 class="mb-4">Agricultural Inputs Report</h1>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Filter Options
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inputs.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-select">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="season" class="form-label">Season</label>
                            <select name="season" id="season" class="form-select">
                                <option value="">All Seasons</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>
                                        {{ ucfirst($season) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="type" class="form-label">Input Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="kebele" class="form-label">Kebele</label>
                            <select name="kebele" id="kebele" class="form-select">
                                <option value="">All Kebeles</option>
                                @foreach($kebeles as $kebele)
                                    <option value="{{ $kebele }}" {{ request('kebele') == $kebele ? 'selected' : '' }}>
                                        Kebele {{ $kebele }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('inputs.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grouped Data Display -->
        @forelse($groupedInputs as $kebeleId => $seasonsData)
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h3 class="mb-0">Kebele {{ $kebeleId }}</h3>
                </div>
                <div class="card-body p-0">
                    @foreach($seasonsData as $season => $typesData)
                        <div class="p-3 border-bottom">
                            <h4 class="text-primary">Season: {{ ucfirst($season) }}</h4>
                            
                            @foreach($typesData as $type => $inputs)
                                <div class="mb-4">
                                    <h5 class="text-secondary">{{ $type }}</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Created By</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inputs as $input)
                                                    <tr>
                                                        <td>{{ $input->year }}</td>
                                                        <td>{{ number_format($input->quantity) }}</td>
                                                        <td>{{ $input->unit }}</td>
                                                        <td>{{ $input->created_by }}</td>
                                                        <td>
                                                             
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-info">No inputs found matching your criteria.</div>
        @endforelse
    </div>
</x-layout>