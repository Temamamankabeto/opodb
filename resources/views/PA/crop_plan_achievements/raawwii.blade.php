<x-layout>
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h4 class="card-title text-center">Raawwii fi Karoora Misooma Midhaanii Bara {{ $selectedYear }}</h4>

        <!-- Year Dropdown -->
        <form method="GET" action="{{ route('crop_plan_achievements.raawwii') }}" class="mb-4 text-center">
          <label for="year" class="me-2">Bara Filadhu:</label>
          <select name="year" id="year" onchange="this.form.submit()" class="form-control d-inline-block w-auto">
            @foreach ($years as $year)
              <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                {{ $year }}
              </option>
            @endforeach
          </select>
        </form>

        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th rowspan="2">Gosa Midhaanii</th>
                <th rowspan="2">Maqaa Midhaanii</th>
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
              @foreach ($achievements->groupBy('cropType.name') as $cropTypeName => $crops)
                @php
                  $cropCount = count($crops);
                  $rowspan = $cropCount * 4;
                @endphp

                @foreach ($crops as $index => $item)
                  @php
                    $gannaa_percent = $item->kog ? round(($item->rog / $item->kog) * 100, 1) : 0;
                    $bona_percent = $item->kob ? round(($item->rob / $item->kob) * 100, 1) : 0;
                    $arfaasaa_percent = $item->koa ? round(($item->roa / $item->koa) * 100, 1) : 0;
                    $idaama_percent = $item->iKo ? round(($item->iro / $item->iKo) * 100, 1) : 0;
                  @endphp

                  @if ($index == 0)
                    <tr>
                      <td rowspan="{{ $rowspan }}">{{ $cropTypeName }}</td>
                  @else
                    <tr>
                  @endif

                      <td rowspan="4">{{ $item->maqaa_midhaanii }}</td>

                      <!-- Gannaa -->
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
                          <div class="progress-bar bg-success" role="progressbar"
                              style="width: {{ $gannaa_percent }}%"
                              aria-valuenow="{{ $gannaa_percent }}" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </td>
                    </tr>

                    <!-- Bona -->
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
                          <div class="progress-bar bg-info" role="progressbar"
                              style="width: {{ $bona_percent }}%"
                              aria-valuenow="{{ $bona_percent }}" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </td>
                    </tr>

                    <!-- Arfaasaa -->
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
                          <div class="progress-bar bg-warning" role="progressbar"
                              style="width: {{ $arfaasaa_percent }}%"
                              aria-valuenow="{{ $arfaasaa_percent }}" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </td>
                    </tr>

                    <!-- Idaáma -->
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
                          <div class="progress-bar bg-danger" role="progressbar"
                              style="width: {{ $idaama_percent }}%"
                              aria-valuenow="{{ $idaama_percent }}" aria-valuemin="0" aria-valuemax="100">
                          </div>
                        </div>
                      </td>
                    </tr>
                @endforeach
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-layout>
