<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Information</title>
    <link rel="icon" href="{{ asset('/images/logo.jpg') }}">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/bootstrap/css/artist-application-form.css') }}">

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
    <div class="container " style=" z-index: 20;">
        <div class="section haze-effect">

            <form action="/application-form" method="POST">
                @csrf
                <h2 class="text-center mb-4" style=" font-weight: 700 ;font-size: 40px; color: #ffeba7;  ">Artist
                    Information</h2>
                <div class="form-group">
                    <label for="full-name" style="font-weight: bold;">Full Name:</label>
                    <input type="text" class="form-control" placeholder="Full Name" id="fullname" name="fullname"
                        required>
                </div>

                <div class="form-group">
                    <label for="phone" style="font-weight: bold;">Phone Number:</label>
                    <input type="tel" class="form-control" placeholder="Phone" id="phone" name="phone"
                        required>
                </div>
                <div class="form-group">
                    <label for="portfolio" style="font-weight: bold;">Portfolio Link (Optional):</label>
                    <input type="url" class="form-control" placeholder="link to portfolio" id="portfolio"
                        name="portfolio">
                </div>
                <div class="form-group">
                    <label for="description" style="font-weight: bold;">Description of Art:</label>
                    <textarea class="form-control" placeholder=" description" id="Description" name="description" required></textarea>
                </div>
                <input type="hidden" name="userId" value="{{ $user->id }}">
                <div class="text-center">
                    <button type="submit" class="btn mt-4" id="submitButton">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
