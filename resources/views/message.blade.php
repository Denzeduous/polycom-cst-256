@extends('layouts.mainlayout', ['title' => $message_type . ' - Polycom', 'post_button' => false])

@section('content')
	<div style="width: 100%; height: 80%">
		<div class="container h-100">
			<div class="row h-100 justify-content-center align-items-center">
				<div>
					<h1>{{ $message_type }}</h1>
					<h3>{{ $message_content }}</h3>
					<p style="margin-top: 25px"><a href="{{ $previous_url }}"><span id="profile-delete"  class="btn btn-outline-success my-2 my-sm-0">Go Back</span></a></p>
				</div>
			</div>
		</div>
	</div>
@endsection