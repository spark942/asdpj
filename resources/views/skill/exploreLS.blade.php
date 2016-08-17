@extends('master')

@section('customhead')
<title>Leader Skills</title>
@endsection

@section('content')
<section id="LeaderSkills" ng-controller="LeaderSkillListController as LeaderSkillListCtrl">
  <div>
    <h3>Filters</h3>
    <div><p>Coming soon</p>
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
            <th><a href="" ng-click="reverse=!reverse;order('name', reverse);">Condition</a></th>
            <th><a href="" ng-click="reverse=!reverse;order('description', reverse);">Description</a></th>
            <th>Used by</th>
          </tr>
        </thead>
        <tbody>
          <tr dir-paginate="leaderSkill in LeaderSkillListCtrl.leaderSkillList | itemsPerPage: ckByPage | orderBy:predicate:reverse"
            [current-page=""]
            [pagination-id=""]
            [total-items=""]>
            <td>@{{ leaderSkill.name}}</td>
            <td>@{{ leaderSkill.description }}</td>
            <td>
              <span ng-if="leaderSkill.chars.length == 1" ng-repeat="char in leaderSkill.chars">
                <a href="/ck/@{{ char.id }}" role="button">
                  <div class="img-circle icon-char40-@{{ char.imgid }}-thum leaderskill-char-mini">
                    <span class="rarity">@{{ char.rarity_num }}★</span>
                  </div>
                </a>
                <a href="/ck/@{{ char.id }}" role="button"><span style="margin-bottom: 10px;">@{{ characterService.langCharHeadName(char.id) }} @{{ characterService.langCharName(char.id) }}</span></a>
              </span>
              <span ng-if="leaderSkill.chars.length != 1" ng-repeat="char in leaderSkill.chars">
                <a href="/ck/@{{ char.id }}" role="button">
                  <div class="img-circle icon-char40-@{{ char.imgid }}-thum leaderskill-char-mini">
                    <span class="rarity">@{{ char.rarity_num }}★</span>
                  </div>
                </a>
              </span>
            </td>
            <td></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th><a href="" ng-click="reverse=!reverse;order('name', reverse);">Condition</a></th>
            <th><a href="" ng-click="reverse=!reverse;order('description', reverse);">Description</a></th>
            <th>Used by</th>
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
    </div>
  </div>
</section>

@endsection

@section('prelocalscript')
{!! Html::script('js/data/'.$ck_data_js_file) !!}
@endsection

@section('localscript')
<script type="text/javascript">
  var saocrLeaderSkilllist = {!! $leader_skills_json !!};
</script>
@endsection