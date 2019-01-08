@extends('layouts.admin')
@section('title', ' - Desk Transfer Request')
@section('topnavbar')
	<topnavbar title="Desk Transfer Request"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<desk-transfer-requests></desk-transfer-requests>
		</div>
	</main>
@endsection