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
						<td data-toggle="tooltip" title="Date of account creation">Member Since: </td>
						<td>{{date("F jS, Y",strtotime($user->created_at))}}</td>
					</tr>
					<tr>
						<td data-toggle="tooltip" title="Your total number of this user's completed races">Races Completed: </td>
						<td>{{number_format($user->races)}}</td>
                    </tr>
                    <tr>
                        <td data-toggle="tooltip" title="Shows your average WPM over the last 25 races!">Last 25 WPM: </td>
                        <td>{{$user->last25_wpm}} WPM - {{max(0,round((1-($user->rank-1) / $userscount)*100,1))}}%</td>
                    </tr>
					<tr>
						<td data-toggle="tooltip" title="The total average of characters typed per second multiplied by twelve">Total Average WPM: </td>
						<td>{{round($user->races_len/max($user->time_taken,1)*12,2)}}</span></td>
					</tr>
					<tr>
						<td data-toggle="tooltip" title="Sum of all mistakes divided by the sum length of all correctly typed races by this user.">Accuracy: </td>
						<td>{{round((1-$user->mistakes/max($user->races_len,1))*100,2)}}%</td>
					</tr>
					<tr>
						<td data-toggle="tooltip" title="The total number of characters of their longest running marathon. Increase this by typing many races within a short time of each other.">Longest Marathon: </td>
						<td>{{number_format($user->longest_marathon)}}</td>
                    </tr>
					<tr>
						<td data-toggle="tooltip" title="The longest streak of perfectly typed texts counted as the total length of consecutive perfect races">Longest Perfect Streak: </td>
						<td>{{number_format($user->longest_perfect_streak)}}</td>
					</tr>
				</tbody></table>
			</div>
		</div>
	</div>
</div>
@endsection
