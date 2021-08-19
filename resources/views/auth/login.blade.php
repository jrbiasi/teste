<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login {{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">	
    <style>
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-container"> 
            <h4>Login</h4>  
            <form method="POST" action="{{ route('login') }}" class="form-signin">
                @csrf
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="E-mail" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>                
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Senha" required autocomplete="current-password">                
                 @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div id="remember" class="checkbox">
                    <label><input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Lembrar-me</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
