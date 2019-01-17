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
					<td title=""></td>
				</tbody></table>
			</div>
			<div class="card-body">
				<table class="table" id="stats"><tbody>
					<tr>
						<td>Member Since: </td>
						<td>{{date("F jS, Y",strtotime($user->created_at))}}</td>
					</tr>
					<tr>
						<td>Races Completed: </td>
						<td>{{number_format($user->races)}}</td>
					</tr>
					<tr title="The average WPM over the last 25 races and the user's percentile ranking. 75% means the user's score is better than 75% of the other users.">
						<td>Last 25 WPM: </td>
						<td>{{$user->last25_wpm}} WPM - {{max(0,round((1-($user->rank-1) / $userscount)*100,1))}}%</td>
					</tr>
					<tr title="The total average of characters typed per second multiplied by twelve">
						<td>Total Average WPM: </td>	
						<td>{{round($user->races_len/max($user->time_taken,1)*12,2)}}</span></td>
					</tr>
					<tr title="Sum of all mistakes divided by the sum length of all correctly typed races by this user.">
						<td>Accuracy: </td>
						<td>{{round((1-$user->mistakes/max($user->races_len,1))*100,2)}}%</td>
					</tr>
					<tr title="The total number of characters of their longest running marathon. Increase this by typing many races within a short time of each other.">
						<td>Longest Marathon: </td>
						<td>{{number_format($user->longest_marathon)}}</td>
					</tr>
					<tr title="The longest streak of perfectly typed texts counted. Counted as the total length of the consecutive perfect races">
						<td>Longest Perfect Streak: </td>
						<td>{{number_format($user->longest_perfect_streak)}}</td>
					</tr>
				</tbody></table>
			</div>
		</div>
	</div>
</div>
@endsection