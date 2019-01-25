@extends('layouts.admin')
@section('title', ' - New Status Account Summary')
@section('topnavbar')
	<topnavbar title="Account Summary for Status New"></topnavbar>
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

<h1 align="center">Account Summary for Status New</h1>


    <table id="CollectorPDC" 
	 		data-toggle="table"
			data-search="true"
			data-filter-control="true" 
			data-show-export="true"
      		class="table table-striped table-bordered"
	   	    style="width:100%">
        <thead>
          <tr>
			<th data-field="client" data-sortable="true" data-filter-control="input">Client Code</th>
			<th data-field="category" data-sortable="true" data-filter-control="select">Category</th>
			<th data-field="countofstatus" data-sortable="true">Number in Status</th>
			<th data-field="averagebalance" data-sortable="true">Average Balance</th>
         </tr>
        </thead>
        <tbody>
          @foreach($accountsinnew as $accountsinnew)
            <tr>
              <td class="StandardTableRow">{{$accountsinnew->ClientCode}}</td>
							<td class="StandardTableRow">{{$accountsinnew->Category}}</td>
							<td class="StandardTableRow">{{$accountsinnew->Count}}</td>
							<td class="StandardTableRow">{{$accountsinnew->AverageBalance}}</td>
            </tr>
          @endforeach
			</tbody>
		</table>
	</div>
	</main>
<script>
</script>
@endsection