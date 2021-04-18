@extends('layouts.mainlayout', ['title' => 'Seach Groups - Polycom', 'post_button' => true])

@section('content')
<a href="/search/user?q={{ $query }}">Search Users</a> <a href="/search/job?q={{ $query }}">Search Jobs</a><br />

<div style="width: 100%; text-align: center">
	@foreach($groups as $group)
		<p><a href="/group/{{ urlencode($group->GetName()) }}">
			{{ $group->GetName() }}
		</a></p>
	@endforeach
</div>
@endsection