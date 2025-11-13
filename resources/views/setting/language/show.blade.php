<x-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Language: {{ strtoupper($lang) }}</h5>
                @if (empty($json))
                    <div class="alert alert-info">No content found in this language file.</div>
                @else
                    <div class="card shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Key</th>
                                        <th width="60%">Translation</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($json as $key => $value)
                                        <tr>
                                            <td class="font-monospace">{{ $key }}</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                <div class="mt-3">
                    <a href="{{ route('languages.edit', $lang) }}" class="btn btn-warning" title="Edit Language File">
                        Edit
                        <i class="bx bx-edit-alt"></i>
                    </a>
                    <a href="{{ route('languages.list') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Languages
                    </a>
                </div>

            </div>
        </div>
    </div>

</x-layout>
