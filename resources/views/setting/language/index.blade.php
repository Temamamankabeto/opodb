<x-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Available Languages</h5>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if ($languages->isEmpty())
                        <div class="alert alert-warning">No languages found.</div>
                    @else
                        <ul>
                            @foreach ($languages as $language)
                                {{--  --}}
                                <li>{{ $language }}
                                    <a class="btn btn-outline-primary btn-sm me-2"
                                        href="{{ route('languages.show', $language) }}">View</a> |
                                    <a class="btn btn-outline-primary btn-sm me-2"
                                        href="{{ route('languages.edit', $language) }}">Edit</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('languages.create') }}" class="btn btn-primary">Add New Language</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
