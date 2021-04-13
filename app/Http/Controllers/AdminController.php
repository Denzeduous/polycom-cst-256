<?php
// Polycom v0.1
// Admin Controller v0.1
// Holds admin functionality and authorization.

namespace App\Http\Controllers;

use App\Service\UserDAO;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Service\Suspension;

class AdminController extends Controller {
	public function DeleteProfile (Request $request) {

		// Make sure user is logged in and is an admin.
		if (!Session::exists ('user') && Session::get ('user')->IsAdmin ()) return view('message', ['message_type' => 'Forbidden (403)', 'message_content' => 'You are not an administrator.', 'previous_url' => url ()->previous ()]);
		
		$username = $request->route ('username');

		// Verify to make sure the user is authenticated.
		if (!UserDAO::VerifyAdmin (Session::get ('user')->GetUsername (), $request->input ('password'))) return view('message', ['message_type' => 'Unauthorized (401)', 'message_content' => 'Administrative authentication was not successful.', url ()->previous ()]);
		
		UserDAO::DeleteFromUsername($username);
		
		return redirect ()->back ();
	}
	
	public function SuspendProfile (Request $request) {

		// Make sure user is logged in and is an admin.
		if (!Session::exists ('user') && Session::get ('user')->IsAdmin ()) returnview('message', ['message_type' => 'Forbidden (403)', 'message_content' => 'You are not an administrator.', 'previous_url' => url ()->previous ()]);

		$username = $request->route ('username');

		// Verify to make sure the user is authenticated.
		if (!UserDAO::VerifyAdmin (Session::get ('user')->GetUsername (), $request->input ('password'))) return view('message', ['message_type' => 'Forbidden (403)', 'message_content' => 'Administrative authentication was not successful.', 'previous_url' => url ()->previous ()]);

		// Verify that the suspension date doesn't generate Epoch.
		if (Suspension::CreateSuspensionDate($request->input ('suspension')) === 0) return view('message', ['message_type' => 'Bad Request (400)', 'message_content' => 'There was an error with the date field entered.', 'previous_url' => url ()->previous ()]);

		UserDAO::SuspendUser($username, Suspension::CreateSuspensionDate($request->input ('suspension')));

		return redirect ()->back ();
	}
}

