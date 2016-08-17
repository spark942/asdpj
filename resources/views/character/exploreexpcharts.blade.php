@extends('master')
@section('maps')
<section id="book-map">
	<div id="map-canvas"></div>
	<div id="hide-credit"></div>
</section>
@endsection
@section('content')
<section id="CharacterGrowthCurves">
  <div>
    <h3>Characters Growth Chart</h3>
    <div>
      <h4>1,000,000 Growth Curve</h4>
      <h5>Available Levels</h5>
      <canvas id="character-exp-chart" width="1000" height="500" style=""></canvas>
      <h5>Unavailable Levels</h5>
      <canvas id="character-exp-chart2" width="1000" height="500" style=""></canvas>
    </div>
  </div>
  
</section>

@endsection

@section('localscript')
<script src="/js/chart.min.js"></script>
<script type="text/javascript">
(function(){
  var data = {
    labels: [
      @foreach ($expcharts[1] as $level)
        @if ($level->character_level <= 70)
        "{{ $level->character_level }}",
        @endif
      @endforeach
    ],
    datasets: [
      @foreach ($expcharts as $expchart)
      {
        label: "1,000,000 Growth Curve",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
          @foreach ($expchart as $level)
          @if ($level->character_level <= 70)
          {{ $level->required_exp }},
          @endif
          @endforeach
        ]
      },
      @endforeach
    ]
  };
  var data2 = {
    labels: [
      @foreach ($expcharts[1] as $level)
        @if ($level->character_level >= 70 && ($level->character_level % 5 == 0))
        "{{ $level->character_level }}",
        @endif
      @endforeach
    ],
    datasets: [
      @foreach ($expcharts as $expchart)
      {
        label: "1,000,000 Growth Curve",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [
          @foreach ($expchart as $level)
          @if ($level->character_level >= 70 && ($level->character_level % 5 == 0))
          {{ $level->required_exp }},
          @endif
          @endforeach
        ]
      },
      @endforeach
    ]
  };
  var options = {
    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,
    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",
    //Number - Width of the grid lines
    scaleGridLineWidth : 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: false,
    //Boolean - Whether the line is curved between points
    bezierCurve : false,
    //Boolean - Whether to show a dot for each point
    pointDot : false,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 3,
  };

  var ctx = document.getElementById("character-exp-chart").getContext("2d");
  var ctx2 = document.getElementById("character-exp-chart2").getContext("2d");
  Chart.defaults.global.scaleOverride = true;
  Chart.defaults.global.scaleSteps = 12;
  Chart.defaults.global.scaleStepWidth = 10000;
  Chart.defaults.global.scaleStartValue = 0;
  var myLineChart = new Chart(ctx).Line(data, options);
  myLineChart.options.tooltipTemplate = "<%if (label){%>Level <%=label%>: <%}%><%= value %> EXP";
  Chart.defaults.global.scaleOverride = true;
  Chart.defaults.global.scaleSteps = 9;
  Chart.defaults.global.scaleStepWidth = 100000;
  Chart.defaults.global.scaleStartValue = 100000;
  var myLineChart2 = new Chart(ctx2).Line(data2, options);
  myLineChart2.options.tooltipTemplate = "<%if (label){%>Level <%=label%>: <%}%><%= value %> EXP";
}());

</script>
@endsection