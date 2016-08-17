@extends('master')
@section('maps')
<section id="book-map">
	<div id="map-canvas"></div>
	<div id="hide-credit"></div>
</section>
@endsection
@section('content')
<section id="welcome">
	<h3>Homepage</h3>
	<div id="categories" class="row">
		<div id="cat-quests" class="col-xs-12 col-sm-12 col-md-6">
			<hr>
			<span class="btn btn-info btn-lg btn-block">Reset Timers</span>
			<div class="table-responsive">
				<small>
					<table class="table ">
						<thead>
							<tr><th></th><th class="text-right">Local</th><th class="text-right">Server</th><th class="text-right">Time left</th></tr>
						</thead>
						<tbody>
							<tr>
								<td>Dungeon</td>
								<td class="rtlt text-right">12:00 am</td><td class="rtst text-right"></td><td class="rttl text-right">TBD</td>
							</tr>
							<tr>
								<td>Quiz-Roulette</td>
								<td class="rtlt text-right"></td><td class="rtst text-right">12:00 am</td><td class="rttl text-right">TBD</td>
							</tr>
							<tr>
								<td>Tower of Trial (every 3days)</td>
								<td class="rtlt text-right"></td><td class="rtst text-right">12:00 am</td><td class="rttl text-right">TBD</td>
							</tr>
							<tr>
								<td>Battle Arena</td>
								<td class="rtlt text-right"></td><td class="rtst text-right">3:00 pm</td><td class="rttl text-right">TBD</td>
							</tr>
							<tr>
								<td>Daily Ranking Battle Start</td>
								<td class="rtlt text-right"></td><td class="rtst text-right">2:00 am</td><td class="rttl text-right">TBD</td>
							</tr>
							<tr>
								<td>Daily Ranking Battle End</td>
								<td class="rtlt text-right"></td><td class="rtst text-right">1:00 pm</td><td class="rttl text-right">TBD</td>
							</tr>
							<tr>
								<td>Guild Attendance &amp; Gifts</td>
								<td class="rtlt text-right"></td><td class="rtst text-right">12:00 am</td><td class="rttl text-right">TBD</td>
							</tr>
						</tbody>
					</table>
				</small>
			</div>
		</div>
		<div id="cat-characters" class="col-xs-12 col-sm-6 col-md-3">
			<hr>
			<span class="btn btn-primary btn-lg btn-block">Tower of Trial</span>
			<span class="btn btn-lg btn-block disabled">Round xx  - time left</span>
			<a href="/tot" class="btn btn-lg btn-block" role="button">See solutions here</a>
			<hr>
			<span class="btn btn-lg btn-block disabled"><span class="numberofsolution">X</span> solutions for this round<br></span>
			<span class="btn btn-lg btn-block disabled">Highest floor with solution : <span class="highestroundwithsolution">32</span></span>
		</div>
		<div id="cat-skills" class="col-xs-12 col-sm-6 col-md-3">
			<hr>
			<span class="btn btn-success btn-lg btn-block">Events</span>
			<span class="btn btn-lg btn-block disabled">Arena Reward bonus</span>
			<span class="btn btn-lg btn-block disabled">Artifact/Unit refresh time</span>
			<span class="btn btn-lg btn-block disabled">Dungeon Open</span>
			<span class="btn btn-lg btn-block disabled">$1 Happiness</span>
			<!-- <a href="#" class="btn btn-success btn-lg btn-block disabled" role="button">Bonus</a> -->
		</div>
	</div>
</section>

@endsection

@section('localscript')
<script type="text/javascript">


</script>
@endsection