<?php

// Polycom v0.1
// Mail Controller v0
// Will hold functionality for mail (WIP).

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Service\UserDAO;
use App\Service\EmailVerification;

class MailController extends Controller
{
	public function verify_email (int $id, Request $request) {
		$password = $request->input ('pwd');
		
		
	}
}
