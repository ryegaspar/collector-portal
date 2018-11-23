@extends('layouts.admin')
@section('title', ' - Detailed Collector Hours')
@section('topnavbar')
	<topnavbar title="Detailed Collector Hours"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<collector-hours :collectionhours="{{ $hoursWorkedDetail }}" class="mt-md-4"></collector-hours>
		</div>
	</main>

@endsection