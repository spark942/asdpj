@extends('master')

@section('customhead')
<title>{{ $char_kind->head_name }} {{ $char_kind->name }} ({{ $char_kind->name_english }})</title>
@endsection

@section('maps')
<section id="book-map">
	<div id="map-canvas"></div>
	<div id="hide-credit">
		<h1 class="character-title"> <!-- <span id="book-author">par </span> -->- October 2014</h1>
	</div>
</section>
azidojoaizd {{ Lang::get('saocr_weapon_categories.1.name') }}
@endsection


@section('content')
<section class="page-header">
	<a href="/characters">Back to character list</a>
	<h3>Character profile</h3>
</section>
<section id="character-profile">
	<div id="character-kind" class="row">
		<div id="character-kind-information" class="col-md-8">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><div class="img-circle icon-char40-{{ $char_kind_imgid }}-thum"></div></th>
						<th colspan="2">
							{{ Lang::get('character.character_kind.' . $char_kind->id . '.head_name') }} {{ Lang::get('character.character_kind.' . $char_kind->id . '.name') }}
						</th>
						<th>[#{{ $char_kind->id }}]</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Name {{ ($char_kind->name_english != '' ? '(english)' : '' ) }}</td>
						<td>{{ $char_kind->head_name }} {{ $char_kind->name }} {{ ($char_kind->name_english != '' ? '(' . $char_kind->name_english . ')' : '' ) }}</td>
						<td>Team Cost</td>
						<td>{{ $chars[0]->cost }}</td>
					</tr>
					<tr>
						<td>Rarity</td>
						<td>{{ $char_kind->rarity_name }}</td>
						<td>Max level</td>
						<td>{{ $char_kind->max_level }}</td>
					</tr>
					<tr>
						<td>Element</td>
						<td>{{ $chars[0]->elemental_name }} - {{ Lang::get('character.elemental.' . $chars[0]->elemental_id) }}</td>
						<td>Weapon type</td>
						<td>{{ $chars[0]->weapon_category_name }} - {{ Lang::get('saocr_weapon_categories.' . $chars[0]->weapon_category_id . '.name') }}</td>
					</tr>
					<tr>
						<td>Sex</td>
						<td>{{ Lang::get('character.sex_type.' . $char_kind->sex_type) }}</td>
						<td>Sell price</td>
						<td>{{ $char_kind->sell_price }} G</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="character-evolve" class="col-md-4" style="margin-top: 20px;">
			@if ($char_before_awake ||$char_after_awake)
			<ul class="tabs">
				@if ($char_before_awake)
		        <li>
		         	<input type="radio" checked name="tabs" id="tab1" class="ipt-tab">
		         	<label for="tab1">Awaken from</label>
					<div id="tab-content1" class="ipt-tab-content">
						<table class="table table-striped">
							<tr>
								<td>
									<a href="/ck/{{$char_before_awake->id}}">
										<strong>
											{{ Lang::get('character.character_kind.' . $char_before_awake->id . '.head_name') }}
											{{ Lang::get('character.character_kind.' . $char_before_awake->id . '.name') }}
										</strong>
									</a> {{ $char_before_awake->rarity_name}}
								</td>
							</tr>
							<tr>
								<td>Receipt</td>
							</tr>
							<tr>
								<td>[items]</td>
							</tr>
							<tr>
								<td>Price : </td>
							</tr>
						</table>
					</div>
		        </li>
		        @endif
		        @if ($char_after_awake)
		        <li>
		          	<input type="radio" {{ !$char_before_awake ? 'checked' : '' }} name="tabs" id="tab2" class="ipt-tab">
		          	<label for="tab2">Awake to</label>
					<div id="tab-content2" class="ipt-tab-content">
						<table class="table table-striped">
							<tr>
								<td>
									<a href="/ck/{{$char_after_awake->id}}">
										<strong>
											{{ Lang::get('character.character_kind.' . $char_after_awake->id . '.head_name') }}
											{{ Lang::get('character.character_kind.' . $char_after_awake->id . '.name') }}
										</strong>
									</a> {{ $char_after_awake->rarity_name}}
								</td>
							</tr>
							<tr>
								<td>Receipt</td>
							</tr>
							<tr>
								<td>[items]</td>
							</tr>
							<tr>
								<td>Price : </td>
							</tr>
						</table>
					</div>
		        </li>
		        @endif
			</ul>
			@endif
		</div>
	</div>
	<div id="character-types">
		<div class="row">
			<div class="col-md-6">
				<div>Sword Skill</div>
				<div>Battle Ability</div>
				<div>Leader Skill</div>
			</div>
			<div id="character-stats-chart" class="col-md-6" style="height: 400px;">
				<img src="/img/characters/10010130-large.png" width="400" height="400" style="z-index: 1; position: absolute; left:0; opacity: 0.45">
				<canvas id="character-chart" width="400" height="400" style="z-index: 3;position: absolute; left:0;"></canvas>
				
				<div id="character-chart-legend"></div>
			</div>
		</div>
		<div id="character-stats-by-types">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Type<h6>English</h6></th>
						<th><h6>Japanese</h6></th>
						<th>HP<h6>LV 1 - LV {{ $char_kind->max_level }} <i class="text-danger">(max closeness)</i></h6></th>
						<th>STR<h6>LV 1 - LV {{ $char_kind->max_level }} <i class="text-danger">(max closeness)</i></h6></th>
						<th>VIT<h6>LV 1 - LV {{ $char_kind->max_level }} <i class="text-danger">(max closeness)</i></h6></th>
						<th>INT<h6>LV 1 - LV {{ $char_kind->max_level }} <i class="text-danger">(max closeness)</i></h6></th>
						<th>MEN<h6>LV 1 - LV {{ $char_kind->max_level }} <i class="text-danger">(max closeness)</i></h6></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($chars as $chartype)
				    <tr>
				    	<td>{{ Lang::get('character.type.' . $chartype->type_id . '.name') }}</td>
						<td><kbd style="background-color: {{ Lang::get('character.type.' . $chartype->type_id . '.color') }}; font-weight: bold;">{{ $chartype->type_name }}</kbd></td>
						<td>{{ $chartype->hp_min }} - {{ $chartype->hp_max }} <i class="text-danger">({{ intval($chartype->hp_max * 1.1) }})</i></td>
						<td>{{ $chartype->str_min }} - {{ $chartype->str_max }} <i class="text-danger">({{ intval($chartype->str_max * 1.1) }})</i></td>
						<td>{{ $chartype->vit_min }} - {{ $chartype->vit_max }} <i class="text-danger">({{ intval($chartype->vit_max * 1.1) }})</i></td>
						<td>{{ $chartype->int_min }} - {{ $chartype->int_max }} <i class="text-danger">({{ intval($chartype->int_max * 1.1) }})</i></td>
						<td>{{ $chartype->men_min }} - {{ $chartype->men_max }} <i class="text-danger">({{ intval($chartype->men_max * 1.1) }})</i></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection

@section('localscript')
<script src="/js/chart.min.js"></script>
<script type="text/javascript">
var data = {
    labels: ["HP", "MEN", "VIT", "INT", "STR"],
    datasets: [
    	@foreach ($chars as $chartype)
        {
            label: "{{ Lang::get('character.type.' . $chartype->type_id . '.name') }}",
            fillColor: "rgba({{ Lang::get('character.type.' . $chartype->type_id . '.colorRGB') }},0.1)",
            strokeColor: "rgba({{ Lang::get('character.type.' . $chartype->type_id . '.colorRGB') }},1)",
            pointColor: "rgba({{ Lang::get('character.type.' . $chartype->type_id . '.colorRGB') }},1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba({{ Lang::get('character.type.' . $chartype->type_id . '.colorRGB') }},1)",
            data: [
            	{{ intval($chartype->hp_max * 1.1) }},
            	{{ intval($chartype->men_max * 1.1) }},
            	{{ intval($chartype->vit_max * 1.1) }},
            	{{ intval($chartype->int_max * 1.1) }},
            	{{ intval($chartype->str_max * 1.1) }}
            ]
        },
        @endforeach
    ]
};
var ctx = document.getElementById("character-chart").getContext("2d");
var myLineChart = new Chart(ctx).Radar(data);
</script>
@endsection