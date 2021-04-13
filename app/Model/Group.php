<?php

namespace App\Model;

class Group {
	private int $id;
	private string $name;
	private User $owner;
	
	public function __construct (int $id, string $name, User $owner) {
		$this->id    = $id;
		$this->name  = $name;
		$this->owner = $owner;
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

