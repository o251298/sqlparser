@extends('layouts.master')
@section('title', 'Список всых баз')
@section('content')
    <div class="content">
        @if(count($files) > 0)
            <form action="{{route('files.parse')}}" method="post">
                @csrf
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Filter</th>
                        <th scope="col">Выгрузить одним файлом?</th>
                        <th scope="col">Искать таблицу</th>
                        <th scope="col">Формат выгрузки</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">#</th>
                        <td>
                            <label for="yes">Да</label>
                            <input class="form-check-input" type="radio" name="select" id="yes" value="yes">
                            <label for="no">Нет</label>
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
                        <th scope="col">База данных</th>
                        <th scope="col">Выбрать</th>
                        <th scope="col">Удалить</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($files as $item)
                        <tr>
                            <th scope="row">{{$item->id}}</th>
                            <td>{{$item->name}}</td>
                            <td><input id="chexbox" type="checkbox" name="file_to_parse[]"  value="{{$item->id}}" checked></td>
                            <td> <a href="{{route('destroy', [$item])}}" class="btn btn-danger">DELETE</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="btn btn-success">Парсить</button>
            </form>
        @else
            <div>
                <p>Файлов еще нету... <a href="{{route('files.create')}}">Загрузить</a></p>
            </div>
        @endif

    </div>
@endsection

