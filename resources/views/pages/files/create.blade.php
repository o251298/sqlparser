@extends('layouts.master')
@section('title', 'Create databases')
@section('content')
    <div class="content">
        <!-- HAS ERROR -->
        @error('file')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @if(session('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        <!-- END HAS ERROR -->

        <!-- SUCCESS UPLOAD -->
        @if (session('files'))
                    <div class="alert alert-success">
                        <p>
                            Upload success
                        </p>
                    </div>
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
                            @foreach(unserialize(session('files')) as $item)
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
        @endif
        <!-- END SUCCESS UPLOAD -->


        <!-- FILE UPLOAD -->
        <form action="{{route('files.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
                <input multiple type="file" name="file[]" class="form-control" id="inputGroupFile02">
                <label class="input-group-text" for="inputGroupFile02">Upload</label>
            </div>
            <button class="btn btn-primary">Upload file(s)</button>
        </form>
        <!-- END FILE UPLOAD -->
    </div>
@endsection
