<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Unifin - Reset Password</title>
	<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
</head>

<body class="app flex-row align-items-center">
	<div class="container full-page login-page section-image" filter-color="black">
		<div class="full-page-background"
			 style="background-image: url('{{ URL::asset('images/bg01.jpg') }}')"></div>
		<div class="row justify-content-center block-center content" style="padding-top: 20px">
			<div class="col-md-4">
				<div class="card-group">
					<div id="app" class="card p-4" style="background-color:rgba(255,255,255,0.1);border: none">
						<div class="card-body">
							<form method="POST" action="{{ route('collector.collector-reset-password.submit') }}">
								@csrf
								<div class="input-group mb-2">
									<input id="password"
										   type="password"
										   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
										   placeholder="Password"
										   name="password"
										   required>

									@if ($errors->has('password'))
										<span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
									@endif
								</div>
								<div class="input-group mb-3">
									<input id="password-confirm"
										   type="password"
										   class="form-control"
										   placeholder="Confirm Password"
										   name="password_confirmation"
										   required>
								</div>

								<div class="row">
									<button type="submit"
											class="btn btn-primary m-auto">Reset Password
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>