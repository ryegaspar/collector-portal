@extends('layouts.admin')
@section('title', ' - Recalls')
@section('topnavbar')
	<topnavbar title="Recalls"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<recalls class="mt-5"></recalls>
		</div>
	</main>
@endsection