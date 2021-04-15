<?php

// Polycom v0.1
// Search Controller v0
// Will hold functionality for searching (WIP).

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UserDAO;

class SearchController extends Controller
{
	/**
	 * Searches for a user from given query.
	 * @param Request $request The request.
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory The view.
	 */
	public function SearchUsers (Request $request) {
		$query = $request->get ('q', '');
		
		$users = UserDAO::SearchUsers($query);
		
		return view('search.searchusers')->with('users', $users)->with('query', $query);
	}
	
	/**
	 * Searches for a group from given query.
	 */
	public function SearchGroups (Request $request) {
		$query = $request->get ('q', '');
		
		$groups = UserDAO::SearchGroups($query);
		
		return view('search.searchgroups')->with('groups', $groups)->with('query', $query);
	}
	
	/**
	 * Searches for a job with given query.
	 * @param Request $request The request.
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory The view.
	 */
	public function SearchJobs (Request $request) {
		$query = $request->get ('q', '');
		
		$jobs = UserDAO::SearchJobs($query);
		
		return view('search.searchjobs')->with('jobs', $jobs)->with('query', $query);
	}
}
