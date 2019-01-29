@extends('layouts.admin')
@section('title', ' - Dashboard')
@section('topnavbar')
	<topnavbar title="Dashboard"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<dashboard :collectionhours="{{ $hoursWorked }}"></dashboard>
		</div>
	</main>

@endsection