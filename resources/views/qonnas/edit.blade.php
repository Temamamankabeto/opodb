<x-layout>
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header text-center">
                <h2>Edit Qonna</h2>
            </div>

            <div class="card-body">
            <form action="{{ route('qonnas.update', $qonna->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Method override to indicate PUT request -->

                    <!-- Display Validation Errors -->
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
                        <div class="mb-3 col-md-4">
                            <label for="haala_qonnaa" class="form-label">Haala Qonnaa</label>
                            <select class="form-control" id="haala_qonnaa" name="haala_qonnaa">
                                <option value="">-- Select Haala Qonnaa --</option>
                                <option value="Jallisiin" {{ old('haala_qonnaa', $qonna->haala_qonnaa) == 'Jallisiin' ? 'selected' : '' }}>Jallisiin</option>
                                <option value="Rooba uumamaatiin" {{ old('haala_qonnaa', $qonna->haala_qonnaa) == 'Rooba uumamaatiin' ? 'selected' : '' }}>Rooba uumamaatiin</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="lafa_qotame" class="form-label">Lafa Qotame</label>
                            <input type="number" step="any" class="form-control" id="lafa_qotame" name="lafa_qotame" value="{{ old('lafa_qotame', $qonna->lafa_qotame) }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="sadarkaa_qonnaa" class="form-label">Sadarkaa Qonnaa</label>
                            <select class="form-control" id="sadarkaa_qonnaa" name="sadarkaa_qonnaa">
                                <option value="">-- Select Sadarkaa Qonnaa --</option>
                                <option value="1ffaa" {{ old('sadarkaa_qonnaa', $qonna->sadarkaa_qonnaa) == '1ffaa' ? 'selected' : '' }}>1ffaa</option>
                                <option value="2ffaa" {{ old('sadarkaa_qonnaa', $qonna->sadarkaa_qonnaa) == '2ffaa' ? 'selected' : '' }}>2ffaa</option>
                                <option value="3ffaa" {{ old('sadarkaa_qonnaa', $qonna->sadarkaa_qonnaa) == '3ffaa' ? 'selected' : '' }}>3ffaa</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="traaktara_hirmaate" class="form-label">Traaktara Hirmaate</label>
                            <input type="number" class="form-control" id="traaktara_hirmaate" name="traaktara_hirmaate" value="{{ old('traaktara_hirmaate', $qonna->traaktara_hirmaate) }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="lafa_tiraakta_tokkoon_qotame" class="form-label">Lafa Tiraakta Tokkoon Qotame</label>
                            <input type="number" step="any" class="form-control" id="lafa_tiraakta_tokkoon_qotame" name="lafa_tiraakta_tokkoon_qotame" value="{{ old('lafa_tiraakta_tokkoon_qotame', $qonna->lafa_tiraakta_tokkoon_qotame) }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('qonnas.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
