<?php
use App\Services\Parsers\SQLParser\SQLParseFile;
?>
<form action="{{route('sql.create')}}" method="post">
    @csrf
    @foreach($files as $file)
        <p>{{$file->getTableName()}}</p>
        @foreach(array_keys($file->getInsertTables()) as $item)
            <div>
                <lable for="checkbox">{{$item}}</lable><br>
                <input type="checkbox" name="sql[]" value="{{serialize($file)}}" @if(SQLParseFile::checkPostsTable($item)) ? checked : null @endif id="checkbox">
            </div>
            <hr>
        @endforeach
    @endforeach
        <button>
            Save
        </button>
</form>

