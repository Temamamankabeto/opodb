<x-layout>
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header text-center">
                <h2>Hojiilee Qonnaa</h2>
            </div>

            <div class="card-body">
                <form action="{{ route('qonnas.store') }}" method="POST">
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

                    <!-- Section 1: Haala Qonnaa -->

                    <div class="mb-3 col-md-4">
                            <label>Gosa Midhaanii</label>
                            <select name="crop_type_id" id="crop_type_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($cropTypes as $type)
                                    <option value="{{ $type->id }}"> </option>
                                @endforeach
                            </select>
                        </div>

                         <div class="mb-3 col-md-4">
                            <label>Maqaa Midhaanii</label>
                            <select name="maqaa_midhaanii" id="maqaa_midhaanii" class="form-control" required>
                                <option value="">-- Select --</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label>Woqtii</label>
                            <select name="crop_type_id" id="crop_type_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($cropTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->id }}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Haala Qonnaa</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label for="haala_qonnaa" class="form-label">Haala Qonnaa</label>
                                <select class="form-control" id="haala_qonnaa" name="haala_qonnaa">
                                    <option value="">-- Select --</option>
                                    <option value="Jallisiin" {{ old('haala_qonnaa') == 'Jallisiin' ? 'selected' : '' }}>Jallisiin</option>
                                    <option value="Rooba uumamaatiin" {{ old('haala_qonnaa') == 'Rooba uumamaatiin' ? 'selected' : '' }}>Rooba uumamaatiin</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="lafa_qotame" class="form-label">Lafa Qotame</label>
                                <input type="number" step="any" class="form-control" id="lafa_qotame" name="lafa_qotame" value="{{ old('lafa_qotame') }}" oninput="calculateValues()">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="sadarkaa_qonnaa" class="form-label">Sadarkaa Qonnaa</label>
                                <select class="form-control" id="sadarkaa_qonnaa" name="sadarkaa_qonnaa">
                                    <option value="">-- Select --</option>
                                    <option value="1ffaa" {{ old('sadarkaa_qonnaa') == '1ffaa' ? 'selected' : '' }}>1ffaa</option>
                                    <option value="2ffaa" {{ old('sadarkaa_qonnaa') == '2ffaa' ? 'selected' : '' }}>2ffaa</option>
                                    <option value="3ffaa" {{ old('sadarkaa_qonnaa') == '3ffaa' ? 'selected' : '' }}>3ffaa</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="traaktara_hirmaate" class="form-label">Traaktara Hirmaate</label>
                                <input type="number" class="form-control" id="traaktara_hirmaate" name="traaktara_hirmaate" value="{{ old('traaktara_hirmaate') }}" oninput="calculateValues()">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Lafa Tiraakta Tokkoon Qotame</label>
                                <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Rakkoolee fi Furmaatota -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Rakkoolee fi Furmaatota</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label for="rakkoo_muudate" class="form-label">Rakkoo Muudate</label>
                                <input type="text" class="form-control" id="rakkoo_muudate" name="rakkoo_muudate" value="{{ old('rakkoo_muudate') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="furmaata" class="form-label">Furmaata Kenname</label>
                                <input type="text" class="form-control" id="furmaata" name="furmaata" value="{{ old('furmaata') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="carraa_argame" class="form-label">Carraa Argame</label>
                                <input type="text" class="form-control" id="carraa_argame" name="carraa_argame" value="{{ old('carraa_argame') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="deeggarsa" class="form-label">Deeggarsa Barbaadamu</label>
                                <input type="text" class="form-control" id="deeggarsa" name="deeggarsa" value="{{ old('deeggarsa') }}">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="rakkoo_deddeebie" class="form-label">Rakkoo Deddeebiidhaan Muudate</label>
                                <input type="text" class="form-control" id="rakkoo_deddeebie" name="rakkoo_deddeebie" value="{{ old('rakkoo_deddeebie') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Carraa Hojii -->
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Carraa Hojii</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label for="baayina_dhiiraa" class="form-label">Baayʼina Dhiiraa</label>
                                <input type="text" class="form-control" id="baayina_dhiiraa" name="baayina_dhiiraa" value="{{ old('baayina_dhiiraa') }}" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="baayina_durbaa" class="form-label">Baayʼina Durbaa</label>
                                <input type="text" class="form-control" id="baayina_durbaa" name="baayina_durbaa" value="{{ old('baayina_durbaa') }}" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="baayina_woliigalaa" class="form-label">Woliigala Carraa Hojii</label>
                                <input type="text" class="form-control" id="baayina_woliigalaa" name="baayina_woliigalaa" value="{{ old('baayina_woliigalaa') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Dhaabbii Woliigalaa</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label for="dhabbii_dhiiraa" class="form-label">Dhaabbii Dhiiraa</label>
                                <input type="text" class="form-control" id="dhabbii_dhiiraa" name="dhabbii_dhiiraa" value="{{ old('dhabbii_dhiiraa') }}" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="dhabbii_durbaa" class="form-label">Dhaabbii Dubaraa</label>
                                <input type="text" class="form-control" id="dhabbii_durbaa" name="dhabbii_durbaa" value="{{ old('dhabbii_durbaa') }}" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="dhabbii_woliigalaa" class="form-label">Dhaabbii Woliigalaa</label>
                                <input type="text" class="form-control" id="dhabbii_woliigalaa" name="dhabbii_woliigalaa" value="{{ old('dhabbii_woliigalaa') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Kan Yeroo Woliigalaa</strong>
                        </div>
                        <div class="card-body row">
                            <div class="mb-3 col-md-4">
                                <label for="yeroo_dhiiraa" class="form-label">Kan Yeroo Dhiiraa</label>
                                <input type="text" class="form-control" id="yeroo_dhiiraa" name="yeroo_dhiiraa" value="{{ old('yeroo_dhiiraa') }}" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="yeroo_durbaa" class="form-label">Kan Yeroo Dubaraa</label>
                                <input type="text" class="form-control" id="yeroo_durbaa" name="yeroo_durbaa" value="{{ old('yeroo_durbaa') }}" oninput="calculateValues()">
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="yeroo_woliigalaa" class="form-label">Kan Yeroo Woliigalaa</label>
                                <input type="text" class="form-control" id="yeroo_woliigalaa" name="yeroo_woliigalaa" value="{{ old('yeroo_woliigalaa') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm rounded mb-4">
                        <div class="card-header bg-light">
                            <strong>Dargaggootas  </strong>
                        </div>
                <div class="card-body row">
                    <div class="mb-3 col-md-4">
                    <label for="dargaggoota_dhiiraa" class="form-label">Dargaggoota Dhiiraa</label>
                    <input type="number" sdargaggoota_dhiiraatep="any" class="form-control" id="dargaggoota_dhiiraa" name="dargaggoota_dhiiraa" value="{{ old('dargaggoota_dhiiraa') }}">
                    </div>
                <div class="mb-3 col-md-4">
                    <label for="dargaggoota_durbaa" class="form-label">Dargaggoota Dubaraa</label>
                    <input type="number" step="any" class="form-control" id="dargaggoota_durbaa" name="dargaggoota_durbaa" value="{{ old('dargaggoota_durbaa') }}">
                </div>
                <div class="mb-3 col-md-4">
                    <label for="dargaggoota_woliigalaa" class="form-label">Dargaggoota Woliigalaa</label>
                    <input type="number" step="any" class="form-control" id="dargaggoota_woliigalaa" name="dargaggoota_woliigalaa" value="{{ old('dargaggoota_woliigalaa') }}">
                </div>
                </div>
                </div>

            </div>
            </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('qonnas.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function calculateValues() {
            // Get values from inputs
            var lafa_qotame = parseFloat(document.getElementById('lafa_qotame').value) || 0;
            var traaktara_hirmaate = parseFloat(document.getElementById('traaktara_hirmaate').value) || 1; // prevent division by 0
            var baayina_dhiiraa = parseFloat(document.getElementById('baayina_dhiiraa').value) || 0;
            var baayina_durbaa = parseFloat(document.getElementById('baayina_durbaa').value) || 0;
            var dhabbii_dhiiraa = parseFloat(document.getElementById('dhabbii_dhiiraa').value) || 0;
            var dhabbii_durbaa = parseFloat(document.getElementById('dhabbii_durbaa').value) || 0;
            var yeroo_dhiiraa = parseFloat(document.getElementById('yeroo_dhiiraa').value) || 0;
            var yeroo_durbaa = parseFloat(document.getElementById('yeroo_durbaa').value) || 0;

            var dargaggoota_dhiiraa = parseFloat(document.getElementById('dargaggoota_dhiiraa').value) || 0;
            var dargaggoota_durbaa = parseFloat(document.getElementById('dargaggoota_durbaa').value) || 0;

            // Calculate values
            var lafa_tiraakta_tokkoon_qotame = lafa_qotame / traaktara_hirmaate;
            var woliigala_carraa_hojii = baayina_dhiiraa + baayina_durbaa;
            var dhaabbii_woliigalaa = dhabbii_dhiiraa + dhabbii_durbaa;
            var kan_yeroo_woliigalaa = yeroo_dhiiraa + yeroo_durbaa;
            var dargaggoota_woliigalaa = dargaggoota_dhiiraa + dargaggoota_durbaa;

            // Set the calculated values
            document.getElementById('lafa_tiraakta_tokkoon_qotame').value = lafa_tiraakta_tokkoon_qotame.toFixed(2);
            document.getElementById('baayina_woliigalaa').value = woliigala_carraa_hojii;
            document.getElementById('dhabbii_woliigalaa').value = dhaabbii_woliigalaa;
            document.getElementById('yeroo_woliigalaa').value = kan_yeroo_woliigalaa;
            document.getElementById('dargaggoota_woliigalaa').value = dargaggoota_woliigalaa;
        }
    </script>
</x-layout>
