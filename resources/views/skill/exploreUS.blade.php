@extends('master')
@section('maps')
<section id="book-map">
	<div id="map-canvas"></div>
	<div id="hide-credit"></div>
</section>
@endsection
@section('content')
<section id="Characters" ng-controller="CharacterListController as CharListCtrl">
  <div>
    <h3>Filters</h3>
    <div><p>Coming soon</p>
      <table>
        <tr><td></td></tr>
      </table>
    </div>
  </div>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <td colspan="9">
          <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default" ng-click="ckByPage = 10;" ng-class="{'btn-primary':(ckByPage == 10 )}">10</button>
            <button type="button" class="btn btn-default" ng-click="ckByPage = 25;" ng-class="{'btn-primary':(ckByPage == 25 )}">25</button>
            <button type="button" class="btn btn-default" ng-click="ckByPage = 50;" ng-class="{'btn-primary':(ckByPage == 50 )}">50</button>
            <button type="button" class="btn btn-default" ng-click="ckByPage = 100;" ng-class="{'btn-primary':(ckByPage == 100 )}">100</button>
          </div>
          <dir-pagination-controls class="pagination-ctrl"
            [max-size=""]
            [direction-links=""]
            [boundary-links=""]
            [on-page-change=""]
            [pagination-id=""]
            [template-url=""]>
          </dir-pagination-controls>
        </td>
      </tr>
      <tr>
        <th>Icon</th>
        <th></th>
        <th><a href="" ng-click="reverse=!reverse;order('name', reverse);">Name</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('rarityNum', reverse);">Rarity</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('cost', reverse);">Cost</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('elementalID', reverse);">Element</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('weaponCategoryID', reverse);">Weapon Type</a></th>
        <th>Types</th>
        <th>BA Effects</th>
      </tr>
    </thead>
    <tbody>
      <tr dir-paginate="charKindData in CharListCtrl.charList | itemsPerPage: ckByPage | orderBy:predicate:reverse"
        [current-page=""]
        [pagination-id=""]
        [total-items=""]>
        <td class="img"><div class="img-circle icon-char40-@{{ charKindData.imgid }}-thum"></div></td>
        <td class="text-right"><a href="/character/@{{ charKindData.id }}">@{{ langCharHeadName(charKindData.id) }}</a></td>
        <td><a href="/character/@{{ charKindData.id }}">@{{ langCharName(charKindData.id) }}</a></td>
        <td>@{{ charKindData.rarityNum}} ★</td>
        <td>@{{ charKindData.cost }}</td>
        <td>@{{ langCharElement(charKindData.elementalID) }} @{{ charKindData.elementalName }}</td>
        <td>@{{ langWeaponCategory(charKindData.weaponCategoryID) }}</td>
        <td>
          <a
            ng-repeat="type in charKindData.types"
            href="#" role="button"
            class="btn btn-xs disabled"
            style="color: #fff; background-color: @{{ langCharTypeColor(type.type_id) }}; margin-right: 3px;">@{{ langCharTypeName(type.type_id) }}</a>
        </td>
        <td></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th>Icon</th>
        <th></th>
        <th><a href="" ng-click="reverse=!reverse;order('name', reverse);">Name</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('rarityNum', reverse);">Rarity</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('cost', reverse);">Cost</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('elementalID', reverse);">Element</a></th>
        <th><a href="" ng-click="reverse=!reverse;order('weaponCategoryID', reverse);">Weapon Type</a></th>
        <th>Types</th>
        <th>BA Effects</th>
      </tr>
      <tr>
        <td colspan="9">
          <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default" ng-click="ckByPage = 10;" ng-class="{'btn-primary':(ckByPage == 10 )}">10</button>
            <button type="button" class="btn btn-default" ng-click="ckByPage = 25;" ng-class="{'btn-primary':(ckByPage == 25 )}">25</button>
            <button type="button" class="btn btn-default" ng-click="ckByPage = 50;" ng-class="{'btn-primary':(ckByPage == 50 )}">50</button>
            <button type="button" class="btn btn-default" ng-click="ckByPage = 100;" ng-class="{'btn-primary':(ckByPage == 100 )}">100</button>
          </div>
          <dir-pagination-controls class="pagination-ctrl"
            [max-size=""]
            [direction-links=""]
            [boundary-links=""]
            [on-page-change=""]
            [pagination-id=""]
            [template-url=""]>
          </dir-pagination-controls>
        </td>
      </tr>
    </tfoot>
  </table>
</section>

@endsection

@section('localscript')
<script type="text/javascript">
  var saocrCharlist = {!! $char_kinds_json !!};
  var saocrCharLocalization = {!! $char_localization !!};
  var saocrWeaponCategoriesLocalization = {!! $weapon_categories_localization !!};
</script>
@endsection