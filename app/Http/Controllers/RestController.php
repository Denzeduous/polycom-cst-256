<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UserDAO;

class RestController {
	public function UserFromUsername (Request $request, string $username) {
		$exists = UserDAO::UserExists($username);
		
		if (!$exists) return response()->json(null, 404);
				
		$user = UserDAO::FromUsernameForProfile($username);
		
		if ($user->IsBusiness())
			return response()->json([
					'id' => $user->GetUserID(),
					'company' => $user->GetLastName(),
					'username' => $user->GetUsername(),
					'email' => $user->GetEmail(),
					'aboutus' => $user->GetBio(),
					'contact' => $user->GetContact(),
					'is_business' => true,
			]);
			
		return response()->json([
				'id' => $user->GetUserID(),
				'first_name' => $user->GetFirstName(),
				'last_name' => $user->GetLastName(),
				'username' => $user->GetUsername(),
				'date_joined' => $user->GetDateJoined(),
				'email' => $user->GetEmail(),
				'bio' => $user->GetBio(),
				'skills' => $user->GetSkills(),
				'contact' => $user->GetContact(),
				'is_admin' => (bool) $user->IsAdmin(),
				'is_business' => false,
		]);
	}
	
	public function GetJobs (Request $request, string $query) {
		$found_jobs = UserDAO::SearchJobs($query);
		
		$clipped = count($found_jobs) === 20;
		
		$jobs = ['clipped' => $clipped, 'jobs' => array()];
		
		foreach ($found_jobs as $job) {
			$jobs['jobs'][] = [
					'id' => $job->GetID(),
					'title' => $job->GetTitle(),
					'company' => $job->GetCompany(),
					'responsibilities' => $job->GetResponsibilities(),
					'requirements' => $job->GetProjects(),
			];
		}
		
		return response()->json($jobs);
	}
	
	public function GetJob (Request $request, $id) {
		$id = intval ($id);
		
		$job = UserDAO::GetJobExperience($id);
		
		if ($job === null) return response()->json(null, 404);
		
		return response()->json([
				'id' => $job->GetID(),
				'title' => $job->GetTitle(),
				'company' => $job->GetCompany(),
				'responsibilities' => $job->GetResponsibilities(),
				'requirements' => $job->GetProjects(),
		]);
	}
}

