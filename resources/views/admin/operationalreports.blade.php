@extends('layouts.admin')
@section('title', ' - Operational Reports')
@section('topnavbar')
	<topnavbar title="Operational Reports"></topnavbar>
@endsection
@section('content')
	<main class="main">
		<div class="container-fluid">
			<operationalreports class="mt-md-4"></operationalreports>

			<div id="accordion">

				<div class="card">
					<div class="card-header" id="headingOne">
						<h5 class="mb-0">
							<button class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							Collection Management
							</button>
						</h5>
					</div>
					<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
						<div class="card-body">
							<table>
								<tbody>
									<tr>
										<td><a href="/admin/collector-pdc" class="btn btn-dark" role="button">Collector PDC</a></td>
										<td><p></p></td>
									</tr>
									<tr>
										<td><a href="/admin/collector-average" class="btn btn-dark" role="button">Collector Average</a></td>
									</tr>
								</tbody>
							</table>
						  </div>
					</div>
				</div>
				<div class="card">
					<div class="card-header" id="headingTwo">
						<h5 class="mb-0">
							<button class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
							Account Management
							</button>
						</h5>
					</div>
					<div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
						<div class="card-body">
							<table>
								<tbody>
									<tr>
										<td><a href="/admin/accounts-in-new-status" class="btn btn-dark" role="button">New Status Account Summary</a></td>
										<td><p></p></td>
									</tr>
								</tbody>
							</table>
						  </div>
					</div>
				</div>

			</div>

		</div>
	</main>
@endsection