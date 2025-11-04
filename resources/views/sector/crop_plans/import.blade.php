<x-layout>
<div class="container-fluid py-4">
    <h2 class="mb-4"><i class="mdi mdi-file-import text-success"></i> Import Crop Plans</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('sector.crop-plans.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Select Excel File</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success"><i class="mdi mdi-file-import"></i> Import</button>
    </form>
</div>
</x-layout>
