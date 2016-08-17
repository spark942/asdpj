<!DOCTYPE html>
<html lang="en" ng-app="saocr">
<head>
	<meta charset="UTF-8">
	{!! Html::style('css/bootstrap.css') !!}
	{!! Html::style('css/main4.css?v=' . env('CSS_VERSION')) !!}
	{!! Html::style('css/sprites/unit60/sprite.css?v=' . env('CSS_VERSION')) !!}
	{!! Html::style('css/sprites/misc/sprite.css?v=' . env('CSS_VERSION')) !!}
	@yield('customhead')
</head>
<body class="dark">
	<div id="side">
		@include('partial.side')
	</div>
	<div id="corpse">
		@yield('map')
		<div id="wrapper">
			@include('partial.header')
			<div id="content" class="container">
				@yield('content')
			</div>
			@include('partial.footer')
		</div>
		@yield('prelocalscript')
		@include('partial.script')
		@yield('localscript')
	</div>
	
</body>
</html>