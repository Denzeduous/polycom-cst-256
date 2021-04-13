<?php

namespace App\Model;

use DateTime;

class JobExperience {
	private int      $id;
	private string   $title;
	private string   $company;
	private DateTime $start_date;
	private DateTime $end_date;
	private bool     $is_current;
	private string   $responsibilities;
	private string   $projects;
	
	public function __construct (int $id, string $title, string $company, DateTime $start_date, DateTime $end_date, bool $is_current, string $responsibilities, string $projects) {
		$this->id               = $id;
		$this->title            = $title;
		$this->company          = $company;
		$this->start_date       = $start_date;
		$this->end_date         = $end_date;
		$this->is_current       = $is_current;
		$this->responsibilities = $responsibilities;
		$this->projects         = $projects;
	}
	
	public function GetID () {
		return $this->id;
	}
	
	/**
	 * @return mixed
	 */
	public function GetTitle () {

		return $this->title;
	}

	/**
	 * @return mixed
	 */
	public function GetCompany () {

		return $this->company;
	}

	/**
	 * @return mixed
	 */
	public function GetStartDate () {

		return $this->start_date;
	}

	/**
	 * @return mixed
	 */
	public function GetEndDate () {

		return $this->end_date;
	}

	/**
	 * @return mixed
	 */
	public function IsCurrent () {

		return $this->is_current;
	}

	/**
	 * @return mixed
	 */
	public function GetResponsibilities () {

		return $this->responsibilities;
	}

	/**
	 * @return mixed
	 */
	public function GetProjects () {

		return $this->projects;
	}
	
	public function setID ( $id ) {
		$this->id = $id;
	}

	/**
	 * @param mixed $title
	 */
	public function SetTitle ( $title ) {

		$this->title = $title;
	}

	/**
	 * @param mixed $company
	 */
	public function SetCompany ( $company ) {

		$this->company = $company;
	}

	/**
	 * @param mixed $start_date
	 */
	public function SetStartDate ( $start_date ) {

		$this->start_date = $start_date;
	}

	/**
	 * @param mixed $end_date
	 */
	public function SetEndDate ( $end_date ) {

		$this->end_date = $end_date;
	}

	/**
	 * @param mixed $is_current
	 */
	public function SetIsCurrent ( $is_current ) {

		$this->is_current = $is_current;
	}

	/**
	 * @param mixed $responsibilities
	 */
	public function SetResponsibilities ( $responsibilities ) {

		$this->responsibilities = $responsibilities;
	}

	/**
	 * @param mixed $projects
	 */
	public function SetProjects ( $projects ) {

		$this->projects = $projects;
	}

}

