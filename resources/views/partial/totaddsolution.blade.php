<div class="list-group-item floor-solution row add-solution-container" ng-if="canaddsolution(floor.floorn)">
	<button class="btn btn-success collapsed" type="button" data-toggle="collapse" href="#f@{{floor.floorn}}addsolution"><b>Add a solution for Floor @{{floor.floorn}}</b></button>
	<button class="btn btn-info pull-right send-button" type="button" ng-disabled="!TotCtrl.newsolution.length" ng-click="TotCtrl.sendNewSolution({{ $totround }},floor.floorn)"><b>Send solution</b></button>
	<div class="list-group-item floor-solution add-solution collapse" id="f@{{floor.floorn}}addsolution">
		<div class="unitteam-container col-xs-12 col-sm-12 col-md-6">
			<div class="row new-solution"><p class="navbar-text"><strong>New Solution for Floor @{{floor.floorn}} - Select your team</strong></p></div>
			<div class="row new-solution-params">
				<div class="pull-left" role="group" aria-label="tot-adds-gamespeed">
					<p class="navbar-text"><b>Game Speed</b>
					<input id="totf-adds-param-gamespeed-1" class="hidden-input" type="radio" ng-model="TotCtrl.newsolutionparams.gamespeed" name="tot-adds-param-gamespeed" value="1">
					<label for="totf-adds-param-gamespeed-1" class="label label-choice">x1</label>
					<input id="totf-adds-param-gamespeed-2" class="hidden-input" type="radio" ng-model="TotCtrl.newsolutionparams.gamespeed" name="tot-adds-param-gamespeed" value="2">
					<label for="totf-adds-param-gamespeed-2" class="label label-choice">x2</label>
					<input id="totf-adds-param-gamespeed-3" class="hidden-input" type="radio" ng-model="TotCtrl.newsolutionparams.gamespeed" name="tot-adds-param-gamespeed" value="3">
					<label for="totf-adds-param-gamespeed-3" class="label label-choice">x3</label>
					<input id="totf-adds-param-gamespeed-5" class="hidden-input" type="radio" ng-model="TotCtrl.newsolutionparams.gamespeed" name="tot-adds-param-gamespeed" value="5">
					<label for="totf-adds-param-gamespeed-5" class="label label-choice">x5</label></p>
				</div>
				<div class="pull-right" role="group" aria-label="tot-adds-luck">
					<p class="navbar-text">
						<input id="totf-adds-param-luck" class="hidden-input" type="checkbox" name="tot-adds-param-luck" ng-model="TotCtrl.newsolutionparams.luck">
						<label for="totf-adds-param-luck" class="label label-choice">Luck</label>
						<a href="" class="tot-help">
							<i class=" glyphicon glyphicon-question-sign"></i>
							<span class="tot-tooltip">If you think your solution needs<br>few tries before success</span>
						</a>
					</p>
				</div>
			</div>
			<div class="row new-solution-params">
				<div class="input-group">
				  <span class="input-group-addon" id="basic-addon1">Note</span>
				  <input type="text" class="form-control" ng-model="TotCtrl.newsolutionparams.note" placeholder="Opt: explain how it works or any required setup" aria-describedby="basic-addon1">
				</div>
			</div>
			<ul class="unitteam unitteam-upper">
				<li class="unitteam-unit unit1" ng-click="removeUnitFromNewSolution(0)" ng-class="getRankBorder(TotCtrl.newsolution[0].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[0].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[0].trans}}"></div>
				</li>
				<li class="unitteam-unit unit2" ng-click="removeUnitFromNewSolution(1)" ng-class="getRankBorder(TotCtrl.newsolution[1].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[1].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[1].trans}}"></div>
				</li>
				<li class="unitteam-unit unit3" ng-click="removeUnitFromNewSolution(2)" ng-class="getRankBorder(TotCtrl.newsolution[2].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[2].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[2].trans}}"></div>
				</li>
				<li class="unitteam-unit unit4" ng-click="removeUnitFromNewSolution(3)" ng-class="getRankBorder(TotCtrl.newsolution[3].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[3].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[3].trans}}"></div>
				</li>
				<li class="unitteam-unit unit5" ng-click="removeUnitFromNewSolution(4)" ng-class="getRankBorder(TotCtrl.newsolution[4].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[4].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[4].trans}}"></div>
				</li>
				<li class="unitteam-unit unit6" ng-click="removeUnitFromNewSolution(5)" ng-class="getRankBorder(TotCtrl.newsolution[5].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[5].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[5].trans}}"></div>
				</li>
			</ul>
			<ul class="unitteam unitteam-lower">
				<li class="unitteam-unit unit7" ng-click="removeUnitFromNewSolution(6)" ng-class="getRankBorder(TotCtrl.newsolution[6].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[6].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[6].trans}}"></div>
				</li>
				<li class="unitteam-unit unit8" ng-click="removeUnitFromNewSolution(7)" ng-class="getRankBorder(TotCtrl.newsolution[7].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[7].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[7].trans}}"></div>
				</li>
				<li class="unitteam-unit unit9" ng-click="removeUnitFromNewSolution(8)" ng-class="getRankBorder(TotCtrl.newsolution[8].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[8].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[8].trans}}"></div>
				</li>
				<li class="unitteam-unit unit10" ng-click="removeUnitFromNewSolution(9)" ng-class="getRankBorder(TotCtrl.newsolution[9].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[9].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[9].trans}}"></div>
				</li>
				<li class="unitteam-unit unit11" ng-click="removeUnitFromNewSolution(10)" ng-class="getRankBorder(TotCtrl.newsolution[10].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[10].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[10].trans}}"></div>
				</li>
				<li class="unitteam-unit unit12" ng-click="removeUnitFromNewSolution(11)" ng-class="getRankBorder(TotCtrl.newsolution[11].rank)">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(TotCtrl.newsolution[11].unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.newsolution[11].trans}}"></div>
				</li>
			</ul>
		</div>
		<div class="unitlist-container col-xs-12 col-sm-12 col-md-6">
			<div class="row unitlistfilter">
				<div class="pull-left" role="group" aria-label="tot-filter-tribes">
					<input id="totf-adds-tribe-1" class="hidden-input" type="checkbox" name="tot-adds-tribe1" ng-model="TotCtrl.unitparams.tribe1">
					<label for="totf-adds-tribe-1" class="label label-choice">Human</label>
					<input id="totf-adds-tribe-2" class="hidden-input" type="checkbox" name="tot-adds-tribe2" ng-model="TotCtrl.unitparams.tribe2">
					<label for="totf-adds-tribe-2" class="label label-choice">Elf</label>
					<input id="totf-adds-tribe-3" class="hidden-input" type="checkbox" name="tot-adds-tribe3" ng-model="TotCtrl.unitparams.tribe3">
					<label for="totf-adds-tribe-3" class="label label-choice">Undead</label>
					<input id="totf-adds-tribe-4" class="hidden-input" type="checkbox" name="tot-adds-tribe4" ng-model="TotCtrl.unitparams.tribe4">
					<label for="totf-adds-tribe-4" class="label label-choice">Orc</label>
				</div>
				<div class="pull-right" role="group" aria-label="tot-filter-damagetype">
					<input id="totf-adds-physical" class="hidden-input" type="checkbox" name="tot-adds-physical" ng-model="TotCtrl.unitparams.physical">
					<label for="totf-adds-physical" class="label label-choice">Physical</label>
					<input id="totf-adds-magical" class="hidden-input" type="checkbox" name="tot-adds-magical" ng-model="TotCtrl.unitparams.magical">
					<label for="totf-adds-magical" class="label label-choice">Magical</label>
				</div>
			</div>
			<div class="row unitlistfilter">
				<div class="pull-left" role="group" aria-label="tot-filter-rank">
					<input id="totf-adds-rank-1star" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxrank" name="tot-adds-rank" value="1">
					<label for="totf-adds-rank-1star" class="label label-choice">1 <i class="glyphicon glyphicon-star"></i></label>
					<input id="totf-adds-rank-2star" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxrank" name="tot-adds-rank" value="2">
					<label for="totf-adds-rank-2star" class="label label-choice">2 <i class="glyphicon glyphicon-star"></i></label>
					<input id="totf-adds-rank-3star" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxrank" name="tot-adds-rank" value="3">
					<label for="totf-adds-rank-3star" class="label label-choice">3 <i class="glyphicon glyphicon-star"></i></label>
					<input id="totf-adds-rank-4star" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxrank" name="tot-adds-rank" value="4">
					<label for="totf-adds-rank-4star" class="label label-choice">4 <i class="glyphicon glyphicon-star"></i></label>
					<input id="totf-adds-rank-5star" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxrank" name="tot-adds-rank" value="5">
					<label for="totf-adds-rank-5star" class="label label-choice">5 <i class="glyphicon glyphicon-star"></i></label>
					<input id="totf-adds-rank-6star" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxrank" name="tot-adds-rank" value="6">
					<label for="totf-adds-rank-6star" class="label label-choice">6 <i class="glyphicon glyphicon-star"></i></label>
				</div>
				<div class="pull-right" role="group" aria-label="tot-filter-airground">
					<input id="totf-adds-ground" class="hidden-input" type="checkbox" name="tot-adds-ground" ng-model="TotCtrl.unitparams.ground">
					<label for="totf-adds-ground" class="label label-choice">Ground</label>
					<input id="totf-adds-air" class="hidden-input" type="checkbox" name="tot-adds-air" ng-model="TotCtrl.unitparams.air">
					<label for="totf-adds-air" class="label label-choice">Air</label>
				</div>
			</div>
			<div class="row unitlistfilter">
				<div class="pull-left" role="group" aria-label="tot-filter-rank">
					<input id="totf-adds-evol-base" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxevol" name="tot-adds-evol" value="1">
					<label for="totf-adds-evol-base" class="label label-choice">Base</label>
					<input id="totf-adds-evol-senior" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxevol" name="tot-adds-evol" value="2">
					<label for="totf-adds-evol-senior" class="label label-choice">Senior</label>
					<input id="totf-adds-evol-trans1" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxevol" name="tot-adds-evol" value="3">
					<label for="totf-adds-evol-trans1" class="label label-choice">Trans 1</label>
					<input id="totf-adds-evol-trans2" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxevol" name="tot-adds-evol" value="4">
					<label for="totf-adds-evol-trans2" class="label label-choice">Trans 2</label>
					<input id="totf-adds-evol-trans3" class="hidden-input" type="radio" ng-model="TotCtrl.unitparams.maxevol" name="tot-adds-evol" value="5">
					<label for="totf-adds-evol-trans3" class="label label-choice">Trans 3</label>
				</div>
			</div>
			<ul class="unitteam allunitlist">
				<li class="unitteam-unit" ng-repeat="unit in unitBook | unitBookFilter:TotCtrl.unitparams | orderBy:['-rank','-tribe','-unitId']" ng-class="getRankBorder(unit.rank)" ng-click="addUnitToNewSolution(unit.unitId,unit.rank,(TotCtrl.unitparams.maxevol - 2))">
					<div class="unitbg"></div>
					<div class="uniticon icon-unit60-@{{pad(unit.unitId,4)}}"></div>
					<div class="unittrans icon-misc-trans@{{TotCtrl.unitparams.maxevol - 2}}"></div>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="list-group-item floor-solution row add-solution-container" ng-if="!canaddsolution(floor.floorn)">
	You need to pass lower floors first to add a solution on this floor.
</div>