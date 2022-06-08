@error('file')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

@if (session('files'))
    <div class="alert alert-success">
        <form action="{{route('files.parse')}}" method="post">
            @csrf
            @foreach(unserialize(session('files')) as $item)
                <label for="chexbox">{{$item->name}}</label>
                <input id="chexbox" type="checkbox" name="file_to_parse[]"  value="{{$item->id}}" checked>
            @endforeach
            <button>SEND</button>
        </form>

    </div>
@endif


<form action="{{route('files.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <lable>Files:</lable>
    <input multiple type="file" name="file[]">
    <button>SEND</button>
</form>
