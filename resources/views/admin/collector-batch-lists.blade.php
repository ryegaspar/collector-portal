@extends('layouts.admin')
@section('title', ' - Collector Batch List')
@section('topnavbar')
	<topnavbar title="Collector Batch List"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<collector-batch-lists id="{{ $id }}"></collector-batch-lists>
		</div>
	</main>
@endsection