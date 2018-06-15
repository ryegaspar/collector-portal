<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@section('title')@show</title>
	<link rel="stylesheet" href="{{ URL::asset('admin/css/app.css') }}">
	@yield('header')
</head>

<body>

	<div class="wrapper" id="app">
		<topnavbar></topnavbar>

		<sidebar uri="{{ \Route::current()->uri() }}"></sidebar>

	@yield('content')

	<!-- Page footer-->
		<footer class="footer-container">
			<span>&copy; 2018 - ryeg_</span>
		</footer>
	</div>

	@yield('footer')
</body>
</html>