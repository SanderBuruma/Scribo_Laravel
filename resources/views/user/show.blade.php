@extends('layouts.app')

@section('title', "| $user->name")

@section('header')
<link rel="stylesheet" href="{{ asset('/css/showuser.css') }}">
@stop

@section('content')
<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="card">
			<div class="card-header">
				<table><tbody>
					<td>{{$user->name}}</td>
					<td title="words per minute / characters per second * 12">{{round($stats->WPM,2)}} WPM</td>
				</tbody></table>
			</div>
			<div class="card-body">
				<p>{{$user->country}}</p>
			</div>
		</div>
	</div>
</div>
@endsection