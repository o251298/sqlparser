<a href="{{route('files.create')}}">Create file</a>
@if(count($files) > 0)
    <form action="{{route('files.parse')}}" method="post">
        @csrf
        @foreach($files as $item)
            <ul>
                <li>
                    <label for="chexbox">{{$item->name}}</label>
                    <input id="chexbox" type="checkbox" name="file_to_parse[]"  value="{{$item->id}}" checked>
                </li>
            </ul>
        @endforeach
        <button>SEND</button>
    </form>
@endif


