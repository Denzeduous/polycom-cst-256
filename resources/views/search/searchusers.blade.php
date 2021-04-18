@extends('layouts.mainlayout', ['title' => 'Seach Users - Polycom', 'post_button' => true])

@section('content')
<a href="/search/group?q={{ $query }}">Search Groups</a> <a href="/search/job?q={{ $query }}">Search Jobs</a><br />

<div style="width: 100%; text-align: center">
	@foreach($users as $user)
		<p><a href="/profile/{{ $user->GetUsername() }}">
			@if(!$user->IsBusiness())
				{{ $user->GetFirstName() }} {{ $user->GetLastName() }}
			@else
				{{ $user->GetLastName() }}
			@endif	
		</a></p>
	@endforeach
</div>
@endsection