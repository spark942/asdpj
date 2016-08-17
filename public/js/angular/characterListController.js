angular.module('saocr')
  .controller('CharacterListController', ['$scope', '$filter', 'characterService', 'weaponService',
    function($scope, $filter, characterService, weaponService) {
    $scope.characterService = characterService;
    $scope.weaponService = weaponService;

    var clCtrl = this;

    clCtrl.charList   = saocrCharlist || [];

    $scope.clOptions = {};
    $scope.ckByPage = 10;
    $scope.predicate = '-rarity_num';
    $scope.reverse   = false;
    $scope.order = function(predicate, reverse) {
      clCtrl.charList = $filter('orderBy')(clCtrl.charList, predicate, reverse);
    };

    clCtrl.initOptions = function() {
      $scope.clOptions['rarity'] = {
        '1' : true,
        '2' : true,
        '3' : true,
        '4' : true,
        '5' : true,
        '6' : true,
      };
      $scope.clOptions['elemental'] = {
        '1' : true,
        '2' : true,
        '3' : true,
      };
      $scope.clOptions['sextype'] = {
        '1' : true,
        '2' : true,
      };
      $scope.clOptions['weapontype'] = {
        '1' : true,
        '2' : true,
        '3' : true,
        '4' : true,
        '5' : true,
        '6' : true,
        '7' : true,
        '8' : true,
        '9' : true,
        '10' : true,
        '11' : true,
        '12' : true,
      };
    };

    $scope.langCharHeadName = function(charKindID) {
      return clCtrl.charLocale.character_kind[charKindID].head_name;
    };
    $scope.langCharName = function(charKindID) {
      return clCtrl.charLocale.character_kind[charKindID].name;
    };
    $scope.langCharElement = function(elementID) {
      return clCtrl.charLocale.elemental[elementID];
    };
    $scope.langWeaponCategory = function(weaponCategoryID) {
      return clCtrl.wcLocale[weaponCategoryID].name;
    };
    $scope.langCharTypeName = function(typeID) {
      return clCtrl.charLocale.type[typeID].name;
    };
    $scope.langCharTypeColor = function(typeID) {
      return clCtrl.charLocale.type[typeID].color;
    };


    clCtrl.addTodo = function() {
      clCtrl.todos.push({text:clCtrl.todoText, done:false});
      clCtrl.todoText = '';
    };

  }]);