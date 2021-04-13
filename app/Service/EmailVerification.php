<?php

namespace App\Service;

use App\Service\Generic\DBConnector;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Log;

class EmailVerification {
	public static function GenerateVerificationPassword (int $id) {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			Log::error ("Connection failed: " . $conn->connect_error);
			return null;
		}
		
		try {
			$query = "DELETE FROM EmailVerificationStorage WHERE user_id=?";
			$stmt  = $conn->prepare($query);

			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close  ();

			$password = bin2hex(random_bytes(25));

			$query = "INSERT INTO EmailVerificationStorage (user_id, verification_password) VALUES (?, ?)";
			$stmt  = $conn->prepare($query);

			$stmt->bind_param('is', $id, $password);
			$stmt->execute();
			$stmt->close  ();

			DBConnector::CloseConnection($conn);

			return $password;
		}
		
		catch (Exception $e) {
			Log::error ($e->getMessage());
			return null;
		}
	}
	
	public static function VerifyEmail (int $id, string $password) {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			Log::error ("Connection failed: " . $conn->connect_error);
			return False;
		}
		
		try {
			$query = "SELECT COUNT(1), verification_password FROM User WHERE user_id=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $id);
			
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($verification_exists, $verification_password);
			$stmt->fetch();
			$stmt->close();

			DBConnector::CloseConnection($conn);

			return $verification_exists && $password === $verification_password;
		}
		
		catch (Exception $e) {
			Log::error ($e->getMessage());
			return False;
		}
	}
}

