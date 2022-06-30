<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>@yield('title', 'Main')</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        @if (Route::has('login'))
        <a class="navbar-brand" href="/">SQLPARSER</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{route('files.list')}}">List databases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('files.create')}}">Upload databases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('truncate')}}">Truncate files storage</a>
                </li>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <li class="nav-item">
                            <a class="nav-link" :href="route('logout')"
                               onclick="event.preventDefault();
                                                this.closest('form').submit();">Log Out</a>
                        </li>
                    </form>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('login')}}">Sing in</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('register')}}">Register</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
        @endif
    </div>
</nav>

