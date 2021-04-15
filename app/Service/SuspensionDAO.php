<?php

namespace App\Service;

use App\Service\Generic\DBConnector;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Log;
use DateTime;

class SuspensionDAO {

	/**
	 * Checks whether a user is suspended.
	 * @param int|string $user_info Either username or user ID.
	 * @return array An array of two values: A boolean named `is_suspended` (if the user is suspended) and a DateTime named `end_date` (when the suspension ends).
	 */
	public static function UserSuspended ($user_info) : array {
		if ($user_info instanceof string) return SuspensionDAO::UserSuspendedFromUsername ((string) $user_info);
		if ($user_info instanceof int)    return SuspensionDAO::UserSuspendedFromID       ((int)    $user_info);

		return array ('is_suspended' => false, 'end_date' => new DateTime ());
	}
	
	public static function UserSuspendedFromUsername (string $username) : array {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			Log::error ("Connection failed: " . $conn->connect_error);
			return array ('is_suspended' => false, 'end_date' => new DateTime ());
		}
		
		try {
			$query = "CALL GetUserSuspendedUsername(?, @is_suspended, @end_date, @user_id)";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('s', $username);
			
			$stmt->execute();
			
			$stmt->close();
			
			$query = 'SELECT @is_suspended, @end_date, @user_id';
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($suspended, $end_date, $user_id);
			$stmt->fetch();
			$stmt->close();
			
			Log::info('suspended: ' . $suspended);
			Log::info('end_date: ' . $end_date);
			Log::info('user_id: ' . $user_id);

			DBConnector::CloseConnection($conn);
			
			$end_date = DateTime::createFromFormat ('Y-m-d H:i:s', $end_date);
			
			return array ('is_suspended' => $suspended, 'end_date' => $end_date, 'user_id' => $user_id);
		}
		
		catch (Exception $e) {
			Log::error ($e->getMessage());
			return array ('is_suspended' => false, 'end_date' => new DateTime ());
		}
	}
	
	public static function UserSuspendedFromID (int $id) : array {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			Log::error ("Connection failed: " . $conn->connect_error);
			return array ('is_suspended' => false, 'end_date' => new DateTime ());
		}
		
		try {
			$query = "SELECT COUNT(1), end_date FROM Suspension user_id=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $id);
			
			$suspended = false;
			
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($suspended, $end_date);
			$stmt->fetch();
			$stmt->close();
			
			DBConnector::CloseConnection($conn);
			
			return array ('is_suspended' => $suspended, 'end_date' => $end_date);
		}
		
		catch (Exception $e) {
			Log::error ($e->getMessage());
			return array ('is_suspended' => false, 'end_date' => new DateTime ());
		}
	}
}

