@extends('layouts.admin')
@section('title', ' - Users')
@section('topnavbar')
	<topnavbar title="Users"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<users></users>
		</div>
	</main>
@endsection