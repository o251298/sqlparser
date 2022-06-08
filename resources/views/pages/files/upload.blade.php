<ul>
    @foreach($files as $key => $val)
        <li>{{$key}}</li>
        @foreach($val as $file)
            <a href="{{$file}}">download</a>
        @endforeach
    @endforeach
</ul>
