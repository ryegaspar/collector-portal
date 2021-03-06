<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="Ryan Gaspar">
	<link rel="shortcut icon" href="img/favicon.png">

	<title>{{ config('app.name', 'UNIFIN') }}@yield('title')</title>

	<!-- Main styles for this application -->
	<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
	@yield('header')
	<script>
		window.App = {!! json_encode([
			'name' => Auth::user()->full_name,
			'userId' => Auth::user()->id,
			'desk' => Auth::user()->desk,
		]) !!}
	</script>
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
	<div id="app" class="app">
		@yield('topnavbar')

		<div class="app-body">
			<sidebar></sidebar>
			@yield('content')
		</div>

		<footer class="app-footer">©Unifin Inc 2019</footer>
	</div>

	<!-- Bootstrap and necessary plugins -->
	<script src="{{ URL::asset('js/app.js') }}"></script>
	@yield('footer')
	<script>
		$(function() {
			$('body').tooltip({
				selector: '[data-toggle=tooltip]'
			});
		});
	</script>
</body>
</html>