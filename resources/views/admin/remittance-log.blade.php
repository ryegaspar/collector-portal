@extends('layouts.admin')
@section('title', ' - Remittance Log')
@section('topnavbar')
	<topnavbar title="Remittance Log"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<remittance-log :unifinclients="{{ $clientList }}" class="mt-md-4"></remittance-log>
		</div>
	</main>
@endsection