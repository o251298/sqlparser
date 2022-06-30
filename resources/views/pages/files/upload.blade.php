@extends('layouts.master')
@section('title', 'Parse result')
@section('content')
    <div class="content">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Databases</th>
                <th scope="col">Download</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $key => $val)
            <tr>
                <th scope="row">#</th>
                <td>{{$key}}</td>
                <td>
                    @foreach($val as $file)
                        <form action="{{route('download')}}" method="post">
                            @csrf
                            <input type="hidden" name="link" value="{{$file}}">
                            <button class="btn btn-outline-success">Download</button>
                        </form>
                    @endforeach
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
