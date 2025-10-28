<!-- resources/views/kunuunsas/create.blade.php -->
<x-layout>
    <div class="container">
        <h2> Kunuunsa</h2>
        
        <div class="card">
            <div class="card-header">
    </div>
    <div class="card-body">

        <form action="{{ route('kunuunsas.store') }}" method="POST">
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

<h2>jallisii</h2>
                    <div class="row">
             <div class="mb-3 col-md-4">
                <label for="lafa_harkaan_aramame" class="form-label">Lafa Bishaaniin argachuu danda'u</label>
                <input type="number" step="any" class="form-control" id="lafa_harkaan_aramame" name="lafa_harkaan_aramame" value="{{ old('lafa_harkaan_aramame') }}">
            </div>
            <div class="mb-3 col-md-4">
                <label for="lafa_harkaan_aramame" class="form-label">Lafa Bishaaniin Dhuge</label>
                <input type="number" step="any" class="form-control" id="lafa_harkaan_aramame" name="lafa_harkaan_aramame" value="{{ old('lafa_harkaan_aramame') }}">
            </div>
            <div class="mb-3 col-md-4">
                <label for="lafa_harkaan_aramame" class="form-label">Mala Dhugaatii</label>
                <select class="form-control" name="mala_facaasaa" id="mala_facaasaa">
                                <option value="maashinaan">Sararaan/furrow</option>
                                <option value="harkaan">Drip</option>
                                <option value="harkaan">Sprinkiler</option>
                                <option value="harkaan">kan biroo</option>
                            </select>
            </div>
            <div class="mb-3 col-md-4">
                <label for="lafa_harkaan_aramame" class="form-label">MAdda Bishaanii</label>
                <select class="form-control" name="mala_facaasaa" id="mala_facaasaa">
                                <option value="maashinaan">Bishaan Haroo</option>
                                <option value="harkaan">Bishaan Laga</option>                               
                                <option value="harkaan">Dhiyeessii Ammayyaa</option>                               
                            </select>
                        </div>
         </div>

         <hr>
         <h2>Aramaa</h2>
                    <div class="row">
             <div class="mb-3 col-md-4">
                <label for="lafa_harkaan_aramame" class="form-label">Lafa Harkaan Aramame</label>
                <input type="number" step="any" class="form-control" id="lafa_harkaan_aramame" name="lafa_harkaan_aramame" value="{{ old('lafa_harkaan_aramame') }}">
            </div>
             <div class="mb-3 col-md-4">
                <label for="lafa_keemikaalaan_aramame" class="form-label">Lafa Keemikaalaan Aramame</label>
                <input type="number" step="any" class="form-control" id="lafa_keemikaalaan_aramame" name="lafa_keemikaalaan_aramame" value="{{ old('lafa_keemikaalaan_aramame') }}">
            </div>
             <div class="mb-3 col-md-4">
                <label for="hamma_keemikaalaa_litiraa" class="form-label">Hamma Keemikaalaa Litiraa/kg</label>
                <input type="number" step="any" class="form-control" id="hamma_keemikaalaa_litiraa" name="hamma_keemikaalaa_litiraa" value="{{ old('hamma_keemikaalaa_litiraa') }}">
            </div><div class="mb-3 col-md-4">
                <label for="hamma_keemikaalaa_litiraa" class="form-label">Bu' Qabeessummaa</label>
                <input type="number" step="any" class="form-control" id="hamma_keemikaalaa_litiraa" name="hamma_keemikaalaa_litiraa" value="{{ old('hamma_keemikaalaa_litiraa') }}">
            </div>
            </div>
            <div class="row">
             <div class="mb-3 col-md-4">
                <label for="lafa_dhibeen_tooatame" class="form-label">Lafa Dhibeen Tooatame</label>
                <input type="number" step="any" class="form-control" id="lafa_dhibeen_tooatame" name="lafa_dhibeen_tooatame" value="{{ old('lafa_dhibeen_tooatame') }}">
            </div>
             <div class="mb-3 col-md-4">
                <label for="lafa_ilbiisni_toatame" class="form-label">Lafa Ilbiisni Tooatame</label>
                <input type="number" step="any" class="form-control" id="lafa_ilbiisni_toatame" name="lafa_ilbiisni_toatame" value="{{ old('lafa_ilbiisni_toatame') }}">
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
                        <a href="{{ route('kunuunsas.index') }}" class="btn btn-secondary">Back</a>
                    </div>

            
        </form>
    </div>
    </div>
    </div>
</x-layout>
