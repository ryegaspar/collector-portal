<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="author" content="Ryan Gaspar">
	<link rel="shortcut icon" href="img/favicon.png">

	<title>INUFIN</title>

	<!-- Main styles for this application -->
	<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
	<div id="app" class="app">
		<topnavbar></topnavbar>

		<div class="app-body">
			<sidebar></sidebar>
			<!-- Main content -->
			<main class="main">

				<div class="container-fluid">

				</div>
				<!-- /.conainer-fluid -->
			</main>

		</div>

		<footer class="app-footer">
			<span>© ryeg_</span>
		</footer>
	</div>

	<!-- Bootstrap and necessary plugins -->
	<script src="{{ URL::asset('js/app.js') }}"></script>
</body>
</html>