
<header class="container">
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container">	
			<div class="navbar-collapse">
				<div class="col-sx-12 col-sm-3 col-md-3">
					<div class="navbar-header text-center">
						<a class="" href="/"><img class="img-rounded" src="/img/eflogo.png" height="80px"></a>
					</div>
				</div>
				<div class="col-sx-12 col-sm-9 col-md-5">
					<div class="container-fluid">
						<h4 class="navbar-text text-center">Endless Frontier (Global)</h4>
					</div>
					<div class="container-fluid">
						<!-- <span class="navbar-text">db version : 1.1.1</span> -->
						<ul class="nav navbar-nav">
							<!-- <li><a href="#"><input class="search" type="text" name="searchbar" style="width: 300px;" /></a></li> -->
							<!-- <li class="dropdown">
							  <a href="/characters" data-toggle="dropdown" class="dropdown-toggle">Characters <b class="caret"></b></a>
							  <ul class="dropdown-menu" id="menu1">
							    <li><a href="/characters">NPC Characters</a></li>
							    <li class="disabled"><a href="#">Hero Characters</a></li>
							    <li class="divider"></li>
							    <li class="disabled"><a href="#">Awaken Recipes</a></li>
							    <li class="divider"></li>
							    <li><a href="/characterexpcharts">NPC Characters Growth Curve</a></li>
							  </ul>
							</li> -->
							<li><a href="/tot">Tower of Trial</a></li>
							<li><a href="/events">Events</a></li>
							@if (Auth::check())
							<li><a href="/me">Account</a></li>
							<li><a href="/auth/logout">Log out</a></li>
							@else
							<li><a href="/auth/login">Sign in</a></li>
							<li><a href="/auth/register">Sign up</a></li>
							@endif
						</ul>
					</div>
					<!-- <p class="navbar-text">Current events : </p> -->
				</div>
				<div ng-controller="TimeController as TimeCtrl" class="col-sx-12 col-sm-9 col-md-4" style="padding: 0;">
					<div id="localtime" class="">
						<p class="navbar-text navbar-right">Local : @{{TimeCtrl.now}}</p>
					</div>
					<div id="jptime" class="">
						<p class="navbar-text navbar-right">Server : @{{TimeCtrl.jpnow}}</p>
					</div>
				</div>
			</div>
		</div>
	</nav>
</header>
