<x-layout>
<div class="container py-5">

    <h3 class="fw-bold mb-4">Add Educational Participation</h3>

    <form action="{{ route('educational_participations.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Enter name" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Type</label>
            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="">Select Type</option>
                <option value="Hirmaannaa fi Ittifufiinsa" {{ old('type') == 'Hirmaannaa fi Ittifufiinsa' ? 'selected' : '' }}>Hirmaannaa fi Ittifufiinsa</option>
                <option value="Bu’a-qabeessummaa Barnootaa" {{ old('type') == 'Bu’a-qabeessummaa Barnootaa' ? 'selected' : '' }}>Bu’a-qabeessummaa Barnootaa</option>
            </select>
            @error('type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

         <div class="mb-3">
            <label class="form-label fw-bold">Student Type</label>
            <select name="student_type" class="form-control @error('student_type') is-invalid @enderror" required>
                <option value="">Select Type</option>
                <option value="Barataa Idilee" {{ old('student_type') == 'Barataa Idilee' ? 'selected' : '' }}>Barataa Idilee</option>
                <option value="Ga`eessota" {{ old('student_type') == 'Ga`eessota' ? 'selected' : '' }}>Ga`eesso</option>
            </select>
            @error('student_type')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        

        <button class="btn btn-success">Save</button>
        <a href="{{ route('educational_participations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
</x-layout>