<!doctype html>
<html lang="en">

<head>
    <title>COLLART</title>
    <link rel="icon" href="{{ asset('/images/logo.jpg') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/layout.css') }}">

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



    <link rel="stylesheet" href="{{ asset('/bootstrap/css/artist_profile.css') }}">
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


    <div class="container">
        <div class="row border-bottom">

            <div class="profile border-bottom">

                <div class="profile-image">

                    <img src="{{ asset($profile->avatar) }}" alt="">

                    <p class="username"> {{ $profile->username }}</p>

                </div>


                <div class="profile-stats">

                    <div class="Posts">
                        <h5 class="stats-num">{{ $profile->posts->count() }}
                            <br>
                            Posts
                        </h5>

                    </div>
                    <div class="Followers">
                        <h5 class="stats-num">{{ $profile->followers->count() }}
                            <br>
                            Followers
                        </h5>

                    </div>
                    <div class="Following">
                        <h5 class="stats-num">{{ $profile->followed->count() }}
                            <br>
                            Following
                        </h5>

                    </div>




                </div>

            </div>
            <div class="row border-bottom">
                <h6 class="bio"> {!! $profile->bio !!} </h6>
            </div>
            <!-- End of profile section -->
            <div class="follow-collab">
                <span class="follow-collab">

                    @if (!$isViewingOwnProfile)
                        @if (auth()->user()->artist_status)
                            <form action="/message-user/{{ $profile->id }}" method="post">
                                @csrf
                                <button type="submit" id="followbtn">Message</button>
                            </form>
                        @endif
                        @if ($profileisFollowedByUser)
                            <form action="/unfollow/{{ $profile->id }}" method="post">
                                @csrf

                                <button type="submit" id="followbtn">Unfollow</button>
                            </form>
                        @else
                            <form action="/follow/{{ $profile->id }}" method="post">
                                @csrf

                                <button type="submit" id="followbtn">Follow</button>
                            </form>
                        @endif
                        @if ($profilefollowsUser)
                            <form action="/remove-follower/{{ $profile->id }}" method="post">
                                @csrf

                                <button type="submit" id="followbtn">Remove follower</button>
                            </form>
                        @endif
                    @else
                        <button id="followtn">
                            <b><a href="/create-post">Create Post</a></b>
                        </button>

                        <button id="modifyprofilebtn">
                            <b><a href="/modify-profile">Modify Profile</a></b>
                        </button>
                        <button id="acivitybtn">
                            <b><a href="/activity-profile">Activity</a></b>
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

        </div>
        <!-- End of container -->



        <div class="row">

            <div class="container">

                <div class="gallery-main">
                    @foreach ($posts as $post)
                        <div class="gallery-item fixed-size ">
                            @if ($post->media->count() > 0)
                                <img src="{{ asset($post->media->first()->addr) }}" class="gallery-image ">
                            @endif

                            <a href="/post/{{ $profile->username }}/{{ $post->id }}">
                                <div class="gallery-item-info ">

                                    <ul class="gallery-item-text">
                                        <li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i
                                                class="fas fa-heart" aria-hidden="true"></i>
                                            {{ $post->likes->count() }}
                                        </li>
                                        <li class="gallery-item-comments"><span
                                                class="visually-hidden">Comments:</span><i class="fas fa-comment"
                                                aria-hidden="true"></i>
                                            {{ $post->comments->count() }}
                                        </li>
                                    </ul>

                                </div>
                            </a>
                        </div>
                    @endforeach


                </div>


                <!-- End of gallery -->



            </div>
            <!-- End of container -->

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
