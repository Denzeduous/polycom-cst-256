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

<div class="container" style="width: 90%">
	<div class="row" style="width: 100%">
		<div class="col-7">
			TODO
		</div>

		<div class="col-3">
			<h3>Members</h3>
				<p><a style="text-decoration: none" href="/profile/{{ $group->GetOwner()->GetUsername() }}">{{ $group->GetOwner()->GetUsername() }}</a></p>
				<p></p>
			@foreach($members as $member)
				<p><a style="text-decoration: none" href="/profile/{{ $member->GetUser()->GetUsername() }}">{{ $member->GetUser()->GetUsername() }}</a></p>
			@endforeach
			
			@if($extra_member_count != 0)
				<p color="bg-grey">... and <a href="/group/{{ $group->GetName() }}/members">{{ $extra_member_count }} more</a>.</p>
			@endif
		</div>
	</div>
</div>
@endsection