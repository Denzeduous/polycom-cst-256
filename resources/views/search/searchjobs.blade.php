@extends('layouts.mainlayout', ['title' => 'Seach Jobs - Polycom', 'post_button' => true])

@section('content')
<a href="/search/user?q={{ $query }}">Search Users</a> <a href="/search/group?q={{ $query }}">Search Groups</a><br />

<div style="width: 100%; text-align: center">
	@foreach($jobs as $job)
		<p><a href="/job/{{ $job->GetID() }}">
			{{ $job->GetTitle() }}
		</a></p>
	@endforeach
</div>
@endsection