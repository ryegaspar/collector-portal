@extends('layouts.admin')
@section('title', ' - Dashboard')
@section('topnavbar')
	<topnavbar title="Dashboard"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<dashboard :Totals="{{ $todaysTotals }}" class="mt-md-4"></dashboard>
		</div>
		<div class="container-fluid">
			<table>
				<tr>
					<td>Group</td>
					<td>GoodForToday</td>
					<td>CurrentMonth</td>
					<td>NextMonth</td>
					<td>ThirtyDay</td>
					<td>NinetyDay</td>
					<td>AllIn</td>
				</tr>
				@foreach($todaysTotals as $value)
					<tr>
						<td>{{ $value-> UGP.UGP_DESC}}</td>
						<td>{{ $value-> GFT}}</td>
						<td>{{ $value-> CurrentMonth}}</td>
						<td>{{ $value-> NextMonth}}</td>
						<td>{{ $value-> 30Day}}</td>
						<td>{{ $value-> 90Day}}</td>
						<td>{{ $value-> AllIn}}</td>
					</tr>
				@endforeach
			</table>
		</div>

	</main>

@endsection