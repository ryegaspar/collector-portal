@extends('layouts.app')
@section('title', ' - Transactions')
@section('topnavbar')
	<topnavbar title="Transactions"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<transactions class="mt-md-4"></transactions>
		</div>
	</main>
@endsection