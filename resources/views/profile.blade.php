@extends('layouts.mainlayout', ['title' => $user->GetFirstName() . ' ' . $user->GetLastName() . ' - Polycom', 'post_button' => true])
<?php Illuminate\Support\Facades\Log::info ("Got here in the page!"); ?>
@push('styles')
	<link href="{{ asset('/resources/css/profile.css') }}" rel="stylesheet">
@endpush

@section('content')
	@if(!$user->IsBusiness())
		<h1 align="center">{{ $user->GetFirstName() }} {{ $user->GetLastName() }}</h1>
		<p class="profile-username"><span>@</span>{{ $user->GetUsername() }}, joined {{ $user->GetDateJoined()->format('F jS, Y') }}</p>
		<p class="profile-email"><a href="mailto:{{ $user->GetEmail() }}">{{ $user->GetEmail() }}</a></p>
	@else
		<h1 align="center">{{ $user->GetLastName() }}</h1>
		<p class="profile-username"><span>@</span>{{ $user->GetUsername() }}, joined {{ $user->GetDateJoined()->format('F jS, Y') }}</p>
		<p class="profile-email"><a href="mailto:{{ $user->GetEmail() }}">{{ $user->GetEmail() }}</a></p>
	@endif
	@if($is_self)
		<span id="profile-edit" class="btn btn-outline-success my-2 my-sm-0">Edit Profile</span>
		
		@push('scripts')
			<script src="{{ asset('/resources/js/profile/profile.js') }}"></script>
		@endpush
	@else
		@if($is_admin)
			<span id="profile-delete"  class="btn btn-outline-danger my-2 my-sm-0">Delete</span>
			<span id="profile-suspend" class="btn btn-outline-warning my-2 my-sm-0">Suspend</span>
			
			@push('scripts')
				<script src="{{ asset('/resources/js/profile/admin.js') }}"></script>
			@endpush
		@endif
	@endif
	
	<div class="container" style="width: 90%">
		<div class="row" style="width: 100%">
			
			<!-- Main Profile -->
			<form class="col-7" action="/editprofile" method="POST" id="profile-form">
				@csrf
			
				<div style="text-align: left; style=width: 100%">
					@if(!$user->IsBusiness())
						<!-- Bio -->
						<h3 align="left" style="border-bottom: 1px solid lightgrey">Bio</h3>
						@if($user->GetBio() !== '')
							<div class="profile-editable">{!! \App\Service\MarkdownParser::parse($user->GetBio()) !!}</div>
						@else
							<p class="profile-editable-empty empty">{{ $user->GetFirstName() }} hasn't created their bio yet.</p>
						@endif
						
						@if($is_self)
							<textarea name="bio" class="profile-input" style="height: 200px">{{ $user->GetBio() }}</textarea>
						@endif
					@else
						<!-- Bio -->
						<h3 align="left" style="border-bottom: 1px solid lightgrey">About Us</h3>
						@if($user->GetBio() !== '')
							<div class="profile-editable">{!! \App\Service\MarkdownParser::parse($user->GetBio()) !!}</div>
						@else
							<p class="profile-editable-empty empty">{{ $user->GetLastName() }} hasn't created their "About Us" yet.</p>
						@endif
						
						@if($is_self)
							<textarea name="bio" class="profile-input" style="height: 200px">{{ $user->GetBio() }}</textarea>
						@endif
					@endif
				</div>
				
				@if(!$user->IsBusiness())
					<div style="text-align: left">
						<!-- Skills -->
						<h3 align="left" style="border-bottom: 1px solid lightgrey">Skills</h3>
						@if($user->GetSkills() !== '')
							<div class="profile-editable">{!! \App\Service\MarkdownParser::parse($user->GetSkills()) !!}</div>
						@else
							<p class="profile-editable-empty empty">{{ $user->GetFirstName() }} hasn't listed their skills yet.</p>
						@endif
						
						@if($is_self)
							<textarea name="skills" class="profile-input" style="height: 200px">{{ $user->GetSkills() }}</textarea>
						@endif
					</div>
				@endif
				
				<div style="text-align: left; style=width: 100%">
					@if(!$user->IsBusiness())
						<!-- Contact Information -->
						<h3 align="left" style="border-bottom: 1px solid lightgrey">Contact</h3>
						@if($user->GetContact() !== '')
							<div class="profile-editable">{!! \App\Service\MarkdownParser::parse($user->GetContact()) !!}</div>
						@else
							<p class="profile-editable-empty empty">{{ $user->GetFirstName() }} hasn't listed their contact information yet.</p>
						@endif
						
						@if($is_self)
							<textarea name="contact" class="profile-input" style="height: 200px">{{ $user->GetContact() }}</textarea>
						@endif
					@else
						<!-- Contact Information -->
						<h3 align="left" style="border-bottom: 1px solid lightgrey">Where to Find Us</h3>
						@if($user->GetContact() !== '')
							<div class="profile-editable">{!! \App\Service\MarkdownParser::parse($user->GetContact()) !!}</div>
						@else
							<p class="profile-editable-empty empty">{{ $user->GetLastName() }} hasn't listed their social media and/or website yet.</p>
						@endif
						
						@if($is_self)
							<textarea name="contact" class="profile-input" style="height: 200px">{{ $user->GetContact() }}</textarea>
						@endif
					@endif
				</div>
				
				@if(!$user->IsBusiness())
					<div style="text-align: left; style=width: 100%">
						<!-- Education -->
						<h3 align="left" style="border-bottom: 1px solid lightgrey">Education</h3>
						@if($user->GetEducation() !== '')
							<div class="profile-editable">{!! \App\Service\MarkdownParser::parse($user->GetEducation()) !!}</div>
						@else
							<p class="profile-editable-empty empty">{{ $user->GetFirstName() }} hasn't listed their education yet.</p>
						@endif
						
						@if($is_self)
							<textarea name="education" class="profile-input" style="height: 200px">{{ $user->GetEducation() }}</textarea>
						@endif
					</div>
				@endif
			</form>
			
			<!-- Posts -->
			<div class="col-5">
				<h3>Groups</h3>
				<?php Illuminate\Support\Facades\Log::info ("Got here!"); ?>
				<?php Illuminate\Support\Facades\Log::info ("count: " . count($groups)); ?>
				@if(count($groups) > 0)
					@foreach($groups as $group)
						<?php Illuminate\Support\Facades\Log::info ("Got here in foreach!"); ?>
						<p style="text-align: center" width="100%">
							<a href="/group/{{ $group->GetName() }}">{{ $group->GetName() }}</a>
						</p>
					@endforeach
				@else
				<?php Illuminate\Support\Facades\Log::info ("Got here instead!"); ?>
					{{ $user->GetFirstName() }} is not a member of any groups.
				@endif
				<div>
					TODO
				</div>
			</div>
		</div>
		
		<div class="row justify-content-center" style="width: 100%; margin-bottom: 20px">
			<h1>Job Experience</h1>
		</div>

		@if($is_self)
			<div class="row" style="width: 100%">
				<form id="add-experience-form" action="/addexperience" method="POST" style="display: none; width: 60%; margin-left: 20%; transition: 0.25s ease-in-out">
					@csrf
					
					<div class="form-group">
						<label for="title">Job Title</label>
						<input type="text" name="title" id="title" class="form-control" placeholder="Job Title" required>
					</div>
	
					@if(!$user->IsBusiness())
						<div class="form-group">
							<label for="company">Company</label>
							<input type="text" name="company" id="company" class="form-control" placeholder="Company Name" required>
						</div>
					@else
						<div class="form-group">
							<label for="company">Location</label>
							<input type="text" name="company" id="company" class="form-control" placeholder="Location" required>
						</div>
					@endif
	
					@if(!$user->IsBusiness())
						<div class="form-row form-group-inline">
							<div class="form-group col-md-4">
								<label for="startdate">Start Date</label>
								<input type="date" name="startdate" id="startdate" class="form-control" placeholder="Start Date" required>
							</div>
		
							<div class="form-group col-md-4">
								<label for="enddate">End Date</label>
								<input type="date" name="enddate" id="enddate" class="form-control" placeholder="End Date">
							</div>
							
							<div class="form-group col-md-3">
								<label for="iscurrent">Current Position</label>
								<input type="checkbox" name="iscurrent" id="iscurrent" class="form-control">
							</div>
						</div>
					@endif
	
					<div class="form-group">
						<label for="responsibilities">Responsibilities</label>
						<textarea type="text" name="responsibilities" id="responsibilities" class="form-control" style="height: 200px" placeholder="Responsibilities"></textarea>
					</div>
	
					@if(!$user->IsBusiness())
						<div class="form-group">
							<label for="projects">Projects</label>
							<textarea type="text" name="projects" id="projects" class="form-control" style="height: 200px" placeholder="Projects"></textarea>
						</div>
					@else
						<div class="form-group">
							<label for="projects">Requirements</label>
							<textarea type="text" name="projects" id="projects" class="form-control" style="height: 200px" placeholder="Requirements"></textarea>
						</div>
					@endif
				</form>
				
				<div id="add-experience-form-error" class="row justify-content-center" style="width: 100%; margin-bottom: 20px; margin-top: -15px; display: none">
					<small style="color: red">"End Date" cannot be empty unless "Current Position" is checked.</small>
				</div>
				
				<div class="row justify-content-center" style="width: 100%; margin-bottom: 20px">
					<div class="btn btn-outline-success my-2 my-sm-0" id="add-experience" style="display: inline; margin-right: 20px">
						@if(!$user->IsBusiness())
							Add Experience
						@else
							Add Job Post
						@endif
					</div>
					<div class="btn btn-outline-danger my-2 my-sm-0" id="add-experience-cancel" style="display: none">Cancel</div>
				</div>
			@endif
		</div>
			
		<div style="width: 60%; margin-left: 0%">
			@foreach($user->GetExperience() as $job)
				@if($is_self)
				<form action="{{ $user->GetUsername() }}/editexperience/{{ strval($job->GetID()) }}" id="{{ strval($job->GetID()) . '-form' }}" method="POST" style="width: 100%; text-align: left">
				@csrf
				@else
				<div style="width: 100%; text-align: left">
				@endif
					@if($is_self)
						<div class="form-group">
							<span id="{{ strval($job->GetID()) . '-edit' }}" name="job-exp-edit" class="btn btn-success my-2 my-sm-0">Edit</span>
							<span id="{{ strval($job->GetID()) . '-delete' }}" name="job-exp-delete" class="btn btn-danger my-2 my-sm-0">Delete</span>
						</div>
					@endif
				
					<div style="display: inline">
						<h2 id="{{ strval($job->GetID()) . '-title' }}" style="width: 50%; margin-left: 25%; text-align: left">{{ $job->GetTitle() }}</h2>
						
						@if($is_self)
							<div id="{{ strval($job->GetID()) . '-title-edit' }}" style="display: none" class="form-group">
								<label for="title">Job Title</label>
								<input type="text" name="title" id="{{ strval($job->GetID()) . '-title' }}" class="form-control" placeholder="Job Title" value="{{ $job->GetTitle() }}" required>
							</div>
						@endif
						
						<h3 id="{{ strval($job->GetID()) . '-company' }}" style="width: 50%; margin-left: 25%; text-align: left">{{ $job->GetCompany() }}</h3>

						@if(!$user->IsBusiness() && $is_self)
							<div id="{{ strval($job->GetID()) . '-company-edit' }}" style="display: none" class="form-group">
								<label for="company">Company</label>
								<input type="text" name="company" id="{{ strval($job->GetID()) . '-company' }}" class="form-control" placeholder="Company" value="{{ $job->GetCompany() }}" required>
							</div>
						@endif
					</div>

					@if(!$user->IsBusiness())
						<p style="width: 50%; margin-left: 25%; text-align: left; margin-bottom: 20px;">{{ $job->GetStartDate()->format('F jS, Y') }} - {{ $job->IsCurrent() ? 'Now' : $job->GetEndDate()->format('F jS, Y') }}</p>
					@endif
					
					<div style="width: 50%; margin-left: 25%; text-align: left">
						<h4>Responsibilities</h4>
						
						<div id="{{ strval($job->GetID()) . '-responsibilities' }}">
							{!! \App\Service\MarkdownParser::parse($job->GetResponsibilities()) !!}
						</div>
						
						<div id="{{ strval($job->GetID()) . '-responsibilities-edit' }}" class="form-group" style="display: none">
							<label for="responsibilities">Responsibilities</label>
							<textarea name="responsibilities" id="{{ strval($job->GetID()) . '-responsibilities' }}" class="form-control" style="height: 200px" placeholder="Requirements" required>{{ $job->GetResponsibilities() }}</textarea>
						</div>
					</div>
	
					<div style="width: 50%; margin-left: 25%; text-align: left">
						<h4>
							@if(!$user->IsBusiness())
								Projects
							@else
								Requirements
							@endif
						</h4>

						<div id="{{ strval($job->GetID()) . '-projects' }}"
							{!! \App\Service\MarkdownParser::parse($job->GetProjects()) !!}
						</div>
						
						<div id="{{ strval($job->GetID()) . '-projects-edit' }}" class="form-group" style="display: none">
							<label for="responsibilities">
								@if(!$user->IsBusiness())
									Projects
								@else
									Requirements
								@endif
							</label>
							<textarea name="projects" id="{{ strval($job->GetID()) . '-projects' }}" class="form-control" style="height: 200px" placeholder="{{ !$user->IsBusiness() ? 'Projects' : 'Requirements' }}" required>{{ $job->GetProjects() }}</textarea>
						</div>
					</div>
					
					<hr style="margin-top: 10px; margin-bottom: 10px;" />
				@if($is_self)
				</form>
				@else
				</div>
				@endif
			@endforeach
		</div>
	</div>
	
	@if($is_admin)
		<div id="profile-popup-background">
			<div class="container h-100">
				<div class="row h-100 justify-content-center align-items-center">
					<form action="{{ $user->GetUsername() }}/delete" method="POST" id="profile-popup-form-delete" class="col-md-4 col-md-offset-4" style="display: none">
						@csrf
						
						<p style="text-align: center; color: white">Are you sure you wish to delete {{ $user->GetUsername() }}?</p>
						<p style="text-align: center; color: white">If so, please type in your password and press the "Delete" button. Else, please select "Cancel"</p>
						
						<div class="form-group">
							<input id="profile-delete-password" name="password" type="password" class="form-control" placeholder="Password" required>
							<label for="password" style="display: none">Password</label>
							<small id="password-help" class="form-text" style="color: white">Please type in your password to confirm deletion. You do not need to type your password to cancel.</small>
						</div>
						
						<span id="profile-delete-cancel" class="btn btn-success my-2 my-sm-0">Cancel</span>
						<span id="profile-delete-delete" class="btn btn-danger my-2 my-sm-0">Delete</span>
					</form>
					
					<form action="{{ $user->GetUsername() }}/suspend" method="POST" id="profile-popup-form-suspend" class="col-md-4 col-md-offset-4" style="display: none">
						@csrf
						
						<p style="text-align: center; color: white">Are you sure you wish to suspend {{ $user->GetUsername() }}?</p>
						<p style="text-align: center; color: white">If so, please type in your password and a suspension length, then press the "Suspend" button. Else, please select "Cancel"</p>
						
						<div class="form-group">
							<input id="profile-suspend-password" name="password" type="password" class="form-control" placeholder="Password" required>
							<label for="password" style="display: none">Password</label>
							<small id="password-help" class="form-text" style="color: white">Please type in your password to confirm suspension. You do not need to type your password to cancel.</small>
						</div>
						
						<div class="form-group">
							<label for="suspension" style="display: none">Suspension Length</label>
							
							<select id="profile-suspend-length" name="suspension" class="form-control" required>
								<option>1 Day</option>
								<option>1 Week</option>
								<option>1 Month</option>
							</select>
							
							<small id="suspension-help" class="form-text" style="color: white">Please select the suspension length. You do not need to choose a length to cancel.</small>
						</div>
						
						<span id="profile-suspend-cancel"  class="btn btn-success my-2 my-sm-0">Cancel</span>
						<span id="profile-suspend-suspend" class="btn btn-danger my-2 my-sm-0">Suspend</span>
					</form>
				</div>
			</div>
		</div>
	@endif
@endsection