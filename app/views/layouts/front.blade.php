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
	<link rel="stylesheet" type="text/css" href="/css/components/datepicker.css"></style>
	<link rel="stylesheet" type="text/css" href="/css/components/datepicker.almost-flat.css"></style>
	<link rel="stylesheet" type="text/css" href="/css/c3.min.css" />
	<link rel="stylesheet" type="text/css" href="/js/datepicker/themes/classic.css" />
	<link rel="stylesheet" type="text/css" href="/js/datepicker/themes/classic.date.css" />
	<link rel="stylesheet" type="text/css" href="/js/datepicker/themes/classic.time.css" />
 	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/uikit.min.js"></script>
	<script type="text/javascript" src="/js/components/grid.min.js"></script>
  <script type="text/javascript" src="/js/jquery.tooltipster.min.js"></script>
	<script type="text/javascript" src="/js/nos.keyupload.js"></script>
	<script type="text/javascript" src="/js/c3.min.js"></script>
	<meta name="viewport" content="width=400, initial-scale=.8, user-scalable=yes">
</head>
<body>
	@if(Session::has('message'))
	<p class="alert alert-info">{{ Session::get('message') }}</p>
	@endif
	<ul id="groups_panel">
		<li class="add-new"><div class="divved"><span>Classification</span><i class="fa fa-plus"></i><input type="text" placeholder="Group Name"><i class="fa fa-check-circle"></i></div></li>
		<?php
		if(!is_null(Auth::user())) {
			$groups = Auth::user()->node_groups()->get();
			foreach($groups as $group) {
			?>
			<li rel="<?= $group->id; ?>"><div class="divved"><?php echo $group->name; ?></div></li>
			<?php
			}
			
		}
		
		?>
	</ul>
	<div id="whole-bird">
		<header>
			<nav class="tm-navbar uk-navbar uk-navbar-attached">
				<div class="uk-container uk-container-center">
			  	@if(Auth::check())<a class="uk-navbar-brand" href="/">@endif<img width="79" class="uk-margin uk-margin-remove" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOC4xLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB2aWV3Qm94PSIwIDAgMTAwMCAxMDAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAxMDAwIDEwMDAiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPGcgaWQ9IkxheWVyXzFfMV8iPg0KCTxjaXJjbGUgZmlsbD0iIzAwNzhFRiIgY3g9IjczMS40IiBjeT0iMjUyLjEiIHI9IjI1MiIvPg0KCTxnPg0KCQk8cGF0aCBmaWxsPSIjMzUzNTM1IiBkPSJNMTMzLjMsMGwyMTAuMywzMzguMWgxLjNWMGgxMDMuOHY1MDMuOUgzMzcuOUwxMjguNCwxNjYuN0gxMjd2MzM3LjRIMjMuMlYwSDEzMy4zeiIvPg0KCTwvZz4NCgk8ZyBlbmFibGUtYmFja2dyb3VuZD0ibmV3ICAgICI+DQoJCTxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik02NDkuNSwzMTUuOGMyLjMsMjMuNSwxMS4zLDQwLDI2LjgsNDkuNXMzNC4yLDE0LjEsNTUuNywxNC4xYzcuNSwwLDE2LjItMC41LDI1LjgtMS44DQoJCQljOS42LTEuMSwxOC42LTMuNCwyNy4xLTYuN2M4LjUtMy4zLDE1LjUtOCwyMC45LTE0LjVjNS40LTYuNCw4LTE0LjcsNy40LTI1Yy0wLjUtMTAuMy00LjItMTguOC0xMS4zLTI1LjNzLTE2LjItMTEuOC0yNy4xLTE1LjgNCgkJCWMtMTAuOS00LjEtMjMuNy03LjQtMzcuNy0xMC4zYy0xNC4xLTIuOC0yOC40LTUuOS00My05LjJjLTE1LTMuMy0yOS42LTcuMi00My41LTExLjljLTEzLjktNC43LTI2LjMtMTAuOS0zNy40LTE5LjENCgkJCWMtMTAuOS04LTE5LjktMTguMy0yNi41LTMwLjdjLTYuNS0xMi40LTkuOC0yNy44LTkuOC00Ni4yYzAtMTkuOCw0LjktMzYuMywxNC41LTQ5LjdjOS42LTEzLjQsMjEuOS0yNC4yLDM2LjgtMzIuNA0KCQkJYzE0LjktOC4yLDMxLjQtMTQuMSw0OS41LTE3LjNjMTguMS0zLjMsMzUuMy00LjksNTItNC45YzE4LjgsMCwzNi44LDIsNTQuMSw2YzE3LjIsNC4xLDMyLjcsMTAuNSw0Ni42LDE5LjMNCgkJCWMxMy45LDksMjUuMywyMC42LDM0LjUsMzVjOS4yLDE0LjQsMTQuOSwzMS41LDE3LjMsNTJoLTg0Yy0zLjgtMTkuMy0xMi42LTMyLjItMjYuNS0zOC45Yy0xMy45LTYuNy0zMC4xLTEwLTQ3LjktMTANCgkJCWMtNS43LDAtMTIuNCwwLjUtMjAuMSwxLjVjLTcuOCwxLTE1LDIuNi0yMS45LDUuMmMtNi45LDIuNi0xMi42LDYuNC0xNy4zLDExLjNjLTQuNyw0LjktNywxMS40LTcsMTkuM2MwLDEwLDMuNCwxNy44LDEwLjMsMjQNCgkJCWM2LjksNi4yLDE1LjcsMTEuMSwyNywxNS4yYzEwLjksNC4xLDIzLjcsNy40LDM3LjcsMTAuM2MxNC4xLDIuOCwyOC42LDUuOSw0My42LDkuMmMxNC41LDMuNCwyOC45LDcuMiw0MywxMS45DQoJCQljMTQuMSw0LjcsMjYuOCwxMC45LDM3LjcsMTkuMWMxMC45LDgsMjAuMSwxOC4xLDI3LDMwLjRjNi45LDEyLjMsMTAuMywyNy4zLDEwLjMsNDUuMWMwLDIxLjYtNC45LDQwLTE0LjcsNTQuOQ0KCQkJYy0xMCwxNS0yMi43LDI3LjMtMzguNCwzNi44Yy0xNS43LDkuNS0zMy4zLDE2LjMtNTIuNiwyMC40Yy0xOS4zLDQuMi0zOC4yLDYuNC01Ny4yLDYuNGMtMjMsMC00NC4zLTIuNi02My45LTcuOA0KCQkJYy0xOS40LTUuMS0zNi40LTEzLjEtNTAuOC0yMy43Yy0xNC40LTEwLjYtMjUuNy0yMy45LTMzLjgtMzkuNWMtOC4yLTE1LjctMTIuNi0zNC41LTEzLjEtNTZMNjQ5LjUsMzE1LjhMNjQ5LjUsMzE1Ljh6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=" title="NoSprawl" alt="NoSprawl"></a>
		    	<ul class="uk-navbar-nav uk-hidden-small">
					@if(Auth::check())
		        <li class="@section('check_link')@show">{{link_to_route('check', 'Environment Status')}}</li>
		        <li class="@section('integrations_link')@show">{{link_to_route('integrations', 'Integrations')}}</li>
						<li class="@section('reporting_link')@show">{{link_to_route('reporting', 'Reporting')}}</li>
		        <li class="@section('alerts_link')@show">{{link_to_route('alerts', 'Alerts')}}</li>
						<li class="@section('users_link')@show">{{link_to_route('users', 'Users')}}</li>
					@endif
		    	</ul>
					<div class="uk-navbar-flip uk-hidden-medium uk-hidden-small">
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

		<div id="main" class="uk-container uk-container-center">
			@yield('content', 'You should add your content here')
		</div><!-- .container-fluid -->
	
		@section('scripts')
		@show
		
		<footer>
			<div class="uk-container uk-container-center">
				<div class="uk-grid">
					<div class="uk-width-4-10">
					Copyright &copy; 2015 NoSprawl, Inc.
					</div>
					<div class="uk-width-1-10">
					</div>
					<div style="text-align: right;" class="uk-width-5-10">
					<strong>Contact us 24/7</strong><br />+1 (678) 705 9235<br /><a href="#">stayup@nosprawl.com</a><br /><a href="#">@teamnosprawl</a>
					</div>
				</div>
				
			</div>
		</footer>
	</div>
	<div id="node_details_modal_container">
		<div id="node_details_modal">
			<div id="node_details_modal_inner">
				<div class="uk-grid">
					<div class="uk-width-1-5" style="width: 27%;">
						<h1 class="uk-article-title">Environment Details</h1>
					</div>
					<div class="uk-width-1-5" style="padding-top: 9px; width: 24%;">
						<img id="i_sp_logo" style='top: -1px; position: relative;' src='/svg/aws.svg' width='70px'>
						<span class="slash">/</span>
						<span class="package_man i_platform"></span>
						<span class="slash">/</span>
						<span class="package_man i_type"></span>
					</div>
					<div class="uk-width-1-5" style="width: 16%;">
						<div class="info_group">
							<div class="title">Hostname</div>
							<div class="info i_hostname"></div>
						</div>
					</div>
					<div class="uk-width-1-5" style="width: 16%;">
						<div class="info_group">
							<div class="title">Physical Location</div>
							<div class="info i_last_patch"></div>
						</div>
					</div>
					<div class="uk-width-1-5" style="width: 15%; margin-left: -13px;">
						<div class="info_group">
							<div class="title">Base Image</div>
							<div class="info i_base_image trim"></div>
						</div>
					</div>
				</div>
				<table class="uk-table">
					<thead>
						<th style='text-align: center;'>Patch Status</th>
						<th>Package Name</th>
						<th>Upstream Version</th>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				<div id="package_info_loading">
					<div class="spinner">
					  <div class="bounce1"></div>
					  <div class="bounce2"></div>
					  <div class="bounce3"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>