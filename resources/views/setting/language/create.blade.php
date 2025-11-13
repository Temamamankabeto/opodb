<x-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-xl">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>User Registration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('languages.store') }}" class="card p-4 shadow-sm border rounded">
                        @csrf
                        <h4 class="mb-4">Add New Language</h4>

                        <!-- Language Code -->
                        <div class="mb-3">
                            <label class="form-label">Language Code <small class="text-muted">(e.g., fr, om)</small></label>
                            <input type="text" name="locale" class="form-control" placeholder="Enter language code">
                            @error('locale')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Translations</label>
                            <div id="translations">
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" class="form-control" name="dynamic_keys[]" placeholder="Translation key (e.g. welcome)">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" name="dynamic_values[]" placeholder="Translation value (e.g. Welcome)">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addTranslationField()">Add More</button>
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Save Language</button>
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
                <input type="text" class="form-control" name="dynamic_keys[]" placeholder="Translation key (e.g. contact)">
            </div>
            <div class="col">
                <input type="text" class="form-control" name="dynamic_values[]" placeholder="Translation value (e.g. Contact)">
            </div>
        `;
            container.appendChild(row);
        }
    </script>


</x-layout>
