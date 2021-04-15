<?php

namespace App\Service;

use App\Service\Generic\DBConnector;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService {
	
	/**
	 * Sends email to specified user.
	 * @param int $id The user's ID.
	 */
	public static function SendVerification (int $id) {
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
		Mail::send ('mail.verification', $data, function ($message) use ($name, $email, $subject, $from_name, $from_email) {
			$message->to      ($email, $name)
			->subject ($subject)
			->from    ($from_email, $from_name);
		});
	}
	
	/**
	 * Deletes the verification if authenticated.
	 * @param int $id The user's ID.
	 * @param string $pwd The user's password.
	 * @return bool Whether the authentication succeeded.
	 */
	public static function Verify (int $id, string $pwd): bool {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			Log::error ("Connection failed: " . $conn->connect_error);
			return False;
		}
		
		try {
			$query = "DELETE FROM emailverificationstorage WHERE user_id=? AND verification_password=?";
			$stmt = $conn->prepare($query);
			
			$stmt->bind_param('is', $id, $pwd);
			
			$stmt->execute();
			$stmt->store_result();
			$success = (bool) $stmt->affected_rows;
			$stmt->close();
			
			DBConnector::CloseConnection($conn);
			
			return $success;
		}
		
		catch (Exception $e) {
			Log::error ($e->getMessage());
			return False;
		}
	}
	
	/**
	 * Checks whether a user is authenticated.
	 * @param int $id The user's ID.
	 * @return bool Whether the user is authenticated.
	 */
	public static function IsAuthenticated (int $id): bool {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			Log::error ("Connection failed: " . $conn->connect_error);
			return False;
		}
		
		try {
			$query = "SELECT COUNT(user_id) FROM emailverificationstorage WHERE user_id=?";
			$stmt = $conn->prepare($query);
			
			$stmt->bind_param('i', $id);
			
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($success);
			$stmt->close();
			
			DBConnector::CloseConnection($conn);
			
			return (bool) $success;
		}
		
		catch (Exception $e) {
			Log::error ($e->getMessage());
			return False;
		}
	}
}

