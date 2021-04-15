<nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 20px; height: 50px">
  <a class="navbar-brand" href="/">Polycom</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/">Link</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0" action="/search/user" method="GET">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="q" id="q">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    @if(Session::has('user'))
    <ul class="navbar-nav ml-auto">
    	@if($post_button)
			<div class="header-button btn btn-outline-primary my-2 my-sm-0" style="border-radius: 100%" >
				<img src="{{ asset('resources/svg/post.svg') }}" />
			</div>
		@endif
    	<li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          	{{ Session::get('user')->GetUsername() }}
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
		          <a class="dropdown-item" href="/profile/{{ Session::get('user')->GetUsername() }}">Profile</a>
		          <a class="dropdown-item" href="/connections">Connections</a>
		          <div class="dropdown-divider"></div>
		          <a class="dropdown-item" href="/creategroup">Create Group</a>
		          <div class="dropdown-divider"></div>
		          <a class="dropdown-item" href="/settings">Settings</a>
		          @if(Session::get('user')->IsAdmin())
			          <div class="dropdown-divider"></div>
			          <a class="dropdown-item" href="/settings">Admin Portal</a>
		          @endif
	        </div>
		</li>
        <li class="nav-item">
          <a class="nav-link" href="/logout">Logout</a>
        </li>
    </ul>
    @else
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="/login">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/register">Register</a>
      </li>
    </ul>
    @endif
  </div>
</nav>