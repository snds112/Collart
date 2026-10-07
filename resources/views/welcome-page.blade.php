<!doctype html>
<html lang="en">

<head>
    <title>COLLART</title>
    <link rel="icon" href="{{ asset('/images/logo.jpg') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/bootstrap.min.css') }}">

    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="{{ asset('/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <script src="https://kit.fontawesome.com/your-fontawesome-kit-code.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/welcome-page.css') }}">



    <script src="{{ asset('/bootstrap/js/jquery-3.7.1.min.js') }}"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>


    @if (session()->has('success'))
        <div class="container container--narrow">
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('failure'))
        <div class="container container--narrow">
            <div class="alert alert-danger text-center">
                {{ session('failure') }}
            </div>
        </div>
    @endif
    <div class="container">
        <div class="row no-gutters shadow-lg" style="margin: 0.5rem !important;">
            <div class="col-md-8">
                <div class="sidebar">
                    <!-- Contenu de la colonne de gauche -->
                    <div class="col-md-8-sidebar">
                        <span class="collart">COLLART</span>




                    </div>
                    <div class="card-slider">
                        <div class="photo-grid">
                            <img src="{{ asset('/images/1.jpg') }}" alt="Description de l'image 1">
                            <img src="{{ asset('/images/2.jpg') }}" alt="Description de l'image 2">
                            <img src="{{ asset('/images/3.jpg') }}" alt="Description de l'image 3">
                        </div>
                        <div class="photo-grid">
                            <img src="{{ asset('/images/4.jpg') }}" alt="Description de l'image 4">
                            <img src="{{ asset('/images/5.jpg') }}" alt="Description de l'image 5">
                            <img src="{{ asset('/images/6.jpg') }}" alt="Description de l'image 6">
                        </div>
                        <div class="photo-grid">
                            <img src="{{ asset('/images/7.jpg') }}" alt="Description de l'image 7">
                            <img src="{{ asset('/images/8.jpg') }}" alt="Description de l'image 8">
                            <img src="{{ asset('/images/9.jpg') }}" alt="Description de l'image 9">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content">

                    <div class="inner-container">

                        <h2>
                            <img src="{{ asset('/images/logo.jpg') }}" alt="logo" class="logo">

                            <button><a href="/signup-login" class="button"
                                    style="text-decoration: none ;color: #000;">Login</a></button>

                            <p>Don't have an account? <a href="/signup-login" class="signup" style="color: #000;">Sign
                                    Up</a>
                            </p>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>





</body>

</html>
