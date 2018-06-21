<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Unifin Employee - Login</title>
	<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>

<body class="app flex-row align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card-group">
					<div id="app" class="card p-4">
						<login></login>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="{{ url::asset('js/app.js') }}"></script>
</body>
</html>