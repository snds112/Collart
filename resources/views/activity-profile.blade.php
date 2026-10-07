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
    <link rel="stylesheet" href="index.css">
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



    <link rel="stylesheet" href="{{ asset('/bootstrap/css/activity-profile.css') }}">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/layout.css') }}">
    <script src="{{ asset('/bootstrap/js/activity-profile.js') }}"></script>
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
                <span class="material-symbols-outlined  me-4 fs-2">
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
    @if ($profile->type == 'visitor')
        <div class="container mt-5 ">
            <div class="row align-items-center border-bottom">
                <div class="col-md-12 border-bottom">
                    <div class="profile-info d-flex align-items-center border-bottom">
                        <img src="{{ asset($profile->avatar) }}" alt="Profile Picture" class="profile-picture mb-3">
                        <h2 class="user-name">{{ $profile->username }}</h2>

                    </div>
                    <br>
                    <h6 class="bio">{!! $profile->bio !!}</h6>
                </div>
            </div>
            <div class="follow-collab border-bottom">
                <span class="follow-collab">

                    @if ($isViewingOwnProfile && auth()->user()->type == 'visitor')
                        <button id="modifyprofilebtn">
                            <b><a href="/modify-profile">Modify Profile</a></b>
                        </button>
                    @endif
                    @if (auth()->user()->type == 'admin')
                        <div class="form-group" style="font-size: 1rem">
                            <form action="/delete-account" method="POST">
                                @csrf

                                <button type="submit" class="btn btn-danger" name="profile-id"
                                    value="{{ $profile->id }}">
                                    Delete Account
                                </button>
                            </form>
                        </div>
                    @endif


                </span>



            </div>

            <div class="row accordion-row">
                <div class="col-md-4">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#likesAccordion">
                        <i class="material-icons accordion-icon">favorite</i>Likes
                    </button>
                    <div class="accordion-content collapse" id="likesAccordion">

                        @foreach ($likedPosts as $post)
                            <a href="/post/{{ $post->accounts()->first()->username }}/{{ $post->id }}"
                                style="color: black">
                                <div class="post acc-item">
                                    <img src="{{ $post->accounts()->first()->avatar }}" alt="Profile Picture"
                                        class="pfp">
                                    <span>{{ $post->accounts()->first()->username }}</span>
                                    <p>{{ $post->caption }}</p>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#commentsAccordion">
                        <i class="material-icons accordion-icon">comment</i>Comments
                    </button>
                    <div class="accordion-content collapse" id="commentsAccordion">
                        @foreach ($comments as $comment)
                            @php
                                $postlink = $comment->post->accounts()->first()->username . '/' . $comment->post_id;
                            @endphp
                            <a href="/post/{{ $postlink }}" style="color: black">
                                <div class="comment acc-item">
                                    <img src="{{ $comment->account->avatar }}" alt="Profile Picture" class="pfp">
                                    <span>{{ $comment->account->username }}</span>
                                    <p>{{ $comment->post->caption }}</p>
                                    <hr>
                                    <p>{{ $comment->content }}</p>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#accountsAccordion">
                        <i class="material-icons accordion-icon">person</i>Followed Accounts
                    </button>
                    <div class="accordion-content collapse" id="accountsAccordion">
                        @foreach ($followedArtists as $artist)
                            <a href="/profile/{{ $artist->username }}" style="color: black">
                                <div class="follow acc-item">
                                    <img src="{{ $artist->avatar }}" alt="Profile Picture" class="pfp">
                                    <span>{{ $artist->username }}</span>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container mt-5 " style=" overflow-y: auto;">
            <div class="row align-items-center ">
                <div class="col-md-12 border-bottom">
                    <div class="profile-info d-flex align-items-center border-bottom">
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="Profile Picture"
                            class="profile-picture mb-3">
                        <h2 class="user-name">{{ auth()->user()->username }}</h2>

                    </div>
                    <br>
                    <h6 class="bio">{!! $profile->bio !!}</h6>
                </div>
            </div>


            <div class="row accordion-row">
                <div class="col-md-3">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#likesAccordion">
                        <i class="material-icons accordion-icon">favorite</i>Likes
                    </button>
                    <div class="accordion-content collapse" id="likesAccordion">
                        @foreach ($likedPosts as $post)
                            <a href="/post/{{ $post->accounts()->first()->username }}/{{ $post->id }}"
                                style="color: black">
                                <div class="post acc-item">
                                    <img src="{{ $post->accounts()->first()->avatar }}" alt="Profile Picture"
                                        class="pfp">
                                    <span>{{ $post->accounts()->first()->username }}</span>
                                    <p>{{ $post->caption }}</p>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#commentsAccordion">
                        <i class="material-icons accordion-icon">comment</i>Comments
                    </button>
                    <div class="accordion-content collapse" id="commentsAccordion">
                        @foreach ($comments as $comment)
                            @php
                                $postlink = $comment->post->accounts()->first()->username . '/' . $comment->post_id;
                            @endphp
                            <a href="/post/{{ $postlink }}" style="color: black">
                                <div class="comment acc-item">
                                    <img src="{{ $comment->account->avatar }}" alt="Profile Picture" class="pfp">
                                    <span>{{ $comment->account->username }}</span>
                                    <p>{{ $comment->post->caption }}</p>
                                    <hr>
                                    <p>{{ $comment->content }}</p>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#accountsAccordion">
                        <i class="material-icons accordion-icon">person</i>Followed
                    </button>
                    <div class="accordion-content collapse" id="accountsAccordion">
                        @foreach ($followeds as $followed)
                            <a href="/profile/{{ $followed->username }}" style="color: black">
                                <div class="follow acc-item">
                                    <img src="{{ $followed->avatar }}" alt="Profile Picture" class="pfp">
                                    <span>{{ $followed->username }}</span>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-light btn-block mb-3 accordion" type="button" data-toggle="collapse"
                        data-target="#accountsfollowingAccordion">
                        <i class="material-icons accordion-icon">person</i>Followers
                    </button>
                    <div class="accordion-content collapse" id="accountsfollowingAccordion">
                        @foreach ($followers as $follower)
                            <a href="/profile/{{ $follower->username }}" style="color: black">
                                <div class="follow acc-item">
                                    @if ($follower->artist_status)
                                        <span class="material-symbols-outlined">
                                            done
                                        </span>
                                    @endif
                                    <img src="{{ $follower->avatar }}" alt="Profile Picture" class="pfp">
                                    <span>{{ $follower->username }}</span>
                                </div>
                            </a>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    @endif

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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
