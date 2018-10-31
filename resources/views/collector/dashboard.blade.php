@extends('layouts.app')
@section('title', ' - Dashboard')
@section('topnavbar')
	<topnavbar title="Dashboard"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<dashboard :Summary="{{ $accountSummary }}" class="mt-md-4"></dashboard>
		</div>
		<div class="container-fluid">
			<dashboard :Summary="{{ $todaysTotals }}" class="mt-md-4"></dashboard>
		</div>
	</main>
@endsection