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
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/home-page.css') }}">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/layout.css') }}">


    <script src="{{ asset('/bootstrap/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('/bootstrap/js/home-page.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    @php
        $pagenum = 0;
    @endphp
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
    <div class="container container-fluid w-100">




        <div class="container container-fluid main">
            <div class="row ">
                <div class="col-md-2 col-sm-3">
                    <form action="/filter-home-page" method="GET" class="category-form">
                        @php
                            $categories = [
                                'all',
                                'photography',
                                'video',
                                'music',
                                'painting',
                                'pottery',
                                'sculpture',
                                'crafts',
                            ];
                        @endphp
                        <ul class="category-list">

                            @foreach ($categories as $category)
                                <li>
                                    <button type="submit" name="category" value="{{ $category }}"
                                        style="font-family:
                                    serif;  font-size: 1rem;">
                                        {{ ucfirst($category) }} </button>
                                </li>
                            @endforeach
                        </ul>
                    </form>
                </div>
                <div class="col-md-10 col-sm-9 posts">
                    @if (!empty($posts))
                        <div class="row">



                            @php
                                $chunkSize = ceil(count($posts) / 4); // Round up to ensure at least 2 elements per chunk
                                $numChunks = min(count($posts), 4); // Limit to 3 chunks (avoid creating empty chunks)
                                $chunks = array_fill(0, $numChunks, []); // Initialize empty chunks

                                $i = 0;
                                foreach ($posts as $post) {
                                    $chunks[$i % $numChunks][] = $post; // Distribute elements round-robin
                                    $i++;
                                }

                            @endphp
                            @foreach ($chunks as $chunk)
                                @if (isset($chunk))
                                    <div class="col-lg-3 col-md-4 col-sm-6 post-column">
                                        <div class="row row-cols-1 g-2">
                                            @foreach ($chunk as $post)
                                                @if (isset($post))
                                                    <div class="col">

                                                        <a
                                                            href="/post/{{ $post->accounts[0]->username }}/{{ $post->id }}">
                                                            <div class="card">
                                                                @php

                                                                    foreach ($post->media as $media) {
                                                                        if ($media->type == 'image') {
                                                                            $image = $media;
                                                                            break;
                                                                        }
                                                                    }
                                                                    $limitedCaption =
                                                                        strlen($post->caption) > 40
                                                                            ? substr($post->caption, 0, 40) . '...'
                                                                            : $post->caption;
                                                                @endphp
                                                                <div class="image-container">
                                                                    <img class="card-img-top"
                                                                        src="{{ asset($image->addr) }}"
                                                                        alt="Card image cap">
                                                                    @if ($post->type == 'video' || $post->type == 'audio')
                                                                        <div class="card-img-overlay">
                                                                            <span
                                                                                class="material-symbols-outlined play-button"
                                                                                style="font-size: 3rem">
                                                                                play_arrow
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="information-userinfo">

                                                                        <span> <img
                                                                                src="{{ asset($post->accounts[0]->avatar) }}"
                                                                                alt="User's Profile Picture"
                                                                                style="width: 1.5rem; height: 1.5rem; border-radius: 50%;">
                                                                            <a class="mr-2 profile-link"
                                                                                href="/profile/{{ $post->accounts[0]->username }}">{{ $post->accounts[0]->username }}</a>
                                                                        </span>

                                                                        <p class="text-muted"
                                                                            style="font-size: 0.6rem;
                                                                        margin: 0.30rem;">
                                                                            {{ date('F j, Y', strtotime($post->created_at)) }}
                                                                        </p>

                                                                    </div>
                                                                    <div class="likebutton" style="margin-left: auto">
                                                                        @php
                                                                            $post = App\Models\Post::find($post->id);
                                                                            $likes = $post->likes;
                                                                            $user = App\Models\Account::find(
                                                                                auth()->user()->id,
                                                                            );
                                                                            $hasLiked = $likes->contains($user);
                                                                        @endphp
                                                                        @if (!$hasLiked)
                                                                            <form action="/add-like" method="post"
                                                                                id="addlike"
                                                                                data-action="/add-like">
                                                                                @csrf
                                                                                <input type="hidden" name="postId"
                                                                                    value="{{ $post->id }}">
                                                                                <i class="material-symbols-outlined favorite unliked"
                                                                                    onclick="event.preventDefault(); $(this).closest('form').submit();">favorite</i>
                                                                            </form>
                                                                        @else
                                                                            <form action="/remove-like" method="post"
                                                                                id="removelike"
                                                                                data-action="/remove-like">
                                                                                @csrf

                                                                                <input type="hidden" name="postId"
                                                                                    value="{{ $post->id }}">
                                                                                <i class="material-symbols-outlined favorite liked"
                                                                                    onclick="event.preventDefault(); $(this).closest('form').submit();"
                                                                                    style="...">favorite</i>
                                                                            </form>
                                                                        @endif

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="row flex" style="justify-content: center;text-align: center;height: 100%;">
                            <h1 class="title"
                                style="font-family: serif; font-weight: bold; font-size: 3vw;height: fit-content;padding: 5vw;">
                                Search
                                for your interests and follow artists to fill your feed!</h1>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>



    <div class="row  flex" id="pages">


        <form action="/switch-page" method="get" class="page-form">
            @csrf
            @if (!($page == 0))
                @php
                    if (isset($page)) {
                        $pagenum = $page;
                    }
                @endphp
                <input type="hidden" name="page" value="{{ $pagenum - 1 }}">
                <button type="submit" class="page-button">previous</button>
            @endif
            @if (count($posts) == 20)
                <input type="hidden" name="page" value="{{ $pagenum + 1 }}">
                <button type="submit" class="page-button">next</button>
            @endif


        </form>




        @if (count($posts) == 20)
            <form action="/switch-page" method="get" class="page-form">
                @csrf

                <input type="hidden" name="page" value="{{ -1 }}">
                <button type="submit" class="page-button">last page</button>
            </form>
        @endif
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
