<!DOCTYPE html>
<html lang="en">
    <head>
    	<title>Login</title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel = "stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="/resources/css/login.css" />
    </head>
    <body class="login-body d-flex align-items-center">
		<div class="container text-center" style="width: 50%; background-color: white; padding: 100px 50px 100px 50px">
			<h1>Welcome to Polycom {{ $name }}!</h1>
			<p>There is just one more step left!</p>
			<p>Please click <a href="https://polycom-cst-256.herokuapp.com/email-verify/{{ $id }}?pwd={{ $pwd }}">here</a> to verify your email!</p>
			<p style="margin-top: 20px;">Received this email in error? Please click <a href="https://polycom-cst-256.herokuapp.com/email-remove/{{ $id }}?pwd={{ $pwd }}">here</a> to stop receiving emails from us.</p>
		</div>
		
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    </body>
</html>