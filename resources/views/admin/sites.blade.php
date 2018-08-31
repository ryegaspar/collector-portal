@extends('layouts.admin')
@section('title', ' - Sites')
@section('topnavbar')
	<topnavbar title="Sites"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<sites></sites>
		</div>
	</main>
@endsection
