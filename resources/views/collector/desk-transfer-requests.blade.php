@extends('layouts.app')
@section('title', ' - Desk Transfer Requests')
@section('topnavbar')
	<topnavbar title="Desk Transfer Requests"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<desk-transfer-requests></desk-transfer-requests>
		</div>
	</main>
@endsection