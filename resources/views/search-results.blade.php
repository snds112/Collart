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
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/search-results.css') }}">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/layout.css') }}">


    <script src="{{ asset('/bootstrap/js/jquery-3.7.1.min.js') }}"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>

    <header class="container d-flex flex-wrap justify-content-between align-items-center py-3 mb-4 border-bottom"
        id="header">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="32" height="32" role="img">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <h1 class="title" style="font-family: serif; font-weight: bold; font-size: 3rem;">Collart</h1>
        </a>

        <div class="d-flex align-items-center">
            <form class="search-form" action="/search" method="POST">
                @csrf
                <span class="material-symbols-outlined search-icon"
                    onclick="event.preventDefault(); $(this).closest('form').submit();">search</span>
                <input class="search-input form-control border-0 py-2" type="search" placeholder="Search"
                    aria-label="Search" name="searchTerm">
            </form>
            <a href="/home">
                <span class="material-symbols-outlined me-3 fs-2">home</span>
            </a>
            <a href="/profile/{{ auth()->user()->username }}">
                <span class="material-symbols-outlined me-4 fs-2">account_circle</span>
            </a>

            @php
                $user = App\Models\Account::find(auth()->user()->id);
            @endphp
            @if ($user->artist_status)
                <a href="/message/{{ auth()->user()->id }}">
                    <span class="material-symbols-outlined me-4 fs-2">chat</span>
                </a>
                <a href="/create-post">
                    <span class="material-symbols-outlined me-4 fs-2">add</span>
                </a>
            @endif

            <a href="/logout">
                <span class="material-symbols-outlined me-4 fs-2">
                    logout
                </span>
            </a>
            @if (auth()->user()->type == 'admin')
                <a href="/admin">
                    <span class="material-symbols-outlined fs-2">
                        admin_panel_settings
                    </span>
                </a>
            @endif
        </div>
    </header>
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
        <div class="row title">
            <h1 class="row-title">Search results for: '{{ $searchTerm }}'</h1>





        </div>
        <div class="row main">
            <div class="col-md-3 visitors ">
                <div class="row visitors-title ">
                    <h3>Visitors :</h3>
                </div>
                <div class="row">
                    @foreach ($visitors as $visitor)
                        @php
                            $limitedbio =
                                strlen($visitor->bio) > 20 ? substr($visitor->bio, 0, 40) . '...' : $visitor->bio;
                        @endphp
                        <a href="/profile/{{ $visitor->username }}" style="color: black">
                            <div class="visitor acc-item">
                                <img src="{{ $visitor->avatar }}" alt="Profile Picture" class="pfp">
                                <span class="username">{{ $visitor->username }}</span>
                                <br>
                                <span class="additional-text">{{ $limitedbio }}
                                </span>
                            </div>

                        </a>
                    @endforeach
                </div>
            </div>

            <div class="col-md-3 artists ">
                <div class="row artists-title ">
                    <h3>Artists :</h3>
                </div>
                <div class="row">
                    @foreach ($artists as $artist)
                        @php
                            $limitedbio =
                                strlen($artist->bio) > 20 ? substr($artist->bio, 0, 40) . '...' : $artist->bio;
                        @endphp
                        <a href="/profile/{{ $artist->username }}" style="color: black">
                            <div class="visitor acc-item">
                                <img src="{{ $artist->avatar }}" alt="Profile Picture" class="pfp">
                                <span class="username">{{ $artist->username }}</span>
                                <br>
                                <span class="additional-text">{{ $limitedbio }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>



            <div class="col-md-3 posts ">

                <div class="row posts-title ">
                    <h3>Posts :</h3>
                </div>
                <div class="row">
                    @foreach ($posts as $post)
                        @if ($post->accounts()->first())
                            @php
                                $limitedcaption =
                                    strlen($post->caption) > 20
                                        ? substr($post->caption, 0, 40) . '...'
                                        : $post->caption;
                            @endphp

                            <a href="/post/{{ $post->accounts()->first()->username }}/{{ $post->id }}"
                                style="color: black">
                                <div class="post acc-item">
                                    <img src="{{ $post->accounts()->first()->avatar }}" alt="Profile Picture"
                                        class="pfp">
                                    <span class="username">{{ $post->accounts()->first()->username }}</span>
                                    <br>
                                    <span class="additional-text">{{ $limitedcaption }}</span>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <footer class="container-fluid bg-d1c78d text-dark" id="footer">
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6 d-flex justify-content-center">
                <a href="#" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('/images/CA.png') }}" alt="Collart Logo" width="70" height="70"
                        class="me-2"> <!-- Logo -->
                    <h3 class="fs-8 mb-0"
                        style="text-decoration: none; color: black; font-family: serif; font-weight: bold;">
                        Collart
                    </h3>
                    <!-- Site name -->
                </a>
            </div>
            <div class="col-md-6">
                <div class="text-right">
                    <h5>About Collart</h5> <!-- Title -->
                    <p class="mb-0" style="font-size: medium;">Collart is a platform designed to
                        give
                        artists a
                        safe
                        space to share their art and enhance creativity and collaborations.</p>
                    <!-- Description -->
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6 text-center">
                <p>Contact us at <a href="mailto:admin@collart.com" style="color:black;">admin@collart.com</a>
                    for
                    any
                    inquiries.</p> <!-- Contact email -->
            </div>
            <div class="col-md-6">
                <div class="row justify-content-center py-3">
                    <div class="col-md-6 text-center">
                        <p class="mb-0">&copy; 2024 Collart</p> <!-- Copyright notice -->
                    </div>
                </div>
            </div>
        </div>

    </footer>



</body>

</html>
