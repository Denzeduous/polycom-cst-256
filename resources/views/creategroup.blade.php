@extends('layouts.mainlayout', ['title' => 'Create Group - Polycom', 'post_button' => true])

@section('content')
<form action="creategroup/create" method="POST" role="form" style="margin 0 auto">
	@csrf
	
	<div class="form-group" style="width: 80%; margin-left: 10%; margin-bottom: 25px">
   		<label for="name">Create a Group</label>
   		<input type="text" name="name" class="form-control" style="text-align: center" placeholder="Group Name" required />
   		<span class="name-error">
   			<?php echo $errors->first('name'); ?>
   		</span>
   	</div>
   	
   	<div class="form-group">
   		<input type="submit" class="btn btn-outline-success my-2 my-sm-0" value="Create" />
	</div>
</form>
@endsection