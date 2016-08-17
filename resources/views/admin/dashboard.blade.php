@extends('master')

@section('customhead')
	{!! Html::style('css/user-profile.css') !!}
	<style type="text/css">
	body { padding-bottom: 0px;}
	footer { display: none;}
	</style>
@endsection

@section('content')
<section id="user-info">
	<div class="container" ng-controller="AdminController as AdmCtrl">
		<h2 ng-cloak>Welcome to admin dashboard, {{Auth::user()->name}}</h2>
		<div class="row">
			<div class="col-md-3">
				<nav>
					<ul class="list-group">
						<li class="list-group-item"
							style="cursor: pointer;"
							ng-repeat="category in AdmCtrl.categories"
							ng-click="categorySelect(category.name)"
							ng-class="{'list-group-item-info': category.selected,'disabled': category.right == 0}"
						 	ng-cloak>
							<span ng-class="{
								'label label-danger': category.right == 0,
								'label label-warning': category.right == 1,
								'label label-success': category.right >= 2,
								}">&nbsp;</span> @{{ category.publicName }}</li>
					</ul>
					<ul class="list-group">
						<li class="list-group-item"><span class="label label-success">&nbsp;</span> Can manage</li>
						<li class="list-group-item"><span class="label label-warning">&nbsp;</span> Can see</li>
						<li class="list-group-item"><span class="label label-danger">&nbsp;</span> Not authorized</li>
					</ul>
					<div id="helps" ng-repeat="category in AdmCtrl.categories">
						<div class="panel panel-default"
							ng-if="category.name == 'dbtranslation' && category.selected">
							<div class="panel-heading">
							    <h3 class="panel-title" ng-cloak>@{{ category.publicName }} HELP</h3>
						  	</div>
						  	<div>
						  		<ul class="list-group">
						  			<li class="list-group-item">
						  				Database Overview : zone where you define which file is used for each table. (The one you select is automaticly set as the file to use)
					  				</li>
						  			<li class="list-group-item">
						  				<b>IMPORTANT : Only one person on the same table at the same time.</b><br/>
						  				Table : Choose the table you want to edit, and select a datafile you want to start with. (prefer use the last edited one)<br/>
						  				If a field is not defined, the default value is used.
						  			</li>
					  			</ul>
						  	</div>
						</div>
					</div>
				</nav>
			</div>
			<div class="col-md-9">
				<section class="panel panel-default"
					ng-repeat="category in AdmCtrl.categories"
					ng-if="category.selected">
					<div class="panel-heading">
					    <h3 class="panel-title" ng-cloak>@{{ category.publicName }}</h3>
				  	</div>

					<!-- OVERVIEW -->
					<div ng-if="category.name == 'overview'">
						<div class="panel-body">
						aa
				  		</div>
					</div>
					<!-- DATABASE TRANSLATION -->
					<div ng-if="category.name == 'dbtranslation'">
				    	<ul class="nav nav-tabs">
				    		<li role="presentation"
				    			ng-repeat="(kdbt, dbtable) in category.tables"
				    			ng-if="kdbt == 0"
				    			ng-click="dbtableSelect(dbtable.name)"
				    			ng-class="{'list-group-item-info': dbtable.selected}">
			    				<a href="#">@{{ dbtable.publicName }}</a>
		    				</li>
				    		<li role="presentation" class="dropdown">
				    		    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
				    		    	Table
				    		    	<span
				    		    		ng-repeat="(kdbt, dbtable) in category.tables"
				    				    ng-if="kdbt != 0"
				    				    ng-show="dbtable.selected">: @{{ dbtable.publicName }}</span>
				    		      	<span class="caret"></span>
				    		    </a>
				    		    <ul class="dropdown-menu" role="menu">
				    		    	<li
				    		    		ng-repeat="(kdbt, dbtable) in category.tables"
				    				    ng-if="kdbt != 0"
				    				    ng-click="dbtableSelect(dbtable.name)"
				    				    ng-class="{'list-group-item-info': dbtable.selected}">
			    				    	<a href="#">@{{ dbtable.publicName }}</a>
		    				    	</li>
				    		    </ul>
			    		  	</li>
			    		  	<!-- <li ng-repeat="(kdbt, dbtable) in category.tables"
		    				    ng-if="kdbt != 0 && dbtable.selected" ng-init="AmdCtrl.loadDataFiles()">
			    		  		<a href="#">Select Data file</a>
			    		  	</li> -->
				    	</ul>
				    	<div class="panel" style="margin-bottom: 0;">
				    		<div class="panel-body" 
				    			ng-repeat="(kdbt, dbtable) in category.tables"
				    			ng-if="dbtable.selected">
				    			<div ng-if="kdbt == 0" ng-init="AdmCtrl.initFilesUsedForTables()">
				    				Database translation files :
				    				<table class="table">
				    					<thead>
				    						<tr>
				    							<th>Table name</th>
				    							<th>File used (Website Server Date - Notes by Editor)</th>
				    						</tr>
				    					</thead>
				    					<tbody>
				    						<tr ng-repeat="(kdbtOverview, dbtableOverview) in category.tables"
				    							ng-if="kdbtOverview != 0"
				    							ng-init="AdmCtrl.loadDataFileList(dbtableOverview.name)">
				    							<td>@{{ dbtableOverview.name }}</td>
				    							<td>
													<select id="selectdatafile"
														class="form-control"
														ng-model="dbtableOverview.fileUsed"
														ng-change="AdmCtrl.updateFileUsedByTable(dbtableOverview.name, dbtableOverview.fileUsed)"
														ng-options="o.id as (o.date + ' - ' + o.notes + ' by ' + o.adminname) for o in AdmCtrl.datafilelist[dbtableOverview.name]">
													</select>
				    							</td>
				    						</tr>
				    					</tbody>
				    				</table>
				    			</div>
				    			<div ng-if="kdbt != 0" ng-init="AdmCtrl.loadDbtable(dbtable.name)">
				    				<div class="row form-group" ng-init="AdmCtrl.loadDataFileList(dbtable.name)">
				    					<div class="col-md-3">
				    						<label for="selectdatafile">Select Data file </label>
			    						</div>
				    					<div class="col-md-9">
				    						<select id="selectdatafile"
				    							class="form-control"
				    							ng-model="datafile"
				    							ng-change="AdmCtrl.updateDbData(datafile, dbtable.name)"
    											ng-options="(dbtable.name + '_' + o.date + '.js') as (o.date + ' - ' + o.notes + ' by ' + o.adminname) for o in AdmCtrl.datafilelist[dbtable.name]">
				    						</select>
				    					</div>
				    				</div>
				    				<div class="row form-group">
				    					<div class="col-md-2">
				    						<a class="btn btn-default" href="#" role="button"
				    							ng-click="AdmCtrl.saveDbtable(dbtable.name)">Save</a>
				    					</div>
				    					<div class="col-md-1">
				    						<label for="savenote">Note</label>
				    					</div>
				    					<div class="col-md-9">
				    						<input
				    							id="savenote"
				    							class="form-control"
				    							type="text"
				    							ng-model="AdmCtrl.dbtablenote"
				    							placeholder="comment about what you modified">
				    					</div>
				    				</div>
				    				<div ng-if="dbtable.name == 'table_character_kind'">
				    					<!-- <p>Column legend</p>
				    					<ul class="list-unstyled">
				    						<li>/1 : Game World</li>
				    						<li>/2 : Limited Type</li>
				    					</ul> -->
				    					<table class="table table-condensed table-db">
				    						<thead style="display: block;">
				    							<tr>
				    								<th colspan="3">Original data</th>
				    								<th colspan="2">Displayed data</th>
				    							</tr>
				    							<tr>
				    								<th class="db-col-ori-id">ID#</th>
				    								<th class="db-col-ori-hn">Headname</th>
				    								<th class="db-col-ori-n">name</th>
				    								<th class="db-col-dsp-hn">Headname</th>
				    								<th class="db-col-dsp-n">name</th>
				    								<th class="db-col-dsp-s">World</th>
				    								<!-- <th class="db-col-dsp-s">/2</th> -->
				    							</tr>
				    						</thead>
				    						<tbody style="max-height: calc(100vh - 460px); overflow-y: scroll; display: block;">
				    							<tr ng-repeat="charkind in AdmCtrl.dbtable" ng-cloak>
				    								<td class="db-col-ori-id"><a ng-href="/ck/@{{ charkind.originalID }}" target="_blank">@{{ charkind.originalID }}</a></td>
				    								<td class="db-col-ori-hn">@{{ charkind.originalHeadName }}</td>
				    								<td class="db-col-ori-n">@{{ charkind.originalName }}</td>
				    								<td class="db-col-dsp-hn">
				    									<input type="text"
				    										ng-if="charkind.originalHeadName != ''"
				    										ng-model="charkind.displayedHeadName"
				    										ng-class="{'isempty': charkind.displayedHeadName == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    								<td class="db-col-dsp-n">
				    									<input type="text"
				    										ng-model="charkind.displayedName"
				    										ng-class="{'isempty': charkind.displayedName == ''}"
				    										placeholder="Code: @{{ charkind.originalCodeName }}"
				    										style="width: 100%;">
			    									</td>
			    									<td class="db-col-dsp-s">
			    										<select
			    											ng-model="charkind.displayedWorld"
			    											ng-options="o as o for o in charworlds"></select>
			    									</td>
			    									<!-- <td class="db-col-dsp-s">
			    										<select
			    											ng-model="charkind.displayedLimitedType"
			    											ng-options="charlimitedtype.name for charlimitedtype in charlimitedtypes"></select>
			    									</td> -->
				    							</tr>
				    						</tbody>
				    					</table>
				    				</div>
				    				<div ng-if="dbtable.name == 'table_quest_worlds'">
				    					<table class="table table-condensed table-db">
				    						<thead style="display: block;">
				    							<tr>
				    								<th colspan="4">Original data</th>
				    								<th colspan="2">Displayed data</th>
				    							</tr>
				    							<tr>
				    								<th class="db-col-ori-sid">ID#</th>
				    								<th class="db-col-ori-acr">Acr</th>
				    								<th class="db-col-ori-n">Story Name</th>
				    								<th class="db-col-ori-desc">Story Description</th>
				    								<th class="db-col-dsp-n">Story Name</th>
				    								<th class="db-col-dsp-desc">Story Description</th>
				    								<!-- <th class="db-col-dsp-s">/2</th> -->
				    							</tr>
				    						</thead>
				    						<tbody style="max-height: calc(100vh - 460px); overflow-y: scroll; display: block;">
				    							<tr ng-repeat="qworld in AdmCtrl.dbtable" ng-cloak>
				    								<td class="db-col-ori-sid">@{{ qworld.originalID }}</td>
				    								<td class="db-col-ori-acr">@{{ qworld.originalAcronymName }}</td>
				    								<td class="db-col-ori-n">@{{ qworld.originalStoryName }}</td>
				    								<td class="db-col-ori-desc">@{{ qworld.originalStoryDescription }}</td>
				    								<td class="db-col-dsp-n">
				    									<input type="text"
				    										ng-if="qworld.originalStoryName != ''"
				    										ng-model="qworld.displayedStoryName"
				    										ng-class="{'isempty': qworld.displayedStoryName == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    								<td class="db-col-dsp-desc">
				    									<input type="text"
				    										ng-if="qworld.originalStoryDescription != ''"
				    										ng-model="qworld.displayedStoryDescription"
				    										ng-class="{'isempty': qworld.displayedStoryDescription == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    							</tr>
				    						</tbody>
				    					</table>
				    				</div>
				    				<div ng-if="dbtable.name == 'table_quest_chapters'">
				    					<table class="table table-condensed table-db">
				    						<thead style="display: block;">
				    							<tr>
				    								<th colspan="3">Original data</th>
				    								<th colspan="2">Displayed data</th>
				    							</tr>
				    							<tr>
				    								<th class="db-col-ori-sid">ID#</th>
				    								<th class="db-col-ori-sn">Name</th>
				    								<th class="db-col-ori-xxldesc">Description</th>
				    								<th class="db-col-dsp-sn">Name</th>
				    								<th class="db-col-dsp-xxldesc">Description</th>
				    								<!-- <th class="db-col-dsp-s">/2</th> -->
				    							</tr>
				    						</thead>
				    						<tbody style="max-height: calc(100vh - 460px); overflow-y: scroll; display: block;">
				    							<tr ng-repeat="qchap in AdmCtrl.dbtable" ng-cloak>
				    								<td class="db-col-ori-sid">@{{ qchap.originalID }}</td>
				    								<td class="db-col-ori-sn">@{{ qchap.originalName }}</td>
				    								<td class="db-col-ori-xxldesc">@{{ qchap.originalDescription }}</td>
				    								<td class="db-col-dsp-sn">
				    									<input type="text"
				    										ng-if="qchap.originalName != ''"
				    										ng-model="qchap.displayedName"
				    										ng-class="{'isempty': qchap.displayedName == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    								<td class="db-col-dsp-xxldesc">
				    									<input type="text"
				    										ng-if="qchap.originalDescription != ''"
				    										ng-model="qchap.displayedDescription"
				    										ng-class="{'isempty': qchap.displayedDescription == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    							</tr>
				    						</tbody>
				    					</table>
				    				</div>
				    				<div ng-if="dbtable.name == 'table_quest_groups'">
				    					<table class="table table-condensed table-db">
				    						<thead style="display: block;">
				    							<tr>
				    								<th colspan="3">Original data</th>
				    								<th colspan="2">Displayed data</th>
				    							</tr>
				    							<tr>
				    								<th class="db-col-ori-mid">ID#</th>
				    								<th class="db-col-ori-sn">Name</th>
				    								<th class="db-col-ori-xxldesc">Description</th>
				    								<th class="db-col-dsp-sn">Name</th>
				    								<th class="db-col-dsp-xxldesc">Description</th>
				    								<!-- <th class="db-col-dsp-s">/2</th> -->
				    							</tr>
				    						</thead>
				    						<tbody style="max-height: calc(100vh - 460px); overflow-y: scroll; display: block;">
				    							<tr ng-repeat="qgroup in AdmCtrl.dbtable" ng-cloak>
				    								<td class="db-col-dsp-mid">@{{ qgroup.originalID }}</td>
				    								<td class="db-col-dsp-sn">@{{ qgroup.originalName }}</td>
				    								<td class="db-col-dsp-xxldesc">@{{ qgroup.originalDescription }}</td>
				    								<td class="db-col-dsp-sn">
				    									<input type="text"
				    										ng-if="qgroup.originalName != ''"
				    										ng-model="qgroup.displayedName"
				    										ng-class="{'isempty': qgroup.displayedName == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    								<td class="db-col-dsp-xxldesc">
				    									<input type="text"
				    										ng-if="qgroup.originalDescription != ''"
				    										ng-model="qgroup.displayedDescription"
				    										ng-class="{'isempty': qgroup.displayedDescription == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    							</tr>
				    						</tbody>
				    					</table>
				    				</div>
				    				<div ng-if="dbtable.name == 'table_quests'">
				    					<table class="table table-condensed table-db">
				    						<thead style="display: block;">
				    							<tr>
				    								<th colspan="3">Original data</th>
				    								<th colspan="2">Displayed data</th>
				    							</tr>
				    							<tr>
				    								<th class="db-col-ori-mid">ID#</th>
				    								<th class="db-col-ori-sn">Name</th>
				    								<th class="db-col-ori-xxldesc">Description</th>
				    								<th class="db-col-dsp-sn">Name</th>
				    								<th class="db-col-dsp-xxldesc">Description</th>
				    								<!-- <th class="db-col-dsp-s">/2</th> -->
				    							</tr>
				    						</thead>
				    						<tbody style="max-height: calc(100vh - 460px); overflow-y: scroll; display: block;">
				    							<tr ng-repeat="quest in AdmCtrl.dbtable" ng-cloak>
				    								<td class="db-col-dsp-mid">@{{ quest.originalID }}</td>
				    								<td class="db-col-dsp-sn">@{{ quest.originalName }}</td>
				    								<td class="db-col-dsp-xxldesc">@{{ quest.originalDescription }}</td>
				    								<td class="db-col-dsp-sn">
				    									<input type="text"
				    										ng-if="quest.originalName != ''"
				    										ng-model="quest.displayedName"
				    										ng-class="{'isempty': quest.displayedName == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    								<td class="db-col-dsp-xxldesc">
				    									<input type="text"
				    										ng-if="quest.originalDescription != ''"
				    										ng-model="quest.displayedDescription"
				    										ng-class="{'isempty': quest.displayedDescription == ''}"
				    										placeholder="NOT DEFINED"
				    										style="width: 100%;">
			    									</td>
				    							</tr>
				    						</tbody>
				    					</table>
				    				</div>
				    			</div>

				    		</div>
				    	</div>
					</div>
					<!-- EVENTS -->
					<div ng-if="category.name == 'events'"></div>
					<!-- ARTICLES -->
					<div ng-if="category.name == 'articles'"></div>

				</section>

			</div>
		</div>
	</div>
</section>


<!-- <section id="user-followers">
	<h4>My Followers</h4>
</section> -->
@endsection

@section('localscript')
<script type="text/javascript">
	var adminCategoryRights = {!! $admin_category_rights_json !!};
</script>
{!! Html::script('js/angular/adminController.js?v=' . env('JS_VERSION')) !!}
<div id="dbfile">
	
</div>
<script type="text/javascript">
	
</script>
@endsection