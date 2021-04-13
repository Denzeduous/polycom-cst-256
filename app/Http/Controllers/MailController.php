<?php

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
