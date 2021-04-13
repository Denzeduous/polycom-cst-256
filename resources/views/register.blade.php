<!DOCTYPE html>
<html lang="en">
    <head>
    	<title>Register</title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<link rel = "stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="/resources/css/login.css" />
    </head>
    <body class="login-body d-flex align-items-center">
		<form action="registercheck" method="POST" role="form" class="container text-center" style="width: 50%; background-color: white; padding: 100px 50px 100px 50px">
			@csrf

			<div class="form-inline" style="width: 100%; margin-bottom: 25px">
	    		<div class="form-group" style="display: inline; width: 50%">
		    		<label for="username">Username</label>
		    		<input type="text" name="username" class="form-control" style="width: 95%; text-align: center" placeholder="Username" required />
		    		<span class="login-error">
		    			<?php echo $errors->first('username'); ?>
		    		</span>
		    	</div>
		    	
		    	<div class="form-group" style="display: inline; width: 50%">
		    		<label for="password">Password</label>
		    		<input type="password" id="password" class="form-control" style="width: 95%; text-align: center" placeholder="Password" name="password" required />
		    		
		    		<span class="login-error">
		    			<?php echo $errors->first('password'); ?>
		    		</span>
		    	</div>
			</div>

			<div class="form-inline" style="width: 100%; margin-bottom: 25px">
		    	<div class="form-group" style="display: inline; width: 50%" id="firstname-group">
		    		<label for="firstname">First Name</label>
		    		<input type="text" id="firstname" name="firstname" class="form-control" style="width: 95%; text-align: center" placeholder="First Name" required />
		    		
		    		<span class="login-error">
		    			<?php echo $errors->first('firstname'); ?>
		    		</span>
		    	</div>
		    	
		    	<div class="form-group" style="display: inline; width: 50%" id="lastname-group">
		    		<label for="lastname" id="lastname-label">Last Name</label>
		    		<input type="text" name="lastname" id="lastname" class="form-control" style="width: 95%; text-align: center" placeholder="Last Name" required />
	    		
		    		<span class="login-error">
		    			<?php echo $errors->first('lastname'); ?>
		    		</span>
		    	</div>
	    	</div>

	    	<div class="form-group" style="margin-bottom: 25px">
	    		<label for="email">Email</label>
	    		<input type="email" name="email" class="form-control" style="width: 98%; margin-left: 1%; text-align: center" placeholder="Email" required />
	    		
	    		<span class="login-error">
	    			<?php echo $errors->first('email'); ?>
	    		</span>
	    	</div>
	    	
	    	<div class="form-group" style="margin-bottom: 25px">
	    		<label for="isbusiness">Business</label>
	    		<input type="checkbox" name="isbusiness" id="isbusiness" class="form-control">
	    	</div>

	    	<div class="form-group">
	    		<input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Register" />
			</div>

	    	<div>
	    		<a href="login">Login</a>
    		</div>
		</form>
		
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
		
		<script src="{{ asset('/resources/js/register/register.js') }}"></script>
    </body>
</html>