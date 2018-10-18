@extends('layouts.admin')
@section('title', ' - SIF Closures')
@section('topnavbar')
	<topnavbar title="SIF Closures"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<sif-closures></sif-closures>
		</div>
	</main>
@endsection