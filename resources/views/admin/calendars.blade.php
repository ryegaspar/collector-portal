@extends('layouts.admin')
@section('title', ' - Calendars')
@section('topnavbar')
	<topnavbar title="Calendars"></topnavbar>
@endsection
@section('content')
	<!-- Main content -->
	<main class="main">
		<div class="container-fluid">
			<iframe src="https://calendar.google.com/calendar/embed?showPrint=0&amp;mode=AGENDA&amp;height=675&amp;wkst=1&amp;bgcolor=%2333ccff&amp;src=unifinrs.com_pck5uff0d8f1q7nbc2ds228k10%40group.calendar.google.com&amp;color=%23182C57&amp;src=unifinrs.com_ar28udfbdqmvmh2s579v8tqnck%40group.calendar.google.com&amp;color=%23711616&amp;ctz=America%2FChicago" style="border-width:0" width="900" height="675" frameborder="0" scrolling="no"></iframe>
		</div>
	</main>

@endsection