<x-layout>
<div class="container py-5">
    <h2>{{ isset($association) ? 'Edit Association' : 'Add New Association' }}</h2>

    <form action="{{ isset($association) ? route('associations.update',$association->id) : route('associations.store') }}" method="POST">
        @csrf
        @if(isset($association)) @method('POST') @endif

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $association->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label>Federation</label>
            <select id="federation" name="federation_id" class="form-select" required>
                <option value="">-- Select Federation --</option>
                @foreach($federations as $f)
                    <option value="{{ $f->id }}" @selected(isset($association) && $association->federation_id==$f->id)>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Union</label>
            <select id="union" name="union_id" class="form-select" required>
                <option value="">-- Select Union --</option>
                @if(isset($association))
                    <option value="{{ $association->union_id }}" selected>{{ $association->union->name ?? '' }}</option>
                @endif
            </select>
        </div>

        <div class="mb-3">
            <label>Zone</label>
            <select id="zone" name="zone_id" class="form-select" required>
                <option value="">-- Select Zone --</option>
                @foreach($zones as $z)
                    <option value="{{ $z->id }}" @selected(isset($association) && $association->zone_id==$z->id)>{{ $z->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Woreda</label>
            <select id="woreda" name="woreda_id" class="form-select" required>
                <option value="">-- Select Woreda --</option>
                @if(isset($association))
                    <option value="{{ $association->woreda_id }}" selected>{{ $association->woreda->name ?? '' }}</option>
                @endif
            </select>
        </div>

        <div class="mb-3">
            <label>Kebele</label>
            <select id="kebele" name="kebele_id" class="form-select" required>
                <option value="">-- Select Kebele --</option>
                @if(isset($association))
                    <option value="{{ $association->kebele_id }}" selected>{{ $association->kebele->name ?? '' }}</option>
                @endif
            </select>
        </div>

        <button class="btn btn-success">{{ isset($association) ? 'Update' : 'Save' }}</button>
        <a href="{{ route('associations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $('#federation').change(function(){
        var id = $(this).val();
        $('#union').html('<option>Loading...</option>');
        $.get('/associations/get-unions/'+id,function(data){
            var options = '<option value="">-- Select Union --</option>';
            $.each(data,function(i,u){ options+='<option value="'+u.id+'">'+u.name+'</option>'; });
            $('#union').html(options);
        });
    });

    $('#zone').change(function(){
        var id = $(this).val();
        $('#woreda').html('<option>Loading...</option>');
        $.get('/associations/get-woredas/'+id,function(data){
            var options = '<option value="">-- Select Woreda --</option>';
            $.each(data,function(i,w){ options+='<option value="'+w.id+'">'+w.name+'</option>'; });
            $('#woreda').html(options);
        });
    });

    $('#woreda').change(function(){
        var id = $(this).val();
        $('#kebele').html('<option>Loading...</option>');
        $.get('/associations/get-kebeles/'+id,function(data){
            var options = '<option value="">-- Select Kebele --</option>';
            $.each(data,function(i,k){ options+='<option value="'+k.id+'">'+k.name+'</option>'; });
            $('#kebele').html(options);
        });
    });

});
</script>
</x-layout>
