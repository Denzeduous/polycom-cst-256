<?php

namespace App\Service;

use Illuminate\Support\Facades\Mail;

class MailService {
	public static function send_verification (int $id) {
		$user = UserDAO::FromID($id);
		
		// Define who email is going to.
		$name    = $user->GetFirstName () . ' ' . $user->GetLastName ();
		$email   = $user->GetEmail ();
		$subject = 'Polycom - Account Email Verification';
		
		// Define the sender.
		$from_name  = 'Alec Sanchez - Polycom';
		$from_email = 'alec.cst.256@gmail.com';
		
		// Define the array for the body of the email.
		$data = array (
				'name' => $name,
				'id'   => $id,
				'pwd'  => EmailVerification::GenerateVerificationPassword($id),
		);
		
		// Create Mail class and send email.
		Mail::send ('mail', $data, function ($message) use ($name, $email, $subject, $from_name, $from_email) {
			$message->to      ($email, $name)
			->subject ($subject)
			->from    ($from_email, $from_name);
		});
	}
}

