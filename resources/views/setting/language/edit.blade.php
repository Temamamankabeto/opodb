<x-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-xl">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Language: {{ $lang }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('languages.update', $lang) }}" class="card p-4 shadow-sm border rounded">
                        @csrf
                        @method('PUT')
                        <h4 class="mb-4">Edit Language Translations</h4>

                        <div class="mb-3">
                            <label class="form-label">Translations</label>
                            <div id="translations">
                                @foreach($translations as $key => $value)
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" class="form-control" name="dynamic_keys[]" value="{{ $key }}" placeholder="Translation key">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" name="dynamic_values[]" value="{{ $value }}" placeholder="Translation value">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addTranslationField()">Add More</button>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Update Language</button>
                            <a href="{{ route('languages.list') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addTranslationField() {
            const container = document.getElementById('translations');
            const row = document.createElement('div');
            row.className = 'row mb-2';

            row.innerHTML = `
                <div class="col">
                    <input type="text" class="form-control" name="dynamic_keys[]" placeholder="New translation key">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="dynamic_values[]" placeholder="New translation value">
                </div>
            `;
            container.appendChild(row);
        }
    </script>
</x-layout>
