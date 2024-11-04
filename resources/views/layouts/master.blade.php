<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>
<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand nav_title" href="#">Assignment Review</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse edit" id="navbarTogglerDemo02">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href='{{route("home")}}'>Home</a>
                    </li>
                </ul>  
               
                @if (Auth::user())
                <div class="login">
                    <p class="nav_text">
                        {{Auth::user()->name}}
                    </p>
                    <p class="nav_text">
                        {{Auth::user()->role}}
                    </p>
                    <form class="form-inline my-2 my-lg-0" method="POST" action="{{url('/logout')}}">
                        {{csrf_field()}}
                        <button class="btn btn-outline-success my-2 my-sm-0 logout" type="submit" value="Logout">Logout</button>
                    </form>
                @else
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class=" nav-item">
                            <a class="nav-link" href="{{route('login')}}">Login</a>
                        </li> 
                        <li class=" nav-item">
                            <a class="nav-link" href="{{route('register')}}">Register</a>
                        </li>
                    </ul>
                </div>
                @endif                
        </div>
    </nav>
@yield('content')
</body>
</html>



