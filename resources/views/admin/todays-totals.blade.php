@extends('layouts.admin')
@section('title', ' - Detailed Todays Totals')
@section('topnavbar')
	<topnavbar title="Detailed Todays Totals"></topnavbar>
@endsection
@section('content')
<head>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/editable/bootstrap-table-editable.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/export/bootstrap-table-export.js"></script>
	<script src="//rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/filter-control/bootstrap-table-filter-control.js"></script>
</head>
	<!-- Main content -->	
	<main class="main">
		<div class="container-fluid">
			<operationalreports class="mt-md-4"></operationalreports>

<h1 align="center">Today's Totals</h1>


    <table id="TodaysTotals" 
	 		data-toggle="table"
			data-search="true"
			data-filter-control="true" 
			data-show-export="true"
      		class="table table-striped table-bordered"
	   	    style="width:100%">
        <thead>
          <tr>
			<th data-field="clientname" data-filter-control="select" data-sortable="true">Client Name</th>
			<th data-field="clientcode" data-filter-control="select" data-sortable="true">Client Code</th>
			<th data-field="collectiongroup" data-filter-control="select" data-sortable="true">Group</th>
			<th data-field="collectorname" data-sortable="true">Collector Name</th>
			<th data-field="debtornumber" data-sortable="true">Account Number</th>
			<th data-field="checkamount" data-sortable="true">Payment Amount</th>
			<th data-field="paymentdate" data-sortable="true">Payment Date</th>
  </tr>
        </thead>
        <tbody>
          @foreach($ttotals as $ttotals)
            <tr>
				<td class="StandardTableRow">{{$ttotals->ClientName}}</td>
				<td class="StandardTableRow">{{$ttotals->ClientCode}}</td>
				<td class="StandardTableRow">{{$ttotals->CollectorGroup}}</td>
				<td class="StandardTableRow">{{$ttotals->UserName}}</td>
				<td class="StandardTableRow">{{$ttotals->DebtorNumber}}</td>
				<td class="StandardTableRow">{{$ttotals->CheckAmount}}</td>
				<td class="StandardTableRow">{{date("Y-m-d", strtotime($ttotals->PaymentDate))}}</td>
            </tr>
          @endforeach
			</tbody>
		</table>
	</div>
	</main>
<script>
</script>
@endsection
