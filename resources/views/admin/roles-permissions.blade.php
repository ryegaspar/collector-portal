@extends('layouts.admin')
@section('title', ' - Roles & Permissions')
@section('topnavbar')
	<topnavbar title="Roles & Permissions"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<roles-permissions></roles-permissions>
		</div>
	</main>
@endsection