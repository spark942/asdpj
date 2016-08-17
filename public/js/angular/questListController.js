angular.module('saocr')
  .controller('QuestListController', ['$scope', '$filter', 'questService',
    function($scope, $filter, questService) {
    $scope.questService = questService;
    var qlCtrl = this;

    qlCtrl.questWorlds   = saocrQuestWorlds   || [];
    qlCtrl.questChapters = saocrQuestChapters || [];
    qlCtrl.questGroups   = saocrQuestGroups   || [];
    qlCtrl.quests        = saocrQuests        || [];


    $scope.langCharHeadName = function(charKindID) {
      return qlCtrl.charLocale.character_kind[charKindID].head_name;
    };
    $scope.langCharName = function(charKindID) {
      return qlCtrl.charLocale.character_kind[charKindID].name;
    };
    $scope.langCharElement = function(elementID) {
      return qlCtrl.charLocale.elemental[elementID];
    };
    $scope.langWeaponCategory = function(weaponCategoryID) {
      return qlCtrl.wcLocale[weaponCategoryID].name;
    };
    $scope.langCharTypeName = function(typeID) {
      return qlCtrl.charLocale.type[typeID].name;
    };
    $scope.langCharTypeColor = function(typeID) {
      return qlCtrl.charLocale.type[typeID].color;
    };


    qlCtrl.addTodo = function() {
      qlCtrl.todos.push({text:qlCtrl.todoText, done:false});
      qlCtrl.todoText = '';
    };

  }]);