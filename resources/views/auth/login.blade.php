<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Unifin - Login</title>
	<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>

<body>

<body class="app flex-row align-items-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card-group">
					<div class="card p-4">
						<div class="card-body">
							<h1>Login</h1>
							<p class="text-muted">Sign In to your account</p>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-user"></i></span>
								</div>
								<input type="text" class="form-control" placeholder="Username">
							</div>
							<div class="input-group mb-4">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="icon-lock"></i></span>
								</div>
								<input type="password" class="form-control" placeholder="Password">
							</div>
							<div class="row">
								<div class="col-6">
									<button type="button" class="btn btn-primary px-4">Login</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap and necessary plugins -->
	<script src="vendors/js/jquery.min.js"></script>
	<script src="vendors/js/popper.min.js"></script>
	<script src="vendors/js/bootstrap.min.js"></script>

</body>
</html>