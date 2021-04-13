<html lang="en">
	<head>
		<title>{{ $title }}</title>
		
		<meta charset="UTF-8" />
		<meta name = "viewport" content = "width = device-width, initial-scale=1">    
        <link rel = "stylesheet" href  = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        
        <link href="{{ asset('/resources/css/header.css') }}" rel="stylesheet">
        
        @stack('styles')
    </head>
	
	<body>
		<div align="center" style="display: flex; flex-direction: column; min-height: 100vh">
			<header>
				@include('layouts.header')
			</header>
		
			<main align="center" style="flex: 1">
				@yield('content')
			</main>
			
			@include('layouts.footer')
		</div>

		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> 
		
		@stack('scripts')
	</body>
</html>