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



    <link rel="stylesheet" href="{{ asset('/bootstrap/css/signup_login.css') }}">

    <script src="{{ asset('/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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

    <div class="section" style="height: 100vh">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <h6 class="mb-0 pb-3"><span>COLLART</span></h6>
                        <input class="custom-checkbox" type="checkbox" id="reg-log" name="reg-log" />
                        <label for="reg-log"></label>
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <form action="/login" method="POST">
                                                @csrf
                                                <h4 class="mb-4 pb-3">Log In</h4>
                                                <div class="form-group">
                                                    <input type="text" class="form-style"
                                                        placeholder="Username or Email" name="username_or_email">
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" class="form-style" placeholder="Password"
                                                        name="password">
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="remember_me" name="remember_me">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Remember&nbsp;me?
                                                        </label>
                                                    </div>

                                                </div>
                                                <button type="submit" class="btn mt-4">Login </button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-3 pb-3">Sign Up</h4>
                                            <form action="/create-user" method="POST" id="signupForm">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="text" class="form-style" placeholder="Username"
                                                        name="username">
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="email" class="form-style" placeholder="Email"
                                                        name="email">
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" class="form-style" placeholder="Password"
                                                        name="password">
                                                    <i class="input-icon uil uil-lock"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="flexCheckDefault" name="userType">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            Apply&nbsp;for&nbsp;artist&nbsp;account?
                                                        </label>
                                                    </div>

                                                </div>
                                                <button type="submit" class="btn mt-4" id="signupButton">Sign
                                                    up</button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--   <script>
        const artistCheckbox = document.getElementById('flexCheckDefault');
        const signupForm = document.getElementById('signupForm');

        signupForm.addEventListener('submit', function(event) {
            if (artistCheckbox.checked) {
                event.preventDefault();
                signupForm.action = "/profile/artist1";
                signupForm.submit();
                console.log(signupForm);
                console.log('form changed');
            }
        });
    </script> --}}
</body>

</html>
