<x-layout>
    <div class="container">
        <h2>Add New Facaasaa</h2>

        <div class="card">
            <div class="card-header">
                <h4>Facaasaa Form</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('facaasaas.store') }}" method="POST">
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
                            <label for="lafa_facae">Lafa Facae</label>
                            <input type="number" step="any" class="form-control" name="lafa_facae" id="lafa_facae">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="mala_facaasaa">Mala Facaasaa</label>
                            <select class="form-control" name="mala_facaasaa" id="mala_facaasaa">
                                <option value="maashinaan">Maashinaan</option>
                                <option value="harkaan">Harkaan</option>
                                <option value="harkaan">Sararaan</option>
                                <option value="harkaan">Bittinneessuun</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lafa_facae">Baayína Sanyii</label>
                            <input type="number" step="any" class="form-control" name="lafa_facae" id="lafa_facae">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lafa_facae">Akaakuu Sanyii</label>
                            <input type="number" step="any" class="form-control" name="lafa_facae" id="lafa_facae">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lafa_facae">Baayína Xaaóo</label>
                            <input type="number" step="any" class="form-control" name="lafa_facae" id="lafa_facae">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lafa_facae">Haala Xaaóo</label>
                            <select class="form-control" name="mala_facaasaa" id="mala_facaasaa">
                                <option value="maashinaan">Kan Uumama</option>
                                <option value="harkaan">Kanaa warshaa/keemikaa</option>                                 
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lafa_facae">Kan uumamaa</label>
                            <select class="form-control" name="mala_facaasaa" id="mala_facaasaa">
                                <option value=" " disabled>select</option>
                                <option value="maashinaan">kompost</option>
                                <option value="harkaan">Kan Raammoo</option>                                 
                                <option value="harkaan">Maanuurii</option>                                 
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="lafa_facae">Kan Keemikaalaa</label>
                            <select class="form-control" name="mala_facaasaa" id="mala_facaasaa">
                                <option value="maashinaan">uuriyaa</option>
                                <option value="harkaan">Daappii</option>                                 
                                <option value="harkaan">MPS</option>                                 
                                <option value="harkaan">K/photasiam</option>                                 
                                <option value="harkaan">Kan biroo</option>                                 
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="qonnaan_bulaa_dhi">Qonnaan Bulaa Dhi</label>
                            <input type="number" class="form-control" name="qonnaan_bulaa_dhi" id="qonnaan_bulaa_dhi">
                        </div>
                         


                        <div class="form-group col-md-4">
                            <label for="qonnaan_bulaa_du">Qonnaan Bulaa Du</label>
                            <input type="number" class="form-control" name="qonnaan_bulaa_du" id="qonnaan_bulaa_du">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="qonnaan_bulaa_du">Baay'ina Xaa'oo saraa</label>
                            <input type="number" class="form-control" name="qonnaan_bulaa_du" id="qonnaan_bulaa_du">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="qonnaan_bulaa_du">Baay'ina Xaa'oo Bittinneessuun </label>
                            <input type="number" class="form-control" name="qonnaan_bulaa_du" id="qonnaan_bulaa_du">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="qonnaan_bulaa_du">Baay'ina Xaa'oo Jala kaa'uudhaan</label>
                            <input type="number" class="form-control" name="qonnaan_bulaa_du" id="qonnaan_bulaa_du">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="qonnaan_bulaa_idaama">Qonnaan Bulaa Woliigalaa</label>
                            <input type="number" class="form-control" name="qonnaan_bulaa_idaama" id="qonnaan_bulaa_idaama" readonly>
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

                        <button type="submit" class="btn btn-success mt-3">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to calculate the sum of Qonnaan Bulaa Dhi and Qonnaan Bulaa Du
        function calculateTotal() {
            var dhi = parseFloat(document.getElementById('qonnaan_bulaa_dhi').value) || 0;
            var du = parseFloat(document.getElementById('qonnaan_bulaa_du').value) || 0;
            var total = dhi + du;

            document.getElementById('qonnaan_bulaa_idaama').value = total;
        }

        // Add event listeners to recalculate the total when values change
        document.getElementById('qonnaan_bulaa_dhi').addEventListener('input', calculateTotal);
        document.getElementById('qonnaan_bulaa_du').addEventListener('input', calculateTotal);
    </script>
</x-layout>
