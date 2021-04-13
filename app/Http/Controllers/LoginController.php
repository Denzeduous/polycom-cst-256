<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Service\SuspensionDAO;
use App\Service\UserDAO;
use App\Model\User;

class LoginController extends Controller {
	public function LoginCheck (Request $request) {
		$this->ValidateLogin ($request)->validate ();

		$username = $request->input ('username');

		$user = UserDAO::FromUsername ($username);
		
		// Early exit, this means something went horribly wrong.
		if ($user === null) return view('message', ['message_type' => 'Internal Server Error (500)', 'message_content' => 'An unknown error occurred during validation. User was not found on the database.', 'previous_url' => url ()->previous ()]);

		// Put a header-formatted user object into the session.
		$request->session ()->put ('user', $user);
		
		return redirect ('/');
	}
	
	public function Logout (Request $request) {
		$request->session ()->remove ('user');
		
		return redirect ()->back ();
	}
	
	public function RegisterCheck (Request $request) {
		$this->ValidateRegister ($request)->validate ();
		
		$username    = $request->input ('username'  );
		$password    = $request->input ('password'  );
		$firstname   = $request->input ('firstname' );
		$lastname    = $request->input ('lastname'  );
		$email       = $request->input ('email'     );
		$is_business = $request->input ('isbusiness') === 'on';

		$firstname = $firstname !== null ? $firstname : '';
		
		$user = User::ForNewUser($firstname, $lastname, $username, $email, $is_business);
		
		$success = UserDAO::CreateUser ($user, $password);
		
		// Early exit, this means something went wrong.
		if (!$success) return view ('error');
		
		// Put a header-formatted user object into the session.
		$request->session ()->put ('user', $user);
		
		return redirect ('/');
	}
	
	public function ValidateLogin (Request $request) {
		$rules = [
				'username' => 'Required | Between: 6, 16 | Alpha',
				'password' => 'Required | Between: 6, 16',
		];
		
		$validator = Validator::make(
			$request->all (),
			$rules,
		);
		
		// This is super spaghetti, but we HAVE to have these values set during the next scope.
		// 
		// Without setting up something sufficiently more complicated, this would require
		// passing the values themselves, which Laravel does not allow in its callback.
		$validator->username = $request->input ('username');
		$validator->password = $request->input ('password');
		
		$validator->after(function ($validator) {
			if (!UserDAO::UserExists ($validator->username))
				$validator->errors ()->add ('username', 'User does not exist.');
				
			if (!UserDAO::VerifyUser ($validator->username, $validator->password))
				$validator->errors ()->add ('password', 'Username or password is incorrect.');
				
			$possible_suspension = SuspensionDAO::UserSuspendedFromUsername ($validator->username);
			
			if ($possible_suspension['is_suspended']) {
				$possible_suspension_date = $possible_suspension['end_date']->format ('F jS, Y');
				$validator->errors ()->add ('username', $validator->username . ' is suspended until ' . $possible_suspension_date . '.');
			}
		});

		return $validator;
	}
	
	public function ValidateRegister (Request $request) {
		$rules = [
				'username'  => 'Required | Between: 6, 16 | Alpha',
				'password'  => 'Required | Between: 6, 64',
				'lastname'  => 'Required',
				'email'     => 'Required',
		];
		
		$validator = Validator::make (
			$request->all (),
			$rules,
		);
		
		// This is super spaghetti, but we HAVE to have these values set during the next scope.
		//
		// Without setting up something sufficiently more complicated, this would require
		// passing the value itself, which Laravel does not allow in its callback.
		$validator->username    = $request->input ('username');
		$validator->first_name  = $request->input ('firstname');
		$validator->is_business = $request->input ('isbusiness') === 'on';
		
		$validator->after(function ($validator) {
			if (UserDAO::UserExists ($validator->username))
				$validator->errors ()->add ('username', 'User already exists.');
			
			if (!$validator->is_business && $validator->first_name === null)
				$validator->errors ()->add ('firstname', 'First Name is required.');
		});
		
		return $validator;
	}
}

