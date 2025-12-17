<x-layout>

{{-- ================= HEADER ================= --}}
<section class="content-header">
    <div class="container-fluid">
        <h1>
            <i class="fas fa-seedling text-success mr-2"></i>
            Add Seedling Preparation
        </h1>
    </div>
</section>

<section class="content">
<div class="container-fluid">

{{-- ================= ERRORS ================= --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="mainForm" method="POST" action="{{ route('sector.seedling-preparations.store') }}">
@csrf

{{-- REQUIRED JSON FIELD --}}
<input type="hidden" name="preparation_data" id="preparation_data">

{{-- ================= YEAR ================= --}}
<div class="card card-primary">
    <div class="card-header">Year</div>
    <div class="card-body col-md-4">
        <select class="form-control" id="yearSelect" name="year" required>
            <option value="">-- Select Year --</option>
            @php
                $currentEthiopianYear = \App\Helpers\EthiopianYear::now();
            @endphp
            @for($y = $currentEthiopianYear - 2; $y <= $currentEthiopianYear + 3; $y++)
                <option value="{{ $y }}">{{ $y }} EC</option>
            @endfor
        </select>
    </div>
</div>

{{-- ================= SEEDLING TYPES ================= --}}
<div class="card card-success">
    <div class="card-header">Seedling Type</div>
    <div class="card-body row">

        @foreach($seedlingTypes as $type)
        <div class="col-md-6">
            <div class="info-box seedling-type-card"
                 data-id="{{ $type->id }}"
                 data-name="{{ $type->name }}">

                <span class="info-box-icon bg-success">
                    <i class="fas fa-tree"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">
                        {{ $type->name }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- ================= PREPARATION ================= --}}
<div id="preparationContainer" style="display:none">

<div class="card card-info">
    <div class="card-header">
        Seedlings for <span id="selectedTypeName"></span>
    </div>
    <div class="card-body">
        <div class="row" id="seedlingsContainer"></div>
    </div>
</div>

</div>

{{-- ================= ACTION ================= --}}
<div class="card">
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Save
        </button>
    </div>
</div>

</form>
</div>
</section>

{{-- ================= TEMPLATE ================= --}}
<template id="seedlingTemplate">
<div class="col-md-6 seedling-row mb-3" data-id="">
    <div class="card card-outline card-warning">
        <div class="card-header">
            <strong class="seedling-name"></strong>
        </div>
        <div class="card-body">
            <input type="number" class="form-control mb-2 hanga" placeholder="Hanga Qophii">
            <input type="number" class="form-control mb-2 nama" placeholder="Nama Hirmaate">
            <input type="number" class="form-control baayina" placeholder="Baayina Guyyaa">
        </div>
    </div>
</div>
</template>

{{-- ================= JS ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    let selectedTypeId = null;

    document.querySelectorAll('.seedling-type-card').forEach(card => {
        card.addEventListener('click', function () {

            document.querySelectorAll('.seedling-type-card')
                .forEach(c => c.classList.remove('bg-light'));

            this.classList.add('bg-light');

            selectedTypeId = this.dataset.id;
            document.getElementById('selectedTypeName').innerText = this.dataset.name;
            document.getElementById('preparationContainer').style.display = 'block';

            fetch(`/sector/seedling-preparations/seedlings-by-type/${selectedTypeId}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('seedlingsContainer');
                    container.innerHTML = '';

                    data.forEach(s => {
                        const tpl = document.getElementById('seedlingTemplate').content.cloneNode(true);
                        const row = tpl.querySelector('.seedling-row');

                        row.dataset.id = s.id;
                        tpl.querySelector('.seedling-name').innerText = s.name;

                        container.appendChild(tpl);
                    });
                });
        });
    });

    document.getElementById('mainForm').addEventListener('submit', function (e) {

        if (!selectedTypeId) {
            e.preventDefault();
            alert('Select seedling type');
            return;
        }

        const seedlings = [];
        let hasData = false;

        document.querySelectorAll('.seedling-row').forEach(row => {
            const hanga = Number(row.querySelector('.hanga').value || 0);
            const nama = Number(row.querySelector('.nama').value || 0);
            const baayina = Number(row.querySelector('.baayina').value || 0);

            if (hanga > 0 || nama > 0 || baayina > 0) {
                hasData = true;
            }

            seedlings.push({
                seedling_id: row.dataset.id,
                hanga_qophii: hanga,
                nama_hirmaate_qophii: nama,
                baayina_guyyaa_qophii: baayina
            });
        });

        if (!hasData) {
            e.preventDefault();
            alert('Fill at least one seedling');
            return;
        }

        const payload = {
            seedling_type_id: selectedTypeId,
            selected_seedlings: seedlings
        };

        document.getElementById('preparation_data').value = JSON.stringify(payload);
    });

});
</script>

<style>
.seedling-type-card { cursor:pointer }
.seedling-type-card.bg-light { border:2px solid #28a745 }
</style>

</x-layout>
