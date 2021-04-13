<?php

namespace App\Model;

use DateTime;

class User {
	private int $user_id;

	private string $first_name;
	private string $last_name;
	private string $username;
	private string $email;
	
	private string $bio;
	private string $contact;
	private string $skills;
	private string $education;
	
	/**
	 * Array of JobExperience
	 */
	private $experience;

	private DateTime $date_joined;
	
	private bool $is_admin;
	private bool $is_business;
	
	public function __construct (int $user_id, string $first_name, string $last_name, string $username,
								 string $email, string $bio, string $contact, string $skills, string $education, $experience,
								 DateTime $date_joined, bool $is_admin, bool $is_business) {
		$this->user_id     = $user_id;
		$this->first_name  = $first_name;
		$this->last_name   = $last_name;
		$this->username    = $username;
		$this->email       = $email;
		$this->bio         = $bio;
		$this->contact     = $contact;
		$this->skills      = $skills;
		$this->education   = $education;
		$this->experience  = $experience;
		$this->date_joined = $date_joined;
		$this->is_admin    = $is_admin;
		$this->is_business = $is_business;
	}
	
	/**
	 * Creates a User model intended for new users registering.
	 * @param string $first_name User's first name.
	 * @param string $last_name User's last name.
	 * @param string $username User's username.
	 * @param string $email User's email.
	 * @param bool $is_business If the user is a business.
	 * @return \App\Model\User The constructed User model.
	 */
	public static function ForNewUser (string $first_name, string $last_name, string $username, string $email, bool $is_business) {
		return new User (0, $first_name, $last_name, $username, $email, "", "", "", "", null, new DateTime(), false, $is_business);
	}
	
	/**
	 * Returns a User model meant for viewing from a profile.
	 * @param int $user_id User's ID.
	 * @param string $first_name User's first name.
	 * @param string $last_name User's last name.
	 * @param string $username User's username.
	 * @param string $email User's email.
	 * @param string $bio User's bio.
	 * @param string $contact User's contact information.
	 * @param string $skills User's skills.
	 * @param DateTime $date_joined The date the user joined.
	 * @param bool $is_admin If the user is an admin.
	 * @param bool $is_business If the user is a business.
	 * @return \App\Model\User The constructed User model.
	 */
	public static function ForProfile (int $user_id, string $first_name, string $last_name, string $username, string $email, string $bio, string $contact, string $skills, string $education, $experience, DateTime $date_joined, bool $is_admin, bool $is_business) {
		return new User ($user_id, $first_name, $last_name, $username, $email, $bio, $contact, $skills, $education, $experience, $date_joined, $is_admin, $is_business);
	}
	
	/**
	 * Returns a User model meant for header viewing.
	 * @param int $user_id User's ID.
	 * @param string $first_name User's first name.
	 * @param string $last_name User's last name.
	 * @param string $username User's username.
	 * @param bool $is_admin If the user is an admin.
	 * @param bool $is_business If the user is a business.
	 * @return \App\Model\User The constructed User model.
	 */
	public static function ForHeader (int $user_id, string $first_name, string $last_name, string $username, bool $is_admin, bool $is_business) {
		return new User ($user_id, $first_name, $last_name, $username, "", "", "", "", "", null, new DateTime(), $is_admin, $is_business);
	}
	
	public static function ForListing (int $user_id, string $first_name, string $last_name, string $username, bool $is_admin, bool $is_business) {
		return new User ($user_id, $first_name, $last_name, $username, "", "", "", "", "", null, new DateTime(), $is_admin, $is_business);
	}
	
	/**
	 * @return int
	 */
	public function GetUserID () {

		return $this->user_id;
	}

	/**
	 * @return string
	 */
	public function GetFirstName () {

		return $this->first_name;
	}

	/**
	 * @return string
	 */
	public function GetLastName () {

		return $this->last_name;
	}

	/**
	 * @return string
	 */
	public function GetUsername () {

		return $this->username;
	}

	/**
	 * @return string
	 */
	public function GetEmail () {

		return $this->email;
	}

	/**
	 * @return string
	 */
	public function GetBio () {

		return $this->bio;
	}

	/**
	 * @return string
	 */
	public function GetContact () {

		return $this->contact;
	}

	/**
	 * @return string
	 */
	public function GetSkills () {

		return $this->skills;
	}
	
	/**
	 * @return string
	 */
	public function GetEducation () {
		
		return $this->education;
	}
	
	public function GetExperience () {
		return $this->experience;
	}

	/**
	 * @return \Faker\Provider\DateTime
	 */
	public function GetDateJoined () {

		return $this->date_joined;
	}

	/**
	 * @return bool
	 */
	public function IsAdmin () {

		return $this->is_admin;
	}

	/**
	 * @return bool
	 */
	public function IsBusiness () {

		return $this->is_business;
	}

	/**
	 * @param string $first_name
	 */
	public function SetFirstName ( $first_name ) {

		$this->first_name = $first_name;
	}

	/**
	 * @param string $last_name
	 */
	public function SetLastName ( $last_name ) {

		$this->last_name = $last_name;
	}

	/**
	 * @param string $username
	 */
	public function SetUsername ( $username ) {

		$this->username = $username;
	}

	/**
	 * @param string $email
	 */
	public function SetEmail ( $email ) {

		$this->email = $email;
	}

	/**
	 * @param string $bio
	 */
	public function SetBio ( $bio ) {

		$this->bio = $bio;
	}

	/**
	 * @param string $contact
	 */
	public function SetContact ( $contact ) {

		$this->contact = $contact;
	}

	/**
	 * @param string $skills
	 */
	public function SetSkills ( $skills ) {

		$this->skills = $skills;
	}
	
	/**
	 * @param string $skills
	 */
	public function SetEducation ( $education ) {
		
		$this->education = $education;
	}
	
	public function SetExperience ( $experience ) {
		$this->experience = $experience;
	}

	/**
	 * @param bool $is_admin
	 */
	public function SetAdmin ( $is_admin ) {

		$this->is_admin = $is_admin;
	}

	/**
	 * @param bool $is_business
	 */
	public function SetBusiness ( $is_business ) {

		$this->is_business = $is_business;
	}

}

