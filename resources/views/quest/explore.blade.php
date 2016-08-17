@extends('master')

@section('customhead')
<title>Quests</title>
@endsection

@section('content')
<section id="Quests" ng-controller="QuestListController as QuestListCtrl">
  <div>
    <br>
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading"><a href="/quests/normal"><strong>Normal Quests</strong></a></div>
          <div class="panel-body">
            <table
              class="table table-condensed table-hover"
              ng-repeat="qWorld in QuestListCtrl.questWorlds | reverse">
              <thead>
                <tr>
                  <th ng-cloak>@{{ qWorld.name }} - @{{ questService.dQWStoryName(qWorld.id) }}</th>
                </tr>
              </thead>
              <tbody
                ng-repeat="qChap in QuestListCtrl.questChapters | reverse"
                ng-if="qChap.parentWorldID == qWorld.id">
                <tr
                  ng-repeat="qGroup in  QuestListCtrl.questGroups | reverse"
                  ng-if="qGroup.questChapterID == qChap.id">
                  <td style="border-top: none;" ng-cloak>
                    <div class="quest-group-label">
                      @{{ questService.dQCName(qChap.id) }}<br>
                      Quest @{{ qGroup.groupOrder }}: @{{ questService.dQGName(qGroup.id) }}
                    </div>
                    <!-- <ul class="list-group" style="margin-top: 4px; margin-bottom: 0;">
                      <li class="list-group-item quest-li"
                        ng-repeat="quest in QuestListCtrl.quests | reverse"
                        ng-if="quest.questGroupID == qGroup.id">
                        @{{ questService.dQName(quest.id) }}<br>
                      </li>
                    </ul> -->
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <h4>Extra Quests</h4>
        <table class="table table-condensed table-striped table-hover">
      a
        </table>
      </div>
      <div class="col-md-4">
        <h4>Daily Quests &amp; Special Quests</h4>
        <table class="table table-condensed table-striped table-hover">
      a
        </table>
      </div>
    </div>
  </div>

</section>

@endsection

@section('prelocalscript')
{!! Html::script('js/data/'.$qw_data_js_file) !!}
{!! Html::script('js/data/'.$qc_data_js_file) !!}
{!! Html::script('js/data/'.$qg_data_js_file) !!}
{!! Html::script('js/data/'.$q_data_js_file) !!}
@endsection

@section('localscript')
<script type="text/javascript">
  var saocrQuestWorlds   = {!! $quest_worlds_json !!};
  var saocrQuestChapters = {!! $quest_chapters_json !!};
  var saocrQuestGroups   = {!! $quest_groups_json !!};
  var saocrQuests        = {!! $quests_json !!};
</script>
@endsection