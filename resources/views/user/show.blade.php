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
					<td title="words per minute / characters per second * 12">{{round($user->races_len/$user->time_taken*12,2)}} WPM</td>
				</tbody></table>
			</div>
			<div class="card-body">
				<table class="table" id="stats"><tbody>
					<tr>
						<td>Member Since: </td>
						<td>{{date("F jS, Y",strtotime($user->created_at))}}</td>
					</tr>
					<tr title="The average WPM over the last 25 races">
						<td>Last 25 WPM: </td>
						<td>{{$user->last25_wpm}} - <span title="The users' percentile ranking among the servers' users.">{{round((1-($user->rank-1) / $userscount)*100,1)}}%</td>
					</tr>
					<tr>
						<td title="The total average of characters typed per second multiplied by twelve">Average WPM: </td>
						<td>{{round($user->races_len/$user->time_taken*12,2)}}</span></td>
					</tr>
					<tr title="Total mistakes divided by the total length of all correctly typed races.">
						<td>Accuracy: </td>
						<td>{{round((1-$user->mistakes/$user->races_len)*100,2)}}%</td>
					</tr>
					<tr title="The total number of characters of their longest running marathon. Increase this by typing many races within a short time of each other.">
						<td>Longest Marathon: </td>
						<td>{{$user->longest_marathon}}</td>
					</tr>
					<tr title="The longest streak of perfectly typed texts counted. Counted as the total length of the consecutive perfect races">
						<td>Longest Perfect Streak: </td>
						<td>{{$user->longest_perfect_streak}}</td>
					</tr>
				</tbody></table>
			</div>
		</div>
	</div>
</div>
@endsection