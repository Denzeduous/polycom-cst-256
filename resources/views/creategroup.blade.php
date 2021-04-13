@extends('layouts.mainlayout', ['title' => 'Create Group - Polycom', 'post_button' => true])

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-6">
			<form action="creategroup/create" method="POST" role="form">
				@csrf
				
				<div class="form-group" style="width: 80%; margin-left: 10%; margin-bottom: 25px">
		    		<label for="name">Username</label>
		    		<input type="text" name="name" class="form-control" style="text-align: center" placeholder="Group Name" required />
		    		<span class="name-error">
		    			<?php echo $errors->first('name'); ?>
		    		</span>
		    	</div>
		    	
		    	<div class="form-group">
		    		<input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Create" />
				</div>
			</form>
		</div>
	</div>
</div>
@endsection