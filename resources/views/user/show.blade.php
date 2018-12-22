@extends('layouts.app')

@section('title', "| $user->name")

@section('content')
<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="card">
			<div class="card-header">
				{{$user->name}}
			</div>
			<div class="card-body">
				<p>{{$user->country}}</p>
			</div>
		</div>
	</div>
</div>
@endsection