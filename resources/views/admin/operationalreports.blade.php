@extends('layouts.admin')
@section('title', ' - Operational Reports')
@section('topnavbar')
	<topnavbar title="Operational Reports"></topnavbar>
@endsection
@section('content')
<head>
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css">
	<link rel="stylesheet" type="text/css" href="//rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/css/bootstrap-editable.css">
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

<h1 align="center">Collector PDC</h1>


    <table id="CollectorPDC" 
	 		data-toggle="table"
			data-search="true"
			data-filter-control="true" 
			data-show-export="true"
      		class="table table-striped table-bordered"
	   	    style="width:100%">
        <thead>
          <tr>
			<th data-field="name" data-sortable="true" data-filter-control="select">Name</th>
			<th data-field="desk" data-sortable="true" data-filter-control="select">Desk</th>
			<th data-field="collectiongroup" data-filter-control="select" data-sortable="true">Group</th>
			<th data-field="goal" data-sortable="true">Goal</th>
			<th data-field="percentToGoal" data-sortable="true">Percent to Goal</th>
			<th data-field="mtdtransaction" data-sortable="true">{{ date('M') }} Transactions</th>
			<th data-field="mtdpostdates" data-sortable="true">{{ date('M') }} Postdates</th>
			<th data-field="mtdtotal" data-sortable="true">{{ date('F') }} Total</th>
			<th data-field="nextmonth1" data-sortable="true">{{ Carbon\Carbon::now()->addMonths(1)->format('F') }} Total</th>
			<th data-field="nextmonth2" data-sortable="true">{{ Carbon\Carbon::now()->addMonths(2)->format('F') }} Total</th>
			<th data-field="nextmonth3" data-sortable="true">{{ Carbon\Carbon::now()->addMonths(3)->format('F') }} Total</th>
			<th data-field="nextmonth4" data-sortable="true">{{ Carbon\Carbon::now()->addMonths(4)->format('F') }} Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($two as $two)
            <tr>
              <td class="StandardTableRow">{{$two->Collector_Name}}</td>
              <td class="StandardTableRow">{{$two->Desk}}</td>
              <td class="StandardTableRow">{{$two->User_Group}}</td>
							<td class="StandardTableRow">{{$two->Goal}}</td>
							<td class="StandardTableRow">{{ number_format(($two->CurrentMonthTotal/$two->Goal)*100, 2) }}</td>
              <td class="StandardTableRow">{{$two->CurrentMonthTrs}}</td>
              <td class="StandardTableRow">{{$two->CurrentMonthPDC}}</td>
              <td class="StandardTableRow">{{$two->CurrentMonthTotal}}</td>
              <td class="StandardTableRow">{{$two->NextMonth1Total}}</td>
              <td class="StandardTableRow">{{$two->NextMonth2Total}}</td>
              <td class="StandardTableRow">{{$two->NextMonth3Total}}</td>
              <td class="StandardTableRow">{{$two->NextMonth4Total}}</td>
            </tr>
          @endforeach
			</tbody>
		</table>
		</div>
	</main>
<script>
</script>
@endsection