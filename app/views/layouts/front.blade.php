<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>NoSprawl</title>
	<link rel="stylesheet" href="/css/uikit.min.css">
	<link rel="stylesheet" href="/css/uikit.almost-flat.min.css">
	<link rel="stylesheet" href="/css/card.css">
	<link rel="stylesheet" href="/css/main.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/uikit.min.js"></script>
</head>
<body>
	@section('header')
		<header>
		<nav class="tm-navbar uk-navbar uk-navbar-attached">
			<div class="uk-container uk-container-center">
		  	<a class="uk-navbar-brand" href="/"><img width="72" class="uk-margin uk-margin-remove" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOC4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB2aWV3Qm94PSIwIDI1MyA2MTIgMzQzIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMjUzIDYxMiAzNDMiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGcgaWQ9IkxheWVyXzFfMV8iPg0KCTxjaXJjbGUgZmlsbD0iIzAwNzhFRiIgY3g9IjQ0Ny42IiBjeT0iNDI3LjciIHI9IjE1NC4yIi8+DQoJPGcgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAgICAiPg0KCQk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNODEuNiwyNzMuNGwxMjguNywyMDYuOWgwLjhWMjczLjRoNjMuNXYzMDguNGgtNjcuOEw3OC42LDM3NS40aC0wLjl2MjA2LjVIMTQuMlYyNzMuNEg4MS42eiIvPg0KCTwvZz4NCgk8ZyBlbmFibGUtYmFja2dyb3VuZD0ibmV3ICAgICI+DQoJCTxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0zOTcuNSw0NjYuN2MxLjQsMTQuNCw2LjksMjQuNSwxNi40LDMwLjNjOS41LDUuOCwyMC45LDguNiwzNC4xLDguNmM0LjYsMCw5LjktMC4zLDE1LjgtMS4xDQoJCQljNS45LTAuNywxMS40LTIuMSwxNi42LTQuMWM1LjItMiw5LjUtNC45LDEyLjgtOC45YzMuMy0zLjksNC45LTksNC41LTE1LjNjLTAuMy02LjMtMi42LTExLjUtNi45LTE1LjVjLTQuMy00LTkuOS03LjItMTYuNi05LjcNCgkJCXMtMTQuNS00LjUtMjMuMS02LjNjLTguNi0xLjctMTcuNC0zLjYtMjYuMy01LjZjLTkuMi0yLTE4LjEtNC40LTI2LjYtNy4zYy04LjUtMi45LTE2LjEtNi43LTIyLjktMTEuNw0KCQkJYy02LjctNC45LTEyLjItMTEuMi0xNi4yLTE4LjhjLTQtNy42LTYtMTctNi0yOC4zYzAtMTIuMSwzLTIyLjIsOC45LTMwLjRjNS45LTguMiwxMy40LTE0LjgsMjIuNS0xOS44czE5LjItOC42LDMwLjMtMTAuNg0KCQkJYzExLjEtMiwyMS42LTMsMzEuOC0zYzExLjUsMCwyMi41LDEuMiwzMy4xLDMuN2MxMC41LDIuNSwyMCw2LjQsMjguNSwxMS44YzguNSw1LjUsMTUuNSwxMi42LDIxLjEsMjEuNA0KCQkJYzUuNiw4LjgsOS4xLDE5LjMsMTAuNiwzMS44aC01MS40Yy0yLjMtMTEuOC03LjctMTkuNy0xNi4yLTIzLjhTNDUzLjksMzQ4LDQ0MywzNDhjLTMuNSwwLTcuNiwwLjMtMTIuMywwLjkNCgkJCWMtNC44LDAuNi05LjIsMS42LTEzLjQsMy4ycy03LjcsMy45LTEwLjYsNi45cy00LjMsNy00LjMsMTEuOGMwLDYuMSwyLjEsMTAuOSw2LjMsMTQuN2M0LjIsMy44LDkuNiw2LjgsMTYuNSw5LjMNCgkJCWM2LjcsMi41LDE0LjUsNC41LDIzLjEsNi4zYzguNiwxLjcsMTcuNSwzLjYsMjYuNyw1LjZjOC45LDIuMSwxNy43LDQuNCwyNi4zLDcuM2M4LjYsMi45LDE2LjQsNi43LDIzLjEsMTEuNw0KCQkJYzYuNyw0LjksMTIuMywxMS4xLDE2LjUsMTguNmM0LjIsNy41LDYuMywxNi43LDYuMywyNy42YzAsMTMuMi0zLDI0LjUtOSwzMy42Yy02LjEsOS4yLTEzLjksMTYuNy0yMy41LDIyLjUNCgkJCWMtOS42LDUuOC0yMC40LDEwLTMyLjIsMTIuNWMtMTEuOCwyLjYtMjMuNCwzLjktMzUsMy45Yy0xNC4xLDAtMjcuMS0xLjYtMzkuMS00LjhjLTExLjktMy4xLTIyLjMtOC0zMS4xLTE0LjUNCgkJCWMtOC44LTYuNS0xNS43LTE0LjYtMjAuNy0yNC4yYy01LTkuNi03LjctMjEuMS04LTM0LjNMMzk3LjUsNDY2LjdMMzk3LjUsNDY2Ljd6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=" title="NoSprawl" alt="NoSprawl"></a>
	    	<ul class="uk-navbar-nav">
				@if(Auth::check())
	        <li class="@section('check_link')@show">{{link_to_route('check', 'Status')}}</li>
	        <li class="@section('zones_link')@show">{{link_to_route('zones', 'Patch Management')}}</li>
	        <li class="@section('integrations_link')@show">{{link_to_route('integrations', 'Integrations')}}</li>
	        <li class="@section('alerts_link')@show">{{link_to_route('alerts', 'Alerts')}}</li>
				@endif
	    	</ul>
				<div class="uk-navbar-flip">
        	<ul class="uk-navbar-nav">
						@if(Auth::check())
							<li class="plain">Welcome, {{ucfirst(explode(' ', Auth::user()->full_name)[0])}}!</li>
							<li>{{link_to_route('signout', 'Sign out')}}</li>
						@else
							<li class="@section('signin_link')@show">{{link_to_route('signin', 'Sign in')}}</li>
							<li class="@section('signup_link')@show">{{link_to_route('signup', 'Sign up')}}</li>
						@endif
        	</ul>
		  	</div>
			</div>
		</nav>
		</header>
	@show

	<div id="main" class="uk-container uk-container-center">
		@yield('content', 'You should add your content here')
	</div> <!-- .container-fluid -->
	
	@section('footer')
	@show

	@section('scripts')
	
	@show

</body>
</html>