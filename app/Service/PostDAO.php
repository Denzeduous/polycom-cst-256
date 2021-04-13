<?php

namespace App\Service;

use App\Service\Generic\DBConnector;
use Carbon\Exceptions\Exception;

use App\Model\Post;

class PostDAO {
	public static function CreatePost (int $user_id, string $content) {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			echo ("Connection failed: " . $conn->connect_error);
			return False;
		}
		
		try {
			$query = "INSERT INTO Post (username, password, email) VALUES (?, ?)";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('is', $user_id, $content);
			
			$success = $stmt->execute();
			
			DBConnector::CloseConnection($conn);
			
			return $success;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
			return False;
		}
	}
	
	public static function GetPost (int $post_id) {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			echo ("Connection failed: " . $conn->connect_error);
			return null;
		}
		
		try {
			$query = "SELECT * FROM Post WHERE post_id=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $post_id);
			
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($post_id, $user_id, $content, $date_posted);
			$stmt->fetch();
			
			$user = new Post ($post_id, $user_id, $content, $date_posted);
			
			DBConnector::CloseConnection($conn);
			
			return $user;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
			return null;
		}
	}
	
	public static function UpdatePost (int $post_id, string $content) {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			echo ("Connection failed: " . $conn->connect_error);
			return False;
		}
		
		try {
			$query = "UPDATE Post SET content=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('s', $content);
			
			$success = $stmt->execute();
			
			DBConnector::CloseConnection($conn);
			
			return $success;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
			return False;
		}
	}
	
	public static function DeletePost (int $post_id) {
		$conn = DBConnector::GetConnection();
		
		if ($conn->connect_error) {
			echo ("Connection failed: " . $conn->connect_error);
			return False;
		}
		
		try {
			$query = "DELETE FROM Post WHERE post_id=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('i', $post_id);
			
			$success = $stmt->execute();
			
			DBConnector::CloseConnection($conn);
			
			return $success;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
			return False;
		}
	}
}

