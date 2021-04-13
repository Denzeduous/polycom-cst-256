<?php

// Polycom v0.1
// Group Controller v0.1
// Holds functionality for viewing, editing, creating, and deleting groups.

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Service\UserDAO;

class GroupController extends Controller {
	public function GetGroup (Request $request, string $group_name) {
		$group_name = urldecode($group_name);
		
		$group = UserDAO::GetGroupByName($group_name);
		
		if ($group === null) return view('message', ['message_type' => '404', 'message_content' => 'Group "' . $group_name . '" does not exist.', 'previous_url' => url ()->previous ()]);
		
		$members = UserDAO::GetUsersForGroup($group->GetID());
		
		$member_slice = $members;
		$extra_member_length = 0;
		$is_member = false;
		
		if (count ($members) > 20) {
			$member_slice = array_slice ($members, 0, 20);
			
			$extra_member_length = count ($members) - 20;
		}
		
		Log::info($members);
		
		if (session ()->has ('user')) $is_member = UserDAO::IsMember (session ()->get ('user')->GetUserID (), $group->GetID ());
		
		return view('group', ['group' => $group, 'members' => $member_slice, 'extra_member_count' => $extra_member_length, 'is_member' => $is_member]);
	}

	public function CreateGroup (Request $request) {
		$this->ValidateGroupCreation ($request)->validate ();
		
		if (!session ()->exists ('user')) return view('message', ['message_type' => 'Internal Server Error (500)', 'message_content' => 'You must be logged in to create a group.', 'previous_url' => '/login']);

		$name = $request->get('name');

		UserDAO::CreateUserGroup(session ()->get ('user')->GetUserID(), $name);

		return redirect ('/group/' . urlencode($name));
	}

	public function DeleteGroup (Request $request, string $group_name) {
		$group_name = urldecode($group_name);

		if (!session()->has('user')) return view('message', ['message_type' => '403', 'message_content' => 'You must be logged in to delete a group.', 'previous_url' => '/login']);
		
		$group = UserDAO::GetGroupByName($group_name);
		
		if ($group === null) return view('message', ['message_type' => '404', 'message_content' => 'Group "' . $group_name . '" does not exist and cannot be deleted.', 'previous_url' => url ()->previous ()]);
		
		if ($group->GetOwner()->GetUserID() !== session()->get('user')->GetUserID()) return view('message', ['message_type' => '403', 'message_content' => 'You do not own this group.', 'previous_url' => url ()->previous ()]);
	
		UserDAO::DeleteGroup ($group->GetID());
		
		return redirect ('/');
	}

	public function JoinGroup (Request $request, string $group_name) {
		$group_name = urldecode($group_name);

		if (!session()->has('user')) return view('message', ['message_type' => '403', 'message_content' => 'You must be logged in to delete a group.', 'previous_url' => '/login']);
		
		$group = UserDAO::GetGroupByName($group_name);
		$user = session()->get('user');
		
		if ($group === null) return view('message', ['message_type' => '404', 'message_content' => 'Group "' . $group_name . '" does not exist and cannot be joined.', 'previous_url' => url ()->previous ()]);
		
		if (UserDAO::IsMember($user->GetUserID(), $group->GetID())) return view('message', ['message_type' => '404', 'message_content' => 'You are already a member of "' . $group_name . '".', 'previous_url' => url ()->previous ()]);

		UserDAO::AddUserToGroup($group->GetID(), $user->GetUserID());
		
		return redirect ('/group/' . urlencode($group_name));
	}

	public function LeaveGroup (Request $request, string $group_name) {
		$group_name = urldecode($group_name);

		if (!session()->has('user')) return view('message', ['message_type' => '403', 'message_content' => 'You must be logged in to delete a group.', 'previous_url' => '/login']);

		$group = UserDAO::GetGroupByName($group_name);
		$user = session()->get('user');

		if ($group === null) return view('message', ['message_type' => '404', 'message_content' => 'Group "' . $group_name . '" does not exist and cannot be joined.', 'previous_url' => url ()->previous ()]);

		if (!UserDAO::IsMember($user->GetUserID(), $group->GetID())) return view('message', ['message_type' => '404', 'message_content' => 'You are not a member of "' . $group_name . '".', 'previous_url' => url ()->previous ()]);

		UserDAO::RemoveUserFromGroup($group->GetID(), $user->GetUserID());

		return redirect ('/group/' . urlencode ($group_name));
	}

	public function GroupMembers (Request $request, string $group_name) {
		$group_name = urldecode($group_name);

		$group = UserDAO::GetGroupByName($group_name);

		if ($group === null) return view('message', ['message_type' => '404', 'message_content' => 'Group "' . $group_name . '" does not exist and cannot be joined.', 'previous_url' => url ()->previous ()]);

		$members = UserDAO::GetUsersForGroup($group->GetID());

		return view ('groupmembers', ['group' => $group, 'members' => $members]);
	}

	public function ValidateGroupCreation (Request $request) {
		$rules = [
				'name'  => 'Required | Between: 6, 64',
		];

		$validator = Validator::make (
				$request->all (),
				$rules,
		);

		// This is super spaghetti, but we HAVE to have these values set during the next scope.
		//
		// Without setting up something sufficiently more complicated, this would require
		// passing the value itself, which Laravel does not allow in its callback.
		$validator->name = $request->input ('name');
		
		$validator->after(function ($validator) {
			if (UserDAO::GetGroupByName($validator->name) !== null)
				$validator->errors ()->add ('name', 'Group already exists.');
		});

		return $validator;
	}
}
