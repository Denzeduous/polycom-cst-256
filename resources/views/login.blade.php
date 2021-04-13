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
		<form action="logincheck" method="POST" role="form" class="container text-center" style="width: 50%; background-color: white; padding: 100px 50px 100px 50px">
			@csrf

    		<div class="form-group" style="width: 80%; margin-left: 10%; margin-bottom: 25px">
	    		<label for="username">Username</label>
	    		<input type="text" name="username" class="form-control" style="text-align: center" placeholder="Username" required />
	    		<span class="login-error">
	    			<?php echo $errors->first('username'); ?>
	    		</span>
	    	</div>
	    	
	    	<div class="form-group" style="width: 80%; margin-left: 10%; margin-bottom: 25px">
	    		<label for="password">Password</label>
	    		<input type="password" id="password" class="form-control" style="text-align: center" placeholder="Password" name="password" required />
	    		
	    		<span class="login-error">
	    			<?php echo $errors->first('password'); ?>
	    		</span>
	    	</div>

	    	<div class="form-group">
	    		<input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Login" />
			</div>

	    	<div>
	    		<a href="register">Register</a>
    		</div>
		</form>
		
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    </body>
</html>