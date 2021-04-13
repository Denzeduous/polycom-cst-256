@extends('layouts.mainlayout', ['title' => $group->GetName() . ' - Polycom', 'post_button' => true])

@section('content')
<h1>{{ $group->GetName() }}</h1>
<h6>Created by <a href="/profile/{{ $group->GetOwner()->GetUsername() }}">{{ $group->GetOwner()->GetUsername() }}</a></h6>

@if(Session::has('user'))
	@if($group->GetOwner()->GetUserID() === Session::get('user')->GetUserID())
		<a href="/group/delete/{{ $group->GetName() }}"><span id="group-delete"  class="btn btn-outline-danger my-2 my-sm-0">Delete Group</span></a>
	@elseif($is_member)
		<a href="/group/leave/{{ $group->GetName() }}"><span id="group-leave"  class="btn btn-outline-danger my-2 my-sm-0">Leave Group</span></a>
	@else
		<a href="/group/join/{{ $group->GetName() }}"><span id="group-join"  class="btn btn-outline-success my-2 my-sm-0">Join Group</span></a>
	@endif
@endif

<p></p>

<div class="container" style="width: 90%">
		<div class="col-12">
			<h3>Members</h3>
				<p><a style="text-decoration: none" href="/profile/{{ $group->GetOwner()->GetUsername() }}">{{ $group->GetOwner()->GetUsername() }}</a></p>
				<p></p>
			@foreach($members as $member)
				<p><a style="text-decoration: none" href="/profile/{{ $member->GetUser()->GetUsername() }}">{{ $member->GetUser()->GetUsername() }}</a></p>
			@endforeach
		</div>
	</div>
</div>
@endsection