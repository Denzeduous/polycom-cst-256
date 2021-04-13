<?php

namespace App\Model;

use DateTime;

class GroupUser {
	private int $user_id;
	private DateTime $date_joined;
	private User $user;
	
	public function __construct (int $user_id, DateTime $date_joined, User $user) {
		$this->user_id     = $user_id;
		$this->date_joined = $date_joined;
		$this->user        = $user;
	}
	
	public function GetID (): int {
		return $this->user_id;
	}
	
	public function GetDateJoined (): DateTime {
		return $this->date_joined;
	}
	
	public function GetUser (): User {
		return $this->user;
	}
	
	public function SetUser (User $user) {
		$this->user = $user;
	}
}

