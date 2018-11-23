@extends('layouts.admin')
@section('title', ' - Operational Reports')
@section('topnavbar')
	<topnavbar title="Operational Reports"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<a href="http://google.com" class="btn btn-default">Go to Google</a>
			<a href="http://google.com" class="btn btn-default">Go to Google</a>
			<table style="width:100%">
				@foreach($sif as $sif)
						<tr>
							<td>{{$sif->DBR_NO}}</td>
							<td>{{$sif->DBR_STATUS}}</td>
							<td>{{$sif->DBR_CLOSE_DATE_O}}</td>
						</tr>
				@endforeach
			</table>
		</div>
	</main>
@endsection