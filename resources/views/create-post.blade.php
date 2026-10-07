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


    <link rel="stylesheet" href="{{ asset('/bootstrap/css/createpost.css') }}">
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
    <div class="container mt-5">
        <div class="close-container">
            <button type="button" class="btn-close" id="closeButton" onclick="history.back()"></button>
        </div>
        <form action="/create-post" method="Post" id="post-form" enctype="multipart/form-data">
            @csrf
            <div class="row">


                <div class="col-md-6 bg-white rounded shadow p-4">
                    <h1>Create Post</h1>
                    <hr>
                    <div class="form-group">
                        <label for="postType">Post Type:</label>
                        <select class="form-control" id="postType" name="postType">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="postType">Art Type:</label>
                        <select class="form-control" id="artType" name="artType">
                            <option value="photography">Photography</option>
                            <option value="films">Films</option>
                            <option value="music">Music</option>
                            <option value="painting">Painting</option>
                            <option value="pottery">Pottery</option>
                            <option value="sculpture">Sculpture</option>
                            <option value="crafts">Crafts</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="caption">Caption:</label>
                        <textarea class="form-control" id="caption" name="caption" rows="5"></textarea>
                    </div>
                    <div class="form-group">

                        <label for="searchPeople">Search People (to collaborate):</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="collaborator-username"
                                name="collaborator-username" placeholder="Search for collaborators">
                            <button class="btn btn-outline-secondary" type="button" name="add-collaborator"
                                id="add-collaborator">Add</button>
                            <button class="btn btn-outline-secondary" type="button"
                                name="reset-collaborator"id="reset-collaborator">Reset</button>

                        </div>
                        <div id="collaborator-message"></div>
                        <ul id="selected-collaborators"></ul>
                        <input type="hidden" name="collaborators" id="collaborators" value="">
                        <button type="submit" class="btn btn-outline-commment float-end "id="store-post">Create
                            Post</button>
                    </div>

                </div>
                <div class="col-md-6 bg-light rounded shadow p-4">
                    <div class="media-upload" id="mediaUpload">
                        <i class="fas fa-plus-circle fa-7x"></i>
                        <div class="form-group " id="image-upload">
                            <label for="imageInput">Select Images:</label>
                            <input type="file" id="images" name="images[]" multiple>
                        </div>

                        <div class="form-group d-none" id="media-and-thumbnail">
                            <label for="mediaInput">Select Media:</label>
                            <input type="file" id="media" name="media">
                            <br>
                            <label for="thumbnail">Select Thumbnail:</label>
                            <input type="file" id="thumbnail" name="thumbnail">
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('/bootstrap/js/createpost.js') }}"></script>

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
