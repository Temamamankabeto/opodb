<!DOCTYPE html>
<x-layout>
    <title>Raawwii fi Karoora Misooma Midhaanii</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 0;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .table th, .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .full-width-container {
            width: 100%;
            padding: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>

    <div class="container my-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Raawwii fi Karoora Misooma Midhaanii Bara 2025</h4>

                <!-- Year Dropdown -->
                <form method="GET" action="#" class="mb-4 text-center">
                    <label for="year" class="me-2">Plan year</label>
                    <select name="year" id="year" onchange="this.form.submit()"
                            class="form-select d-inline-block w-auto">
                        <option selected>2025</option>
                        <option>2024</option>
                        <option>2023</option>
                    </select>
                </form>


                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th rowspan="2">Gosa Midhaanii</th>
                            <!-- <th rowspan="2">Maqaa Midhaanii</th> -->
                            <th rowspan="2">Waqtii</th>
                            <th colspan="2" class="text-center">Rawwii Bara Darbee / Baseline</th>
                            <th colspan="2" class="text-center">Karoora Bara Kana</th>
                            <th colspan="2" class="text-center">Rawwii Bara Kana</th>
                            <th rowspan="2" class="text-center">Gatii Raawwii (%)</th>
                        </tr>
                        <tr>
                            <th>Bal’ina Lafaa [ha]</th>
                            <th>Oomisha [qt]</th>
                            <th>Bal’ina Lafaa [ha]</th>
                            <th>Oomisha [qt]</th>
                            <th>Bal’ina Lafaa [ha]</th>
                            <th>Oomisha [qt]</th>
                        </tr>
                        </thead>
                        <tbody>
 @foreach ($achievements as $cropTypeName => $cropsGroup)
    @php $firstCropType = true; @endphp

    @foreach ($cropsGroup as $cropName => $cropItemsGrouped)
        @php
            $seasons = [
                'Gannaa' => ['land' => 'kblg', 'production' => 'kblb', 'plan_land' => 'kog', 'plan_prod' => 'rog', 'achv_land' => 'rog', 'productivity' => 'this_year_gana_productivity', 'color' => 'info'],
                'Bona' => ['land' => 'rblg', 'production' => 'rblb', 'plan_land' => 'kob', 'plan_prod' => 'rob', 'achv_land' => 'rob', 'productivity' => 'this_year_bona_productivity', 'color' => 'success'],
                'Arfaasaa' => ['land' => 'kbla', 'production' => 'rbla', 'plan_land' => 'koa', 'plan_prod' => 'roa', 'achv_land' => 'roa', 'productivity' => 'this_year_arfasa_productivity', 'color' => 'warning'],
                'Idaama' => ['land' => 'ikbl', 'production' => 'ikbl', 'plan_land' => 'iKo', 'plan_prod' => 'iro', 'achv_land' => 'iro', 'productivity' => 'this_year_idaama_productivity', 'color' => 'danger'],
            ];
            $firstCrop = true;
        @endphp

        @foreach ($seasons as $season => $keys)
            <tr>
                @if ($firstCropType)
                    <td rowspan="{{ count($cropsGroup) * 4 }}" class="align-middle">{{ $cropTypeName }}</td>
                    @php $firstCropType = false; @endphp
                @endif

                @if ($firstCrop)
                    <td rowspan="4" class="align-middle">{{ $cropName }}</td>
                    @php $firstCrop = false; @endphp
                @endif

                <td>{{ $season }}</td>
                <td>{{ $cropItemsGrouped->sum($keys['land']) }}</td>
                <td>{{ $cropItemsGrouped->sum($keys['production']) }}</td>
                <td>{{ $cropItemsGrouped->sum($keys['plan_land']) }}</td>
                <td>{{ $cropItemsGrouped->sum($keys['plan_prod']) }}</td>
                <td>{{ $cropItemsGrouped->sum($keys['achv_land']) }}</td>
                <td>{{ round($cropItemsGrouped->avg($keys['productivity']), 1) }}%</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar bg-{{ $keys['color'] }}" style="width: {{ round($cropItemsGrouped->avg($keys['productivity']), 1) }}%"></div>
                    </div>
                </td>
            </tr>
        @endforeach
    @endforeach
@endforeach

                    </table>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $achievements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
