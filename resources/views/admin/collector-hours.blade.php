@extends('layouts.admin')
@section('title', ' - Detailed Collector Hours')
@section('topnavbar')
	<topnavbar title="Detailed Collector Hours"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	    <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    	</head>
	<main class="main">
		<div class="container-fluid">
			<collector-hours :collectionhours="{{ $hoursWorkedDetail }}" class="mt-md-4"></collector-hours>
		</div>
	</main>

@endsection