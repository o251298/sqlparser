@extends('layouts.master')
@section('title', 'List all databases')
@section('content')
    <div class="content">
        @if(session('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        @if(count($files) > 0)
            <form action="{{route('files.parse')}}" method="post">
                @csrf
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Filter</th>
                        <th scope="col">Download one file?</th>
                        <th scope="col">Select table</th>
                        <th scope="col">Format download</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">#</th>
                        <td>
                            <label for="yes">Yes</label>
                            <input class="form-check-input" type="radio" name="select" id="yes" value="yes">
                            <label for="no">No</label>
                            <input class="form-check-input" type="radio" name="select" id="no" value="no" checked>
                        </td>
                        <td>
                            <input type="text" class="form-control" value="posts" readonly name="table">
                        </td>
                        <td>
                            <select class="form-select" name="export_format">
                                <option value="csv">CSV</option>
                                <option value="xml">XML</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Databases</th>
                        <th scope="col">Select</th>
                        <th scope="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($files as $item)
                        <tr>
                            <th scope="row">{{$item->id}}</th>
                            <td>{{$item->name}}</td>
                            <td><input id="chexbox" type="checkbox" name="file_to_parse[]"  value="{{$item->id}}" checked></td>
                            <td> <a href="{{route('destroy', [$item])}}" class="btn btn-danger">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success">Parse</button>
            </form>
        @else
            <div>
                <p>Databases not found <a href="{{route('files.create')}}">Upload</a></p>
            </div>
        @endif

    </div>
@endsection

