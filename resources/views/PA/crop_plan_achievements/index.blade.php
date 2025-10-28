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
          <label for="year" class="me-2">Bara Filadhu:</label>
          <select name="year" id="year" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
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
              @foreach ($achievements->groupBy('crops.croptype.name') as $cropTypeName => $cropItems)
                <tr class="table-primary fw-bold">
                  <td colspan="10">{{ $cropTypeName }}</td>
                </tr>
                @foreach ($cropItems->groupBy('crops.name') as $cropName => $cropData)
                  @php
                    $item = $cropData->first();
                    $gannaa_percent = $item->kog ? round(($item->rog / $item->kog) * 100, 1) : 0;
                    $bona_percent = $item->kob ? round(($item->rob / $item->kob) * 100, 1) : 0;
                    $arfaasaa_percent = $item->koa ? round(($item->roa / $item->koa) * 100, 1) : 0;
                    $idaama_percent = $item->iKo ? round(($item->iro / $item->iKo) * 100, 1) : 0;
                  @endphp
                  <tr class="table-secondary">
                    <td rowspan="4">{{ $cropName }}</td>
                    <td>Gannaa</td>
                    <td>{{ $item->kblg }}</td>
                    <td>{{ $item->kog }}</td>
                    <td>{{ $item->rblg }}</td>
                    <td>{{ $item->rog }}</td>
                    <td>{{ $item->blg }}</td>
                    <td>{{ $item->olg }}</td>
                    <td>
                      {{ $gannaa_percent }}%
                      <div class="progress">
                        <div class="progress-bar bg-info" style="width: {{ $gannaa_percent }}%;" role="progressbar"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Bona</td>
                    <td>{{ $item->kblb }}</td>
                    <td>{{ $item->kob }}</td>
                    <td>{{ $item->rblb }}</td>
                    <td>{{ $item->rob }}</td>
                    <td>{{ $item->blb }}</td>
                    <td>{{ $item->olb }}</td>
                    <td>
                      {{ $bona_percent }}%
                      <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $bona_percent }}%;" role="progressbar"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Arfaasaa</td>
                    <td>{{ $item->kbla }}</td>
                    <td>{{ $item->koa }}</td>
                    <td>{{ $item->rbla }}</td>
                    <td>{{ $item->roa }}</td>
                    <td>{{ $item->bla }}</td>
                    <td>{{ $item->ola }}</td>
                    <td>
                      {{ $arfaasaa_percent }}%
                      <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $arfaasaa_percent }}%;" role="progressbar"></div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Idaáma</td>
                    <td>{{ $item->ikbl }}</td>
                    <td>{{ $item->iKo }}</td>
                    <td>{{ $item->irbl }}</td>
                    <td>{{ $item->iro }}</td>
                    <td>{{ $item->ibl }}</td>
                    <td>{{ $item->iol }}</td>
                    <td>
                      {{ $idaama_percent }}%
                      <div class="progress">
                        <div class="progress-bar bg-danger" style="width: {{ $idaama_percent }}%;" role="progressbar"></div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              @endforeach
            </tbody>
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
