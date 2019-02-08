@extends('layouts.app')
@section('title', ' - Collector Incentives')
@section('topnavbar')
	<topnavbar title="Collector Incentives"></topnavbar>
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

<h1 align="center">Resurgent February Competition (RCSOS1)</h1>


    <table id="CollectorIncentive" 
	 		data-toggle="table"
			data-search="true"
			data-filter-control="true" 
			data-show-export="false"
      		class="table table-striped table-bordered"
	   	    style="width:100%">
					 <thead>
          <tr>
			<th data-field="position" data-sortable="true">Number</th>
			<th data-field="name" data-sortable="true" data-filter-control="input">Name</th>
			<th data-field="desk" data-sortable="true" data-filter-control="input">Desk</th>
			<th data-field="mtdtransaction" data-sortable="true">Transactions</th>
			<th data-field="mtdpostdates" data-sortable="true">Postdates</th>
			<th data-field="mtdtotal" data-sortable="true">{{ date('F') }} Total</th>
      </tr>
        </thead>
        <tbody>
					<?php $i = 0 ?>
          @foreach($Incentive as $Incentive)
					<?php $i++ ?>
            <tr>
							<td class="StandardTableRow">{{$i}}</td>
              <td class="StandardTableRow">{{$Incentive->Name}}</td>
              <td class="StandardTableRow">{{str_pad($Incentive->DESK,3,'0',STR_PAD_LEFT)}}</td>
              <td class="StandardTableRow">{{$Incentive->TransTotal}}</td>
							<td class="StandardTableRow">{{$Incentive->PDCTotal}}</td>
              <td class="StandardTableRow">{{$Incentive->Total}}</td>
            </tr>
          @endforeach
			</tbody>
		</table>
		</div>
	</main>
<script>
</script>
@endsection