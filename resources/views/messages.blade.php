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
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/messages.css') }}">
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
    <div class="close-container">
        <button type="button" class="btn-close" id="closeButton" onclick="history.back()"></button>
    </div>

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
    <!-- partial:index.partial.html -->
    <div class="container main-container ">

        <div class="row no-gutters inside-container">
            <div class="col-md-4 border-right panel">
                <div class="settings-tray">


                </div>
                <div class="search-box">
                    <div class="input-wrapper">
                        <form action="/search-messages" method="get" id="searchMessages">
                            @csrf
                            <input placeholder="Search here" type="text" name="search"
                                style="width: 80%; padding: 0px 15px ">
                            <i class="material-icons" onclick="submitForm()" style="cursor: pointer">search</i>
                        </form>
                    </div>
                </div>
                @foreach ($conversations as $conversation)
                    @php

                        $sender = App\Models\Account::find($conversation->sender_id);
                        $receiver = App\Models\Account::find($conversation->receiver_id);
                        $messageheader = $sender->id === auth()->user()->id ? $receiver : $sender;

                        $messageContent = $conversation->messages->last()->content;
                        $limitedContent =
                            strlen($messageContent) > 20 ? substr($messageContent, 0, 20) . '...' : $messageContent;
                    @endphp
                    <a href="/message/{{ auth()->user()->id }}/{{ $messageheader->id }}" class="no-link-style"
                        style="text-decoration: none">
                        <div class="friend-drawer friend-drawer--onhover">
                            <img class="profile-image" src="{{ asset($messageheader->avatar) }}" alt="">
                            <div class="text">


                                <h6>{{ $messageheader->username }}</h6>
                                <p class="text-muted">{{ $limitedContent }}</p>
                            </div>
                            <span
                                class="time text-muted small">{{ $conversation->messages()->latest()->first()->created_at->format('M j Y g:i A') }}</span>
                        </div>
                    </a>
                    <hr>
                @endforeach


            </div>
            <div class="col-md-8 border-left panel">
                @if (isset($messages))
                    <div class="container">

                        <div class="row settings-tray">
                            <div class="friend-drawer no-gutters friend-drawer--grey">
                                <img class="profile-image" src="{{ asset($corresponder->avatar) }}" alt="">
                                <div class="my-2 text">
                                    <h4>{{ $corresponder->username }}</h4>

                                </div>
                                <span class="settings-tray--right">

                                    <form action="/delete-conversation" id="deleteconvo" method="post">
                                        @csrf
                                        <input type="hidden" name="userId" value="{{ $corresponder->id }}">
                                        <i class="material-icons"
                                            onclick="document.getElementById('deleteconvo').submit()">delete</i>
                                    </form>


                                </span>
                            </div>
                        </div>
                        <div class="row chat-panel">
                            <div class="messages" style="min-height: 80vh;">


                                @foreach ($messages as $message)
                                    @if ($message->sender_id === auth()->user()->id)
                                        <div class="row no-gutters">
                                            <div class="col-md-7 offset-md-5 sent">
                                                <div class="chat-bubble chat-bubble--right">
                                                    {{ $message->content }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row no-gutters">
                                            <div class="col-md-7">
                                                <div class="chat-bubble chat-bubble--left">
                                                    {{ $message->content }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach



                            </div>
                        </div>
                        <div class="row sendform">
                            <div class="col-12">

                                <form action="/send-message" method="POST" class="chat-box-tray" id="myForm">
                                    @csrf

                                    <input type="text" placeholder="Type your message here..."
                                        name="messagetosend">
                                    <input type="hidden" value="{{ $corresponder->id }}" name="corresponder">


                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('myForm').submit();"
                                        class="no-link-style"><i class="material-icons">send</i></a>


                                </form>

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <script src="{{ asset('/bootstrap/js/messages.js') }}"></script>
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
