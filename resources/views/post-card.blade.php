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





    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

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


    <link rel="stylesheet" href="{{ asset('/bootstrap/css/post-card.css') }}">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/layout.css') }}">


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

    <div class="close-container">

        <button type="button" class="btn-close" id="closeButton" onclick="history.back()"></button>
    </div>
    <div class="container mt-2">

        <div class="row justify-content-center">

            <div class="col-md-6">
                <div class="postcard">
                    @if ($post->type == 'image')
                        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">

                            <div class="carousel-indicators">
                                @php
                                    $isFirst = true;
                                @endphp

                                @foreach ($post->media as $media)
                                    <button type="button" data-bs-target="#carouselExampleDark"
                                        data-bs-slide-to="{{ $loop->index }}" class="{{ $isFirst ? 'active' : '' }}"
                                        aria-current="true" aria-label="Slide {{ $loop->index + 1 }}"></button>
                                    @php
                                        $isFirst = false;
                                    @endphp
                                @endforeach
                            </div>

                            <div class="carousel-inner">


                                @foreach ($post->media as $media)
                                    <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                        <img src="{{ asset($media->addr) }}" class="d-block w-100" alt="PostCard image"
                                            id="{{ $loop->index }}">
                                    </div>
                                @endforeach
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"
                                data-bs-slide="prev" id="prevButton">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleDark" data-bs-slide="next" id="nextButton">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    @elseif ($post->type == 'audio')
                        <div class="media">

                            @if ($post->media[0]->type == 'audio')
                                <img src="{{ asset($post->media[1]->addr) }}" class="media"
                                    alt="music cover image">
                                <audio width="100%" controls>
                                    <source src="{{ asset($post->media[0]->addr) }}"
                                        type="audio/{{ pathinfo($post->media[0]->addr, PATHINFO_EXTENSION) }}">
                                    Your browser does not support the audio tag.
                                </audio>
                            @else
                                <img src="{{ asset($post->media[0]->addr) }}" class="media"
                                    alt="music cover image">
                                <audio width="100%" controls>
                                    <source src="{{ asset($post->media[1]->addr) }}"
                                        type="audio/{{ pathinfo($post->media[1]->addr, PATHINFO_EXTENSION) }}">
                                    Your browser does not support the audio tag.
                                </audio>
                            @endif

                        </div>
                    @else
                        @foreach ($post->media as $media)
                            @if ($media->type == 'video')
                                <video width="100%" height="100%" controls class="media">
                                    <source src="{{ asset($media->addr) }}"
                                        type="video/{{ pathinfo($media->addr, PATHINFO_EXTENSION) }}">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        @endforeach
                    @endif





                    <div class="informations " style="margin: 10px 0px">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="information-userinfo">

                                    <h4> <img src="{{ asset($account->avatar) }}" alt="User's Profile Picture"
                                            style="width: 40px; height: 40px; border-radius: 50%;"> &nbsp;&nbsp; <a
                                            class="mr-2"
                                            href="/profile/{{ $account->username }}">{{ $account->username }}</a>
                                    </h4>

                                    <p class="text-muted">Posted on {{ date('F j, Y', strtotime($post->created_at)) }}
                                    </p>

                                </div>
                            </div>
                            <div class="col-md-3" style="text-align: end;">
                                <div class="information-like_comment_share">
                                    <span>
                                        @php
                                            $post = App\Models\Post::find($post->id);
                                            $likes = $post->likes;
                                            $posters = $post->accounts;
                                            $user = App\Models\Account::find(auth()->user()->id);
                                            $hasLiked = $likes->contains($user);
                                            $hasPosted = $posters->contains($user);

                                        @endphp
                                        @if (!$hasLiked)
                                            <form action="/add-like" method="post" id="addlike">
                                                @csrf
                                                <input type="hidden" name="postId" value= "{{ $post->id }}">
                                                <i class="material-symbols-outlined"
                                                    onclick="document.getElementById('addlike').submit()">favorite</i></button>
                                            </form>
                                        @else
                                            <form action="/remove-like" method="post" id="removelike">
                                                @csrf
                                                <style>
                                                    .favorite {
                                                        font-variation-settings: "FILL" 1, "wght" 400, "GRAD" 0, "opsz" 24;
                                                        color: rgba(238, 15, 15, 0.922);
                                                    }
                                                </style>
                                                <input type="hidden" name="postId" value= "{{ $post->id }}">
                                                <i class="material-symbols-outlined favorite"
                                                    onclick="document.getElementById('removelike').submit()">favorite</i></button>
                                            </form>
                                        @endif

                                        <i class="material-symbols-outlined" id="copyLinkButton">send</i>
                                        <div id="copySuccess" class="visually-hidden">Link Copied!</div>
                                        <script>
                                            const copyLinkButton = document.getElementById('copyLinkButton');
                                            const linkToCopy = "https://www.example.com"; // Replace with your actual link

                                            copyLinkButton.addEventListener('click', () => {
                                                navigator.clipboard.writeText(linkToCopy)
                                                    .then(() => {
                                                        console.log('Link copied to clipboard!');
                                                    })
                                                    .catch(err => {
                                                        console.error('Failed to copy link:', err);
                                                    });
                                            });
                                        </script>
                                        @if ($hasPosted || auth()->user()->type == 'admin')
                                            <form action="/delete-post" method="post" id="deletePost">
                                                @csrf

                                                <input type="hidden" name="postId" value= "{{ $post->id }}">
                                                <i class="material-symbols-outlined "
                                                    onclick="document.getElementById('deletePost').submit()">delete</i></button>
                                            </form>
                                        @endif
                                        @if ($hasPosted && count($posters) > 1)
                                            <form action="/remove-collab" method="post" id="removeCollab">
                                                @csrf

                                                <input type="hidden" name="postId" value= "{{ $post->id }}">
                                                <i class="material-symbols-outlined "
                                                    onclick="document.getElementById('removeCollab').submit()">person_remove</i></button>
                                            </form>
                                        @endif

                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <p>{!! $post->caption !!}
                            </p>
                            @if (count($posters) > 1)
                                <hr>
                                <span>In collaboration with :</span>
                                <br>
                                @foreach ($posters as $collaber)
                                    @if ($collaber->username != $account->username)
                                        <span>- <a href="/profile/{{ $collaber->username }}"
                                                style="text-decoration: underline">{{ $collaber->username }}
                                            </a></span>
                                    @endif
                                @endforeach
                            @endif
                            <div class="mt-3">

                                @if ($post->likes()->count() == 1)
                                    <strong>
                                        {{ $post->likes()->count() }} like
                                    </strong>
                                @else
                                    <strong>
                                        {{ $post->likes()->count() }} likes
                                    </strong>
                                @endif
                                |
                                @if ($post->comments()->count() == 1)
                                    <strong>
                                        {{ $post->comments()->count() }} comment
                                    </strong>
                                @else
                                    <strong>
                                        {{ $post->comments()->count() }} comments
                                    </strong>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">



                <div class="comment-bottom p-2 px-4" style="border-radius: .25rem; background-color: #fff;">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4"
                        style="position: sticky; top: 0; background-color: #fff; margin: 5px;"><img
                            class="img-fluid img-responsive rounded-circle mr-2"
                            src="{{ asset(auth()->user()->avatar) }}"
                            style="width: 40px; height: 40px; border-radius: 50%; margin: 0px 10px">


                        <form action="/add-comment" method="post" class="full-width">
                            @csrf
                            <input type="text" class="form-control flex-grow-1 mr-3" placeholder="Add comment"
                                name="commentcontent">
                            <input type="hidden" name="postId" value="{{ $post->id }}">
                            <button type="submit" class="btn btn-outline-commment"
                                style="background-color: #d1c7bd">Comment</button>
                        </form>



                    </div>

                    <div class="comment-section py-0">
                        @foreach ($post->comments as $comment)
                            <div class="commented-section  mt-3" style="border-radius: 10px; display: flex;">
                                <div class="comment-content" style="width: 100%">
                                    <div class="d-flex flex-row align-items-center commented-user">
                                        @php
                                            $commenter = $comment->account()->get();
                                            $user = App\Models\Account::find(auth()->user()->id);

                                        @endphp
                                        <img src="{{ asset($commenter[0]->avatar) }}"
                                            style="width: 20px; height: 20px; border-radius: 50%;"
                                            alt="Use's profile picture">
                                        <h6 class="mr-2">{{ $commenter[0]->username }}</h6>

                                    </div>
                                    <div class="comment-text-sm"><span>
                                            <h5>{{ $comment->content }}</h5>
                                        </span></div>
                                </div>
                                @if (auth()->user()->type == 'admin' || $commenter->contains($user))
                                    <div class="form-group" style="font-size: 1rem">
                                        <form action="/delete-comment" method="POST">
                                            @csrf

                                            <button type="submit" class="btn btn-danger"
                                                style="padding: 0.25rem; margin: 0.25rem;" name="comment-id"
                                                value="{{ $comment->id }}">
                                                <span class="material-symbols-outlined">
                                                    delete
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </div>





    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('/bootstrap/js/post-card.js') }}"></script>

    <footer class="container-fluid bg-d1c78d text-dark" id="footer">
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6 d-flex justify-content-center">
                <a href="#" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('/images/CA.png') }}" alt="Collart Logo" width="70" height="70"
                        class="me-2"> <!-- Logo -->
                    <h3 class="fs-8 mb-0"
                        style="text-decoration: none; color: black; font-family: serif; font-weight: bold;">Collart
                    </h3>
                    <!-- Site name -->
                </a>
            </div>
            <div class="col-md-6">
                <div class="text-right">
                    <h5>About Collart</h5> <!-- Title -->
                    <p class="mb-0" style="font-size: medium;">Collart is a platform designed to give artists a safe
                        space to share their art and enhance creativity and collaborations.</p> <!-- Description -->
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6 text-center">
                <p>Contact us at <a href="mailto:admin@collart.com" style="color:black;">admin@collart.com</a> for any
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
