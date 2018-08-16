<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Ryan Gaspar">
	<link rel="shortcut icon" href="img/favicon.png">

	<title>{{ config('app.name', 'UNIFIN') }} - Forbidden</title>

	<!-- Main styles for this application -->
	<style>
		body {
			font: normal 16px/26px Arial, sans-serif;
			background: #fafafa;
			color: #2a3744;
		}

		.error-page {
			margin: 100px 0 40px;
			text-align: center;
		}

		.error-page__header-image {
			width: 500px;
		}

		.error-page__title {
			font-family: "Roboto", Arial, sans-serif;
			font-size: 31px;
		}
	</style>
</head>
<body class="app flex-row align-items-center">
	<div class="container">
		<div class="error-page">
			<header class="error-page__header">
				<img class="error-page__header-image" src="{{ URL::asset('images/403.jpg') }}" alt="Sad computer">
				<h1 class="error-page__title nolinks">FORBIDDEN</h1>
			</header>
		</div>
	</div>
</body>
</html>

