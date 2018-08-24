@extends('layouts.app')
@section('title', ' - Letter Requests')
@section('topnavbar')
	<topnavbar title="Letter Requests"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<letter-requests></letter-requests>
		</div>
	</main>
@endsection