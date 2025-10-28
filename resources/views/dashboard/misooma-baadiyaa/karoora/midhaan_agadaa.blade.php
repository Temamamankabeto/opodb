<x-layout>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Midhaan innishatiivii: Boqqolloo</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> Maqaa MIdhaanii</th>
                            <th> Lafa hek </th>
                            <th> Oomisha </th>
                            <th> ku/hek </th>
                            <th> Bara </th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach ($boqqolloo as $item)
        <tr>
        <td>{{ $item->crop->name }}</td>
            <td>{{ $item->total_land_area_plan }}</td>
            <td>{{ $item->total_production_plan }}</td>
            <td>
                @if ($item->total_land_area_plan > 0)
                    {{ number_format($item->total_production_plan / $item->total_land_area_plan, 2) }}
                @else
                    0
                @endif
            </td>
            <td>{{ $item->production_year }}</td>
        </tr>
    @endforeach
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Midhaan innishatiivii: Misingaa</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th> Maqaa MIdhaanii</th>
                            <th> Lafa hek </th>
                            <th> Oomisha </th>
                            <th> ku/hek </th>
                            <th> Bara </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($garbuu as $item)
                        <tr>
                        <td>{{ $item->crop->name }}</td>
                        <td>{{ $item->total_land_area_plan }}</td>
                        <td>{{ $item->total_production_plan }}</td>
                        <td>
                @if ($item->total_land_area_plan > 0)
                    {{ number_format($item->total_production_plan / $item->total_land_area_plan, 2) }}
                @else
                    0
                @endif
            </td>
                            <td>{{ $item->production_year }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Midhaan innishatiivii: Garbuu</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th> Maqaa MIdhaanii</th>
                            <th> Lafa hek </th>
                            <th> Oomisha </th>
                            <th> ku/hek </th>
                            <th> Bara </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($qamadii as $item)
                        <tr>
                        <td>{{ $item->crop->name }}</td>
                        <td>{{ $item->total_land_area_plan }}</td>
                        <td>{{ $item->total_production_plan }}</td>
                        <td>
                @if ($item->total_land_area_plan > 0)
                    {{ number_format($item->total_production_plan / $item->total_land_area_plan, 2) }}
                @else
                    0
                @endif
            </td>
                            <td>{{ $item->production_year }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-layout>
