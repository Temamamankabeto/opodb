<x-layout>
    <div class="container">
        <h2>Add New Haamaa</h2>
        <div class="card">
            <div class="card-header">
                <h4>Facaasaa Form</h4>
            </div>
        <div class="card-body">
        <form action="{{ route('haamaas.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
             <div class="form-group col-md-4">
                <label for="lafa_maashinaan_haamame">Lafa Maashinaan Haamame</label>
                <input type="number" class="form-control" name="lafa_maashinaan_haamame" id="lafa_maashinaan_haamame" step="any">
            </div>
             <div class="form-group col-md-4">
                <label for="maashina_haamaa_hirmaate">Maashina Haamaa Hirmaate</label>
                <input type="number" class="form-control" name="maashina_haamaa_hirmaate" id="maashina_haamaa_hirmaate">
            </div>
             <div class="form-group col-md-4">
                <label for="lafa_harkaan_haamame">Lafa Harkaan Haamame</label>
                <input type="number" class="form-control" name="lafa_harkaan_haamame" id="lafa_harkaan_haamame" step="any">
            </div>
             <div class="form-group col-md-4">
                <label for="qotee_bulaa_dhi_haamame">Qotee Bulaa Dhi Haamame</label>
                <input type="number" class="form-control" name="qotee_bulaa_dhi_haamame" id="qotee_bulaa_dhi_haamame">
            </div>
             <div class="form-group col-md-4">
                <label for="qotee_bulaa_du_haamame">Qotee Bulaa Du Haamame</label>
                <input type="number" class="form-control" name="qotee_bulaa_du_haamame" id="qotee_bulaa_du_haamame">
            </div>
             <div class="form-group col-md-4">
                <label for="kan_tumame">Kan Tumame</label>
                <input type="number" class="form-control" name="kan_tumame" id="kan_tumame">
            </div>

            <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Rakkoo Muudate</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                            <!-- <textarea name="" id=""></textarea> -->
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Furmaata Kenname</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Carra Argame</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Deeggarsa Barbaadamu</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Rakkoo Deddeebiidhaan Muudate</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <h2>carraa hojii</h2>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Baayína Dhiiraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Baayína Durbaa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Woliigala Carraa Hojii Uumame</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Dhaabbii Dhiiraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Dhaabbii Dubaraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Kan Dhaabbii woliigalaa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Kan yeroo Dhiiraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Kan yeroo Dubaraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Kan yeroo Woliigalaa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Dargaggoota Dhiiraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Dargaggoota Dubaraa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Dargaggoota Woliigalaa</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}">
                        </div>
            <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('haamaas.index') }}" class="btn btn-secondary">Back</a>
                    </div>
             
        </form>
    </div>
</x-layout>
