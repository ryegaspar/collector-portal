@extends('layouts.admin')
@section('title', ' - Letter Request')
@section('topnavbar')
	<topnavbar title="Letter Request"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<letter-requests></letter-requests>
		</div>
	</main>
@endsection