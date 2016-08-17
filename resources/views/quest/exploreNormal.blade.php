@extends('master')

@section('customhead')
<title>Normal Quests</title>
@endsection

@section('content')
<section id="Quests" ng-controller="QuestListController as QuestListCtrl">
  <div>
    <br>

    <div class="panel panel-default">
      <div class="panel-heading"><strong>Normal Quests</strong></div>
      <div class="panel-body"
          ng-repeat="qWorld in QuestListCtrl.questWorlds | reverse">
        <div
          ng-repeat="qChap in QuestListCtrl.questChapters | reverse"
          ng-if="qChap.parentWorldID == qWorld.id">
          <div
            ng-repeat="qGroup in  QuestListCtrl.questGroups | reverse"
            ng-if="qGroup.questChapterID == qChap.id">
            <table class="table table-condensed table-hover">
              <thead>
                <tr>
                  <th colspan="9" ng-cloak>
                    @{{ qWorld.name }} - @{{ questService.dQWStoryName(qWorld.id) }} |
                    @{{ questService.dQCName(qChap.id) }}<br>
                    Quest @{{ qGroup.groupOrder }}: @{{ questService.dQGName(qGroup.id) }} - @{{ questService.dQGDescription(qGroup.id) }}
                  </th>
                </tr>
                <tr>
                  <th>Stage</th>
                  <th class="text-center" style="width: 80px;">Battle</th>
                  <th class="text-center" style="width: 70px;">AP</th>
                  <th colspan="2" style="text-align: left;">Gold</th>
                  <th colspan="2" style="text-align: center;">Player EXP</th>
                  <th colspan="2" style="text-align: center;">Character EXP</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  ng-repeat="quest in QuestListCtrl.quests | reverse"
                  ng-if="quest.questGroupID == qGroup.id">
                  <td>
                    @{{ questService.dQName(quest.id) }}
                  </td>
                  <td class="text-center">@{{ quest.battleNum }}</td>
                  <td class="text-center">@{{ quest.requiredStamina }}</td>
                  <td style="text-align: right; width: 70px;">@{{ quest.gainedMoney | number }} G</td>
                  <td style="text-align: right; width: 80px;"><span class="quest-number-per-ap">@{{ quest.gainedMoney/quest.requiredStamina | number:2 }} /AP</span></td>
                  <td style="text-align: right; width: 45px;">@{{ quest.gainedCharacterExp | number }}</td>
                  <td style="text-align: right; width: 65px;"><span class="quest-number-per-ap">@{{ quest.gainedCharacterExp/quest.requiredStamina | number:2 }} /AP</span></td>
                  <td style="text-align: right; width: 70px;">@{{ quest.gainedPlayerExp | number }}</td>
                  <td style="text-align: right; width: 80px;"><span class="quest-number-per-ap">@{{ quest.gainedPlayerExp/quest.requiredStamina | number:2 }} /AP</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
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