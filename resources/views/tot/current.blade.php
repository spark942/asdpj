@extends('master')

@section('customhead')
<title>ToT Round {{ $totround }}</title>
@endsection

@section('maps')
<section id="book-map">
	<div id="map-canvas"></div>
	<div id="hide-credit">
	</div>
</section>
@endsection


@section('content')
<section class="container">
	<div class="col-xs-12 col-sm-6 col-md-6">
		<h3 class="">Tower of Trial Round {{ $totround }} </h3>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
		<h3 class="countdown pull-right"></h3>
	</div>
</section>

<section id="current-tot" ng-controller="TotController as TotCtrl">
	<div class="list-group tower-floors">

		<!-- Floor -->
		<span ng-repeat="floor in TotCtrl.floors" ng-cloak>
			<!-- Floor HEADER -->
			<h3 class="list-group-item floor-title" ng-class="{
				'list-group-item-danger': floor.solutions.length == 0,
				'list-group-item-warning': floor.solutions.length > 0 && floor.solutions.length <= 5,
				'list-group-item-success': floor.solutions.length > 5}" data-toggle="collapse" href="#f@{{floor.floorn}}solutions" ng-click="toggle(floor.floorn,floor.isCollapsed)">
					<!-- Floor Cleared ICON -->
				<i style="color: #5cb85c;" class="glyphicon glyphicon-ok" aria-hidden="true" ng-if="floorCleared(floor.floorn)"></i>
				<!-- Floor number -->
				Floor @{{floor.floorn}}
				<!-- Floor:  number of votes -->
				<span class="label label-default floor-info" ng-if="floor.solutions.length">@{{countvote(floor.floorn)}} votes</span>
				<!-- Floor: number of solutions -->
				<span class="label floor-info" ng-class="{
					'label-danger': floor.solutions.length == 0,
					'label-warning': floor.solutions.length > 0 && floor.solutions.length <= 5,
					'label-success': floor.solutions.length > 5}">@{{floor.solutions.length}} solutions</span>
			</h3>
			<div class="list-group-item floor-solution collapse" id="f@{{floor.floorn}}solutions" ng-if="floor.isCollapsed" ng-cloak>
				@if (Auth::check())
				@include('partial.totaddsolution')
				@endif
				<ul class="solutions list-group row" ng-if="floor.solutions.length != 0">
					<!-- Floor solutions -->
					@if (Auth::check())
					<li class="solution list-group-item" ng-repeat="solution in (filteredSolutions = (floor.solutions | totSolutionsFilter:TotCtrl.resultFilters | orderBy:['-karma']))">
					@else
					<li class="solution list-group-item" ng-repeat="solution in floor.solutions">
					@endif
						<!-- <span class="solution-info">votes</span> -->
						<div class="solution-details row">
							@if (Auth::check())
							<!-- Logged: Show Solution points and vote options -->
							<span class="label label-choice">@{{solution.karma | number:0}} pts</span>
							<span class="pull-right">
								<!-- Vote buttons if can vote -->
								<span class="label label-choice" ng-if="!voted(solution.sid) && canaddsolution(floor.floorn)">
									Vote :
									<button type="button" class="btn btn-success btn-xs" ng-click="TotCtrl.voteSolution(solution.sid,2)"><i class="glyphicon glyphicon-ok" aria-hidden="true"></i> Passed 1st try</button>
									<button type="button" class="btn btn-primary btn-xs" ng-click="TotCtrl.voteSolution(solution.sid,1)"><i class="glyphicon glyphicon-minus" aria-hidden="true"></i> Passed after few tries</button>
									<button type="button" class="btn btn-danger btn-xs" ng-click="TotCtrl.voteSolution(solution.sid,0)"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Not pass</button>
								</span>
								<!-- Voted -->
								<span class="label label-choice" ng-if="voted(solution.sid)">
									You voted :
									<button type="button" class="btn btn-success btn-xs nocursor" ng-if="voteType(solution.sid) == 2"><i class="glyphicon glyphicon-ok" aria-hidden="true"></i> Passed 1st try</button>
									<button type="button" class="btn btn-primary btn-xs nocursor" ng-if="voteType(solution.sid) == 1"><i class="glyphicon glyphicon-minus" aria-hidden="true"></i> Passed after few tries</button>
									<button type="button" class="btn btn-danger btn-xs nocursor" ng-if="voteType(solution.sid) == 0"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Not pass</button>
								</span>
							</span>
							@endif
							<!-- Solution details -->
							<span class="label label-choice">x@{{solution.gamespeed}} Speed</span>
							<span class="label label-warning" ng-if="solution.luck == true">Luck solution</span>
							<span class="label label-choice" ng-if="solution.note">Note : @{{solution.note}}</span>
						</div>
						<!-- Unit Team -->
						<div class="unitteam-container">
							<ul class="unitteam unitteam-upper">
								<li class="unitteam-unit unit1" ng-class="getRankBorder(solution.units[0].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[0].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[0].trans}}"></div>
								</li>
								<li class="unitteam-unit unit2" ng-class="getRankBorder(solution.units[1].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[1].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[1].trans}}"></div>
								</li>
								<li class="unitteam-unit unit3" ng-class="getRankBorder(solution.units[2].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[2].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[2].trans}}"></div>
								</li>
								<li class="unitteam-unit unit4" ng-class="getRankBorder(solution.units[3].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[3].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[3].trans}}"></div>
								</li>
								<li class="unitteam-unit unit5" ng-class="getRankBorder(solution.units[4].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[4].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[4].trans}}"></div>
								</li>
								<li class="unitteam-unit unit6" ng-class="getRankBorder(solution.units[5].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[5].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[5].trans}}"></div>
								</li>
							</ul>
							<ul class="unitteam unitteam-lower">
								<li class="unitteam-unit unit7" ng-class="getRankBorder(solution.units[6].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[6].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[6].trans}}"></div>
								</li>
								<li class="unitteam-unit unit8" ng-class="getRankBorder(solution.units[7].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[7].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[7].trans}}"></div>
								</li>
								<li class="unitteam-unit unit9" ng-class="getRankBorder(solution.units[8].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[8].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[8].trans}}"></div>
								</li>
								<li class="unitteam-unit unit10" ng-class="getRankBorder(solution.units[9].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[9].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[9].trans}}"></div>
								</li>
								<li class="unitteam-unit unit11" ng-class="getRankBorder(solution.units[10].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[10].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[10].trans}}"></div>
								</li>
								<li class="unitteam-unit unit12" ng-class="getRankBorder(solution.units[11].rank)">
									<div class="unitbg"></div>
									<div class="uniticon icon-unit60-@{{pad(solution.units[11].unitId,4)}}"></div>
									<div class="unittrans icon-misc-trans@{{solution.units[11].trans}}"></div>
								</li>
							</ul>
						</div>
					</li>
				</ul>
				<div class="row" ng-if="floor.solutions.length == 0">
					<div class="container">
						<h3>No solutions found for this floor =(</h3>
					</div>
				</div>
			</div>
		</span>
		<div class="splash jumbotron">
			<div class="container">
				<h2>Wait please...<br>Loading Tower of Trial SOLUTIONS</h2>
			</div>
		</div>
	</div>
	<div id="self-tot-progression">
		<div id="wrapperfixed" class="container">
			<div class="col-xs-12 col-sm-12 col-md-6">
				<div class="acc-header collapsed" data-toggle="collapse" href="#totfilters">
					<h3><i class="glyphicon glyphicon-chevron-down pull-right"></i><i class="glyphicon glyphicon-chevron-up pull-right"></i>Result Filters</h3>
				</div>
				<div class="collapse" id="totfilters">
				@if (Auth::check())
					<div class="row">
						<label id="tot-filter-evolution">Evolution Limit</label>
						<div class="pull-right" role="group" aria-label="tot-filter-evolution">
							<input id="totf-input-evolution-base" class="hidden-input" type="radio" name="tot-filter-evolution" ng-model="TotCtrl.resultFilters.maxevol" value="1">
							<label for="totf-input-evolution-base" class="label label-choice">Base</label>
							<input id="totf-input-evolution-senior" class="hidden-input" type="radio" name="tot-filter-evolution" ng-model="TotCtrl.resultFilters.maxevol" value="2">
							<label for="totf-input-evolution-senior" class="label label-choice">Senior</label>
							<input id="totf-input-evolution-trans1" class="hidden-input" type="radio" name="tot-filter-evolution" ng-model="TotCtrl.resultFilters.maxevol" value="3">
							<label for="totf-input-evolution-trans1" class="label label-choice">Trans 1</label>
							<input id="totf-input-evolution-trans2" class="hidden-input" type="radio" name="tot-filter-evolution" ng-model="TotCtrl.resultFilters.maxevol" value="4">
							<label for="totf-input-evolution-trans2" class="label label-choice">Trans 2</label>
							<input id="totf-input-evolution-trans3" class="hidden-input" type="radio" name="tot-filter-evolution" ng-model="TotCtrl.resultFilters.maxevol" value="5">
							<label for="totf-input-evolution-trans3" class="label label-choice">Trans 3</label>
						</div>
					</div>
					<div class="row">
						<label id="tot-filter-evolution">Rank Limit</label>
						<div class="pull-right" role="group" aria-label="tot-filter-rank">
							<input id="totf-input-rank-1star" class="hidden-input" type="radio" name="tot-filter-rank" ng-model="TotCtrl.resultFilters.maxrank" value="1">
							<label for="totf-input-rank-1star" class="label label-choice">1 <i class="glyphicon glyphicon-star"></i></label>
							<input id="totf-input-rank-2star" class="hidden-input" type="radio" name="tot-filter-rank" ng-model="TotCtrl.resultFilters.maxrank" value="2">
							<label for="totf-input-rank-2star" class="label label-choice">2 <i class="glyphicon glyphicon-star"></i></label>
							<input id="totf-input-rank-3star" class="hidden-input" type="radio" name="tot-filter-rank" ng-model="TotCtrl.resultFilters.maxrank" value="3">
							<label for="totf-input-rank-3star" class="label label-choice">3 <i class="glyphicon glyphicon-star"></i></label>
							<input id="totf-input-rank-4star" class="hidden-input" type="radio" name="tot-filter-rank" ng-model="TotCtrl.resultFilters.maxrank" value="4">
							<label for="totf-input-rank-4star" class="label label-choice">4 <i class="glyphicon glyphicon-star"></i></label>
							<input id="totf-input-rank-5star" class="hidden-input" type="radio" name="tot-filter-rank" ng-model="TotCtrl.resultFilters.maxrank" value="5">
							<label for="totf-input-rank-5star" class="label label-choice">5 <i class="glyphicon glyphicon-star"></i></label>
							<input id="totf-input-rank-6star" class="hidden-input" type="radio" name="tot-filter-rank" ng-model="TotCtrl.resultFilters.maxrank" value="6">
							<label for="totf-input-rank-6star" class="label label-choice">6 <i class="glyphicon glyphicon-star"></i></label>
						</div>
					</div>
					<div class="row">
						<label id="tot-filter-evolution">Game speed</label>
						<div class="pull-right" role="group" aria-label="tot-filter-speed">
							<input id="totf-input-speed-1" class="hidden-input" type="checkbox" name="tot-filter-speed" ng-model="TotCtrl.resultFilters.speed1" value="true">
							<label for="totf-input-speed-1" class="label label-choice">x1 Speed</label>
							<input id="totf-input-speed-2" class="hidden-input" type="checkbox" name="tot-filter-speed" ng-model="TotCtrl.resultFilters.speed2" value="true">
							<label for="totf-input-speed-2" class="label label-choice">x2 Speed</label>
							<input id="totf-input-speed-3" class="hidden-input" type="checkbox" name="tot-filter-speed" ng-model="TotCtrl.resultFilters.speed3" value="true">
							<label for="totf-input-speed-3" class="label label-choice">x3 Speed</label>
							<input id="totf-input-speed-5" class="hidden-input" type="checkbox" name="tot-filter-speed" ng-model="TotCtrl.resultFilters.speed5" value="true">
							<label for="totf-input-speed-5" class="label label-choice">x5 Speed</label>
						</div>
					</div>
					<div class="row"><label id="tot-filter-end">&nbsp;</label></div>
				@else
					<div class="notlogged">
						<p class="navbar-text">Connect to use filters or <a href="/auth/register">sign-up</a></p>
					</div>
				@endif
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 progression">
				<div class="acc-header collapsed" data-toggle="collapse" href="#progressiondetails">
					<h3><i class="glyphicon glyphicon-chevron-down pull-right"></i><i class="glyphicon glyphicon-chevron-up pull-right"></i>Progression</h3>
				</div>
				<div class="collapse" id="progressiondetails">
					<div class="content">
					@if (Auth::check())
						<p>Progression - Coming soon</p>
					@else
						<div class="notlogged">
							<p class="navbar-text">Connect to see your progression or <a href="/auth/register">sign-up</a></p>
						</div>
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="form-group">
								<label class="col-md-4 control-label">E-Mail Address</label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="email" value="{{ old('email') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Password</label>
								<div class="col-md-6">
									<input type="password" class="form-control" name="password">
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember"> Remember Me
										</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">Login</button>

									<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
								</div>
							</div>
						</form>
					@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('localscript')
<script src="/js/chart.min.js"></script>
<script type="text/javascript">
	window.gameUnitBook = {!! $unitbook_json !!};
	window.currentTotSolutions = {!! $solutions_json !!};
	window.roundvotes = {!! $roundvotes !!};
	@if (Auth::check())
	window.myroundvotes = {!! $myroundvotes !!};
	@endif

	var eventTime= {!! $round_ts_end !!}; // Timestamp - Sun, 21 Apr 2013 13:00:00 GMT
	var currentTime = (new Date()).getTime() / 1000; // Timestamp - Sun, 21 Apr 2013 12:30:00 GMT
	var diffTime = eventTime - currentTime;
	var duration = moment.duration(diffTime*1000, 'milliseconds');
	var interval = 1000;

	setInterval(function(){
		duration = moment.duration(duration - interval, 'milliseconds');
		ticketLeft = Math.floor(({!! $round_ts_end !!} - ((new Date()).getTime() / 1000)) / 1200);

	    $('.countdown').text(
	    	"(" + ticketLeft + " tickets left) - " +
	    	duration.days() + ":" +
	    	('0' + duration.hours()).slice(-2) + ":" +
	    	('0' + duration.minutes()).slice(-2) + ":" +
	    	('0' + duration.seconds()).slice(-2));
	}, interval);
</script>
@endsection