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
	</main>
@endsection