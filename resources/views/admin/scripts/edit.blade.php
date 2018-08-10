@extends('layouts.admin')
@section('title', ' - Edit Script')
@section('topnavbar')
	<topnavbar title="Edit Script"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<script-create-edit class="mt-5" id="{{ $scriptId }}" >
			</script-create-edit>
		</div>
	</main>
@endsection