@extends('layouts.admin')
@section('title', ' - Collector Batches')
@section('topnavbar')
	<topnavbar title="Collector Batches"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<collector-batches></collector-batches>
		</div>
	</main>
@endsection