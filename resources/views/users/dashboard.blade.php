@extends('layouts.app')
@section('title', ' - Dashboard')
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<dashboard :Summary="{{ $accountSummary }}"
					   month-name="{{ $monthName }}"
					   class="mt-md-4">
			</dashboard>
		</div>
	</main>
@endsection