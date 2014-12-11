<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>NoSprawl</title>
	<link rel="stylesheet" href="/css/main.css">

</head>
<body>
	@section('header')
		<header class="site-header clearfix">
			<div class="container outer">
				<h1>NoSprawl</h1>
				@if(Auth::check())
					<a href='/logout'>Log out</a>
				@else
					<a href='/login'>login</a> | <a href='/register'>register</a> 
				@endif
			</div>

		</header>
	@show


	<div class="container-fluid">
		@yield('content', 'You should add your content here')	
	</div> <!-- .container-fluid -->
	
	@section('footer')
	@show

	@section('scripts')
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	@show

</body>
</html>