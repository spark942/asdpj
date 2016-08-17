<!-- libraries -->
{!! Html::script('js/moment-with-locales.js') !!}
{!! Html::script('js/moment-timezone-with-data-2010-2020.js') !!}
{!! Html::script('js/angular/angular.min.js') !!}
{!! Html::script('js/angular/dirPagination.js') !!}
{!! Html::script('js/jquery-3.1.0.min.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
<!-- init angular -->
{!! Html::script('js/angular/app.js?v=' . env('JS_VERSION')) !!}
<!-- services -->
<!--
{!! Html::script('js/angular/characterService.js?v=' . env('JS_VERSION')) !!}
{!! Html::script('js/angular/weaponService.js?v=' . env('JS_VERSION')) !!}
{!! Html::script('js/angular/questService.js?v=' . env('JS_VERSION')) !!}
-->

{!! Html::script('js/angular/unitService.js?v=' . env('JS_VERSION')) !!}
<!-- controllers -->
<!--
{!! Html::script('js/angular/characterListController.js?v=' . env('JS_VERSION')) !!}
{!! Html::script('js/angular/leaderSkillListController.js?v=' . env('JS_VERSION')) !!}
{!! Html::script('js/angular/questListController.js?v=' . env('JS_VERSION')) !!}
-->
{!! Html::script('js/angular/totController.js?v=' . env('JS_VERSION')) !!}
<script type="text/javascript">
angular.module('saocr')
  .controller('TimeController', ['$scope', '$interval', function($scope, $interval) {
  	//moment.locale('en');
  	moment.locale('en');
    this.now = moment().format("ddd, MMM Do YYYY, h:mm:ss a");
    this.jpnow = moment().tz('GMT').format("ddd, MMM Do YYYY, h:mm:ss a");

    this.updateLocalTime = function() {
			this.now = moment().format("ddd, MMM Do YYYY, h:mm:ss a");
		};

		this.updateJpTime = function() {
			this.jpnow = moment().tz('GMT').format("ddd, MMM Do YYYY, h:mm:ss a");
		};

  	this.timedUpdate = function() {
  		(function(timeCtrl){
  			$interval(function(){
  				timeCtrl.updateLocalTime();
  				timeCtrl.updateJpTime();
  			}, 1000);
  		})(this);
    }

    this.timedUpdate();
  }]);

</script>
