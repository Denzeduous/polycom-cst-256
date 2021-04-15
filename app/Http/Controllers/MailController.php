<?php

// Polycom v0.1
// Mail Controller v0
// Will hold functionality for mail (WIP).

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Service\UserDAO;
use App\Service\EmailVerification;
use App\Service\MailService;

class MailController extends Controller
{
	public function VerifyEmail (int $id, Request $request) {
		$pwd = $request->input ('pwd');

		if (MailService::Verify($id, $pwd))
			return view('message', ['message_type' => 'Success!', 'message_content' => 'Your email is now verified!', 'previous_url' => '/']);

		else
			return view('message', ['message_type' => 'Error authenticating', 'message_content' => 'There was an error authenticating. Either your email is already authenticated, or the supplied password was not valid. To resend a new password, please go to your settings.', 'previous_url' => '/']);
	}
}
