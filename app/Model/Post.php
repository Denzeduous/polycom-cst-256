<?php

namespace App\Model;

use Faker\Provider\DateTime;

class Post {
	private int $post_id;
	private int $user_id;
	private string $content;
	private DateTime $date_posted;
	
	public function __construct (int $post_id, int $user_id, string $content, DateTime $date_posted) {
		$this->post_id = $post_id;
		$this->user_id = $user_id;
		$this->content = $content;

		$this->date_posted = $date_posted;
	}
	
	public function GetPostID () {
		return $this->post_id;
	}
	
	public function GetUserID () {
		return $this->user_id;
	}
	
	public function GetContent () {
		return $this->content;
	}
	
	public function SetContent (string $content) {
		$this->content = $content;
	}
	
	public function GetDatePosted () {
		return $date_posted;
	}
}

