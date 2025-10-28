<!-- resources/views/kunuunsas/edit.blade.php -->
<x-layout>
    <div class="container">
        <h2>Edit Kunuunsa</h2>

        <form action="{{ route('kunuunsas.update', $kunuunsa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
            <div class="mb-3 col-md-4">
                <label for="lafa_harkaan_aramame" class="form-label">Lafa Harkaan Aramame</label>
                <input type="number" step="any" class="form-control" id="lafa_harkaan_aramame" name="lafa_harkaan_aramame" value="{{ old('lafa_harkaan_aramame', $kunuunsa->lafa_harkaan_aramame) }}">
            </div>

            <div class="mb-3 col-md-4">
                <label for="lafa_keemikaalaan_aramame" class="form-label">Lafa Keemikaalaan Aramame</label>
                <input type="number" step="any" class="form-control" id="lafa_keemikaalaan_aramame" name="lafa_keemikaalaan_aramame" value="{{ old('lafa_keemikaalaan_aramame', $kunuunsa->lafa_keemikaalaan_aramame) }}">
            </div>

            <div class="mb-3 col-md-4">
                <label for="hamma_keemikaalaa_litiraa" class="form-label">Hamma Keemikaalaa Litiraa</label>
                <input type="number" step="any" class="form-control" id="hamma_keemikaalaa_litiraa" name="hamma_keemikaalaa_litiraa" value="{{ old('hamma_keemikaalaa_litiraa', $kunuunsa->hamma_keemikaalaa_litiraa) }}">
            </div>

            <div class="mb-3 col-md-4">
                <label for="lafa_dhibeen_tooatame" class="form-label">Lafa Dhibeen Tooatame</label>
                <input type="number" step="any" class="form-control" id="lafa_dhibeen_tooatame" name="lafa_dhibeen_tooatame" value="{{ old('lafa_dhibeen_tooatame', $kunuunsa->lafa_dhibeen_tooatame) }}">
            </div>

            <div class="mb-3 col-md-4">
                <label for="lafa_ilbiisni_toatame" class="form-label">Lafa Ilbiisni Tooatame</label>
                <input type="number" step="any" class="form-control" id="lafa_ilbiisni_toatame" name="lafa_ilbiisni_toatame" value="{{ old('lafa_ilbiisni_toatame', $kunuunsa->lafa_ilbiisni_toatame) }}">
            </div>
            <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('kunuunsas.index') }}" class="btn btn-secondary">Back</a>
            </div>

        </form>
    </div>
</x-layout>
