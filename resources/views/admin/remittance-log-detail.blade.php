@extends('layouts.admin')
@section('title', ' - Remittance Log')
@section('topnavbar')
	<topnavbar title="Remittance Log"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<div class="card-body">
			@foreach($remitdetail as $remitdetail)
			@foreach($test as $test)
				<div class="row">
					<div class="col-sm btn btn-primary disabled">Client Name</div>
					<div class="col-sm"></div>
					<div class="col-sm btn btn-primary disabled">Remittance Date</div>
					<div class="col-sm"></div>
					<div class="col-sm"><a href="{{ url('/admin/remittance-log/'.$remitdetail->client_name.'/'.$remitdetail->period_start_date.'/'.$remitdetail->period_end_date.'/') }}" class="btn btn-dark" role="button">Download Remit</a></div>
				</div>
				<div class="row">
					<div class="col-sm btn disabled">{{$remitdetail->client_name}}</div>
					<div class="col-sm btn disabled"></div>
					<div class="col-sm btn disabled">{{$remitdetail->remit_date}}</div>
					<div class="col-sm btn disabled"></div>
					<div class="col-sm btn disabled"></div>
				</div>
					<br>
					<br>
				<div class="row">
					<div class="col-sm-2 btn btn-primary disabled">Period Start</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-2 btn btn-primary disabled">Period End</div>
					<div class="col-sm-7"></div>
				</div>
				<div class="row">
					<div class="col-sm-2 btn disabled">{{$remitdetail->period_start_date}}</div>
					<div class="col-sm-1 btn disabled">to</div>
					<div class="col-sm-2 btn disabled">{{$remitdetail->period_end_date}}</div>
					<div class="col-sm-7 btn disabled"></div>
				</div>
					<br>
					<br>
					<br>
				<table class="table table-bordered table-info" style="width:75%">
					<tbody>
						<tr>
							<th></th>
							<th>Report</th>
							<th>Database</th>
							<th></th>
							<th>Difference</th>
						</tr>
						<tr>
							<th>Remittance Amount</th>
							<td>{{$remitdetail->remit_amount}}</td>
							<td>{{$test->TRS_REMIT_AMT}}</td>
							<td></td>
							<td>{{$remitdetail->remit_amount - $test->TRS_REMIT_AMT}}</td>
						</tr>
						<tr>
							<th>Commission Amount</th>
							<td>{{$remitdetail->commission_amount}}</td>
							<td>{{$test->TRS_COMM_AMT}}</td>
							<td></td>
							<td>{{$remitdetail->commission_amount - $test->TRS_COMM_AMT}}</td>
						</tr>
						<tr>
							<th>Total Collections</th>
							<td>{{$remitdetail->total_collections}}</td>
							<td>{{$test->TRS_AMT}}</td>
							<td></td>
							<td>{{$remitdetail->total_collections - $test->TRS_AMT}}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered table-info" style="width:75%">
					<tbody>	
						<tr>
							<td>Sent Date</td>
							<td>{{$remitdetail->remittance_sent_date}}</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Recieved Date</td>
							<td>{{$remitdetail->commission_recieved_date}}</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
					<br>
					<br>
					<br>
				<div class="row">
					<div class="col-sm">
					</div>
					<div class="col-sm">
						<div class="form-group">
							<label for="comment">Notes:</label>
							<textarea class="form-control" rows="5" id="comment" disabled>{{$remitdetail->notes}}</textarea>
						</div>
					</div>
				</div>
			@endforeach
			@endforeach
			</div>
		</div>
	</main>
@endsection