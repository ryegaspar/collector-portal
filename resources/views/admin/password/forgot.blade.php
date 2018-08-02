<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Unifin Admin - Login</title>
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
						@if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif
						<div class="card-body">
							<form method="POST" action="{{ route('admin.forgot_password.submit') }}">
								@csrf
								<div class="input-group mb-3">
									<input type="text"
										   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"
										   style="border: 1px solid #888"
										   placeholder="Email"
										   required>
									@if ($errors->has('email'))
										<span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
									@endif
								</div>
								<div class="row">
									<button type="submit"
											class="btn btn-primary m-auto">Send Password Reset Link
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