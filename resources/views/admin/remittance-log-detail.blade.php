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
				<div class="row">
					<div class="col-sm btn btn-primary disabled">Client Name</div>
					<div class="col-sm"></div>
					<div class="col-sm btn btn-primary disabled">Remittance Date</div>
					<div class="col-sm"></div>
					<div class="col-sm"><a href="{{ url('/admin/remittance-log/'.$remitdetail->client_name.'/'.$remitdetail->period_start_date.'/'.$remitdetail->period_end_date.'/') }}" class="btn btn-dark" role="button">Download Remit</a></div>
				</div>
				<div class="row">
					<div class="col-sm">{{$remitdetail->client_name}}</div>
					<div class="col-sm"></div>
					<div class="col-sm">{{$remitdetail->remit_date}}</div>
					<div class="col-sm"></div>
					<div class="col-sm"></div>
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
					<div class="col-sm-2 btn">{{$remitdetail->period_start_date}}</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-2 btn">{{$remitdetail->period_end_date}}</div>
					<div class="col-sm-7"></div>
				</div>
					<br>
					<br>
					<br>
				<table class="table table-bordered table-info" style="width:75%">
					<tbody>	
						<tr>
							<td>Remittance Amount</td>
							<td>{{$remitdetail->remit_amount}}</td>
							<td></td>
							<td>Sent Date</td>
							<td>{{$remitdetail->remitance_sent_date}}</td>
						</tr>
						<tr>
							<td>Commission Amount</td>
							<td>{{$remitdetail->commission_amount}}</td>
							<td></td>
							<td>Recieved Date</td>
							<td>{{$remitdetail->commission_recieved_date}}</td>
						</tr>
						<tr>
							<th class="table-dark">Total Collections</th>
							<th class="table-dark">{{$remitdetail->total_collections}}</th>
							<th class="table-dark"></th>
							<th class="table-dark"></th>
							<th class="table-dark"></th>
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
			</div>
		</div>
	</main>
@endsection