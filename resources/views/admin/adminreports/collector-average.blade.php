@extends('layouts.admin')
@section('title', ' - Collector Three Month Average')
@section('topnavbar')
	<topnavbar title="Collector Three Month Average"></topnavbar>
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

<h1 align="center">Collector Three Month Average</h1>


    <table id="CollectorAverage" 
	 		data-toggle="table"
			data-search="true"
			data-filter-control="true" 
			data-show-export="true"
      		class="table table-striped table-bordered"
	   	    style="width:100%">
        <thead>
          <tr>
			<th data-field="name" data-sortable="true" data-filter-control="input">Name</th>
			<th data-field="desk" data-sortable="true" data-filter-control="input">Desk</th>
			<th data-field="collectiongroup" data-filter-control="select" data-sortable="true">Group</th>
			<th data-field="firstmonth" data-sortable="true">FirstMonth</th>
			<th data-field="averagetransactions" data-sortable="true">Three Month Average Transactions</th>
			<th data-field="averagefee" data-sortable="true">Three Month Average Fee</th></tr>
        </thead>
        <tbody>
          @foreach($threemonthaverage as $threemonthaverage)
            <tr>
              <td class="StandardTableRow">{{$threemonthaverage->CollectorName}}</td>
							<td class="StandardTableRow">{{str_pad($threemonthaverage->DeskNumber,3,'0',STR_PAD_LEFT)}}</td>
              <td class="StandardTableRow">{{$threemonthaverage->GroupName}}</td>
							<td class="StandardTableRow">{{date("m/d/Y", strtotime($threemonthaverage->FirstMonth))}}</td>
							<td class="StandardTableRow">{{$threemonthaverage->AverageTransactions}}</td>
							<td class="StandardTableRow">{{$threemonthaverage->AverageFee}}</td>
            </tr>
          @endforeach
			</tbody>
		</table>
	</div>
	</main>
<script>
</script>
@endsection