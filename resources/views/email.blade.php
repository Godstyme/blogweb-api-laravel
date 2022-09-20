<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Message From web_blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>

    <div class="card" style="background: wheat; padding: 10px 8px;">
        <h1 class="card-title" style="color:green;">
            {{ $details['title'] }}
        </h1>
        <div class="card-header">
            <h3 style="color:blueviolet; font-weight:bolder;">Hello&nbsp;{{ $details['name'] }},</h3>
        </div>
        <div class="card-body">
            <p>
                Thank you for creating an account with us. Don't forget to complete your registration! <br>
                Please click on the link below or copy it into the address bar of your browser to confirm your email address:
            </p>
            <p class="card-text">
                {{ $details['subject'] }}
            </p>
            <a href="{{ url('user/verify', $verification_code)}}" class="btn btn-primary">Confirm my email address</a>
        </div>
        <div class="card-footer">
            <p><strong>Best Regards!!!</strong></p>
        </div>
      </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>
</html>
