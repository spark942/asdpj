angular.module('saocr')
  .controller('LeaderSkillListController', ['$scope', '$filter', 'characterService',
    function($scope, $filter, characterService) {
    $scope.characterService = characterService;

    var lslCtrl = this;
    lslCtrl.leaderSkillList   = saocrLeaderSkilllist || [];

    $scope.clOptions = {};
    $scope.ckByPage = 10;
    $scope.predicate = '-rarity_num';
    $scope.reverse   = false;
    $scope.order = function(predicate, reverse) {
      lslCtrl.leaderSkillList = $filter('orderBy')(lslCtrl.leaderSkillList, predicate, reverse);
    };

  }]);