<html lang="en">
<head>
	<title>Jcap Placement</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Jcap Placement</a>
			</div>
		</div>
	</nav>
	<div class="container">
		<table class="table">
			<thead>
				@foreach($records[0] as $header)
					<th scope="col">{{ $header }}</th>
				@endforeach
			</thead>
			<tbody>
				@foreach($records[1] as $key => $placement)
					<tr>
						<td>{{ $key }}</td>
						@foreach($placement as $column)
							<td>{{ $column }}</td>
						@endforeach
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	{{--<div class="container">--}}
		{{--<a href="{{ URL::to('downloadExcel/csv') }}">--}}
			{{--<button class="btn btn-success">Download CSV</button>--}}
		{{--</a>--}}

		{{--<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}"--}}
			  {{--class="form-horizontal" method="post" enctype="multipart/form-data">--}}
			{{--<input type="file" name="import_file"/>--}}
			{{--<button class="btn btn-primary">Import File</button>--}}
		{{--</form>--}}
	{{--</div>--}}

</body>

</html>