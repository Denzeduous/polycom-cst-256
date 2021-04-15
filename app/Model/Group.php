<?php

// Polycom v0.1
// Group Model v0.1
// Is a group.

namespace App\Model;

class Group {
	private int $id;
	private string $name;
	private User $owner;
	private int $member_count;
	
	public function __construct (int $id, string $name, User $owner, int $member_count = 0) {
		$this->id    = $id;
		$this->name  = $name;
		$this->owner = $owner;
		$this->member_count = $member_count;
	}
	
	public function GetID (): int {
		return $this->id;
	}
	
	public function GetName (): string {
		return $this->name;
	}
	
	public function GetOwner (): User {
		return $this->owner;
	}
	
	public function SetName (string $name) {
		$this->name = $name;
	}
	
	public function SetOwner (User $owner) {
		$this->owner = $owner;
	}
}

