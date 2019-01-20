@extends('layouts.admin')
@section('title', ' - Correspondence Log')
@section('topnavbar')
	<topnavbar title="Correspondence Log"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<correspondence-log :unifinclients="{{ $clientList }}" class="mt-md-4"></correspondence-log>
		</div>
	</main>
@endsection