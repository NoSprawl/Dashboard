<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>NoSprawl</title>
	<link rel="stylesheet" href="/css/uikit.min.css">
	<link rel="stylesheet" href="/css/uikit.almost-flat.min.css">
	<link rel="stylesheet" href="/css/card.css">
	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
	<link rel="stylesheet" type="text/css" href="/css/themes/tooltipster-light.css" />
	<link rel="stylesheet" type="text/css" href="/css/toggle.css" />
 	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/uikit.min.js"></script>
  <script type="text/javascript" src="/js/jquery.tooltipster.min.js"></script>
</head>
<body>
	<div id="whole-bird">
	@section('header')
		<header>
		<nav class="tm-navbar uk-navbar uk-navbar-attached">
			<div class="uk-container uk-container-center">
		  	<a class="uk-navbar-brand" href="/"><img width="79" class="uk-margin uk-margin-remove" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOC4xLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB2aWV3Qm94PSIwIDAgMTAwMCAxMDAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAxMDAwIDEwMDAiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGcgaWQ9IkxheWVyXzFfMV8iPg0KCTxjaXJjbGUgZmlsbD0iIzAwNzhFRiIgY3g9IjczMS40IiBjeT0iMjUyLjEiIHI9IjI1MiIvPg0KCTxnPg0KCQk8cGF0aCBmaWxsPSIjMzUzNTM1IiBkPSJNMTMzLjMsMGwyMTAuMywzMzguMWgxLjNWMGgxMDMuOHY1MDMuOUgzMzcuOUwxMjguNCwxNjYuN0gxMjd2MzM3LjRIMjMuMlYwSDEzMy4zeiIvPg0KCTwvZz4NCgk8ZyBlbmFibGUtYmFja2dyb3VuZD0ibmV3ICAgICI+DQoJCTxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik02NDkuNSwzMTUuOGMyLjMsMjMuNSwxMS4zLDQwLDI2LjgsNDkuNXMzNC4yLDE0LjEsNTUuNywxNC4xYzcuNSwwLDE2LjItMC41LDI1LjgtMS44DQoJCQljOS42LTEuMSwxOC42LTMuNCwyNy4xLTYuN2M4LjUtMy4zLDE1LjUtOCwyMC45LTE0LjVjNS40LTYuNCw4LTE0LjcsNy40LTI1Yy0wLjUtMTAuMy00LjItMTguOC0xMS4zLTI1LjNzLTE2LjItMTEuOC0yNy4xLTE1LjgNCgkJCWMtMTAuOS00LjEtMjMuNy03LjQtMzcuNy0xMC4zYy0xNC4xLTIuOC0yOC40LTUuOS00My05LjJjLTE1LTMuMy0yOS42LTcuMi00My41LTExLjljLTEzLjktNC43LTI2LjMtMTAuOS0zNy40LTE5LjENCgkJCWMtMTAuOS04LTE5LjktMTguMy0yNi41LTMwLjdjLTYuNS0xMi40LTkuOC0yNy44LTkuOC00Ni4yYzAtMTkuOCw0LjktMzYuMywxNC41LTQ5LjdjOS42LTEzLjQsMjEuOS0yNC4yLDM2LjgtMzIuNA0KCQkJYzE0LjktOC4yLDMxLjQtMTQuMSw0OS41LTE3LjNjMTguMS0zLjMsMzUuMy00LjksNTItNC45YzE4LjgsMCwzNi44LDIsNTQuMSw2YzE3LjIsNC4xLDMyLjcsMTAuNSw0Ni42LDE5LjMNCgkJCWMxMy45LDksMjUuMywyMC42LDM0LjUsMzVjOS4yLDE0LjQsMTQuOSwzMS41LDE3LjMsNTJoLTg0Yy0zLjgtMTkuMy0xMi42LTMyLjItMjYuNS0zOC45Yy0xMy45LTYuNy0zMC4xLTEwLTQ3LjktMTANCgkJCWMtNS43LDAtMTIuNCwwLjUtMjAuMSwxLjVjLTcuOCwxLTE1LDIuNi0yMS45LDUuMmMtNi45LDIuNi0xMi42LDYuNC0xNy4zLDExLjNjLTQuNyw0LjktNywxMS40LTcsMTkuM2MwLDEwLDMuNCwxNy44LDEwLjMsMjQNCgkJCWM2LjksNi4yLDE1LjcsMTEuMSwyNywxNS4yYzEwLjksNC4xLDIzLjcsNy40LDM3LjcsMTAuM2MxNC4xLDIuOCwyOC42LDUuOSw0My42LDkuMmMxNC41LDMuNCwyOC45LDcuMiw0MywxMS45DQoJCQljMTQuMSw0LjcsMjYuOCwxMC45LDM3LjcsMTkuMWMxMC45LDgsMjAuMSwxOC4xLDI3LDMwLjRjNi45LDEyLjMsMTAuMywyNy4zLDEwLjMsNDUuMWMwLDIxLjYtNC45LDQwLTE0LjcsNTQuOQ0KCQkJYy0xMCwxNS0yMi43LDI3LjMtMzguNCwzNi44Yy0xNS43LDkuNS0zMy4zLDE2LjMtNTIuNiwyMC40Yy0xOS4zLDQuMi0zOC4yLDYuNC01Ny4yLDYuNGMtMjMsMC00NC4zLTIuNi02My45LTcuOA0KCQkJYy0xOS40LTUuMS0zNi40LTEzLjEtNTAuOC0yMy43Yy0xNC40LTEwLjYtMjUuNy0yMy45LTMzLjgtMzkuNWMtOC4yLTE1LjctMTIuNi0zNC41LTEzLjEtNTZMNjQ5LjUsMzE1LjhMNjQ5LjUsMzE1Ljh6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=" title="NoSprawl" alt="NoSprawl"></a>
	    	<ul class="uk-navbar-nav">
				@if(Auth::check())
	        <li class="@section('check_link')@show">{{link_to_route('check', 'Cloud Status')}}</li>
	        <!--<li class="@section('zones_link')@show">{{link_to_route('zones', 'Patch Management')}}</li>-->
	        <li class="@section('integrations_link')@show">{{link_to_route('integrations', 'Cloud Integrations')}}</li>
	        <li class="@section('alerts_link')@show">{{link_to_route('alerts', 'Alerts')}}</li>
					<li class="@section('users_link')@show">{{link_to_route('alerts', 'Users')}}</li>
					<li class="@section('users_link')@show">{{link_to_route('alerts', 'Settings')}}</li>
				@endif
	    	</ul>
				<div class="uk-navbar-flip">
        	<ul class="uk-navbar-nav">
						@if(Auth::check())
							<li class="plain">Hi, {{ucfirst(explode(' ', Auth::user()->full_name)[0])}}!</li>
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
	</div><!-- .container-fluid -->
	@section('footer')
	@show
	
	@section('scripts')
	@show
	</div>
</body>
</html>