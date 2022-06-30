@extends('layouts.master')
@section('title', 'Register')
@section('content')

<!-- Validation Errors -->
@if ($errors->any())
    <ul class="list-group">
        @foreach ($errors->all() as $error)
            <li class="list-group-item list-group-item-danger"> {{ $error }}</li>
        @endforeach
    </ul>
@endif
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
