<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="Ryan Gaspar">
	<link rel="shortcut icon" href="img/favicon.png">

	<title>{{ config('app.name', 'UNIFIN') }}@section('title')@show</title>

	<!-- Main styles for this application -->
	<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
	@yield('header')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
	<div id="app" class="app">
		<topnavbar></topnavbar>

		<div class="app-body">
			<sidebar></sidebar>
			@yield('content')
		</div>

		<footer class="app-footer">
			<span>© 2018 ryeg_</span>
		</footer>
	</div>

	<!-- Bootstrap and necessary plugins -->
	<script src="{{ URL::asset('js/app.js') }}"></script>
	@yield('footer')
</body>
</html>