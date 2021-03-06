<?php

// Polycom v0.1
// Profile Controller v0.1
// Holds functionality for viewing and editing profiles.

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UserDAO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Service\SuspensionDAO;
use DateTime;
use App\Model\JobExperience;

class ProfileController extends Controller {
	public function GetProfile (Request $request) {
		$username = $request->route ('username');
		
		if (!UserDAO::UserExists ($username)) return view('message', ['message_type' => '404', 'message_content' => 'User does not exist.', 'previous_url' => url ()->previous ()]);
		
		$possible_suspension = SuspensionDAO::UserSuspendedFromUsername ($username);
		
		if ($possible_suspension['is_suspended']) {
			$possible_suspension_date = $possible_suspension['end_date']->format('F jS, Y');
			return view('message', ['message_type' => 'Suspended (307)', 'message_content' => 'User is suspended until ' . $possible_suspension_date . '.', 'previous_url' => url ()->previous ()]);
		}
		
		$user     = UserDAO::FromUsernameForProfile ($username);
		$is_self  = false;
		$is_admin = false;
		
		if ($request->session ()->exists ('user')) {
			$is_self = $user->GetUsername() === $request->session ()->get ('user')->GetUsername ();
			$is_admin = $request->session ()->get ('user')->IsAdmin ();
		}
		
		$groups = UserDAO::GetGroupsForUser($user->GetUserID());

		return view ('profile')->with (['user' => $user, 'groups' => $groups, 'is_self' => $is_self, 'is_admin' => $is_admin]);
	}
	
	public function EditProfile (Request $request) {
		Log::info('Entering ' . __METHOD__);

		$user = session('user', null);
		
		if ($user === null) return view('message', ['message_type' => '403', 'message_content' => 'You must be logged in to edit a profile.', 'previous_url' => url ()->previous ()]);

		$bio       = $request->input ('bio'      );
		$contact   = $request->input ('contact'  );
		$skills    = $request->input ('skills'   );
		$education = $request->input ('education');
		
		$user->SetBio       ($bio       !== null ? $bio       : '');
		$user->SetContact   ($contact   !== null ? $contact   : '');
		$user->SetSkills    ($skills    !== null ? $skills    : '');
		$user->SetEducation ($education !== null ? $education : '');
		
		UserDAO::UpdateUserProfile($user);
		
		return redirect ()->back ();
	}
	
	public function AddExperience (Request $request) {
		
		// Int value for the DateTime epoch.
		$epoch = 1344988800;
		
		$title            = $request->input ('title');
		$start_date       = $request->input ('startdate');
		$end_date         = $request->input ('enddate');
		$is_current       = $request->input ('iscurrent');
		$responsibilities = $request->input ('responsibilities');
		$projects         = $request->input ('projects');

		$company = '';
		
		if ($request->session ()->get ('user')->IsBusiness ())
			$company = $request->session ()->get ('user')->GetLastName ();
		
		else
			$company = $request->input ('company');

		$start_date = ($start_date !== null) ? DateTime::createFromFormat('Y-m-d', $start_date) : new DateTime ("@$epoch"); // Magic number is epoch
		$end_date   = ($end_date   !== null) ? DateTime::createFromFormat('Y-m-d', $end_date)   : new DateTime ("@$epoch"); // Magic number is epoch

		$is_current = ($is_current === 'on');

		UserDAO::AddJobExperience(new JobExperience(0, $title, $company, $start_date, $end_date, $is_current, $responsibilities, $projects), session('user')->GetUserID());
		
		return redirect ()->back ();
	}
	
	public function EditExperience (Request $request, $username, $id) {
		
		// Int value for the DateTime epoch.
		$epoch = 1344988800;
		
		$id = intval ($id);
		
		$title            = $request->input ('title');
		$responsibilities = $request->input ('responsibilities');
		$projects         = $request->input ('projects');
		
		$company = '';
		
		if ($request->session ()->get ('user')->IsBusiness ())
			$company = $request->session ()->get ('user')->GetLastName ();

		else
			$company = $request->input ('company');
		
		UserDAO::EditJobExperience(new JobExperience ($id, $title, $company, new DateTime ("@$epoch"), new DateTime ("@$epoch"), false, $responsibilities, $projects));
		
		return redirect ()->back ();
	}
	
	public function DeleteExperience (Request $request, $username, $id) {
		$id = intval ($id);
		
		UserDAO::DeleteJobExperienceFromID($id);
		
		return redirect ()->back ();
	}
	
	public function GetJob (Request $request, int $id) {
		$id = intval ($id);
		
		$job = UserDAO::GetJobExperience($id);
		
		if ($job === null) return response(404);
		
		return view('job')->with('job', $job);
	}
}

