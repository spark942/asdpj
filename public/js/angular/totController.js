angular.module('saocr')
  .controller('TotController', ['$scope', '$http', '$filter', 'unitService',
    function($scope, $http, $filter, unitService) {
    $scope.unitService = unitService;
    $scope.unitBook = window.gameUnitBook || [];
    //$scope.filteredSolutions = 0;

    var totCtrl = this;

    /*clCtrl.charList   = saocrCharlist || [];*/
    totCtrl.test = "hola";
    totCtrl.round = '99';
    totCtrl.floors = currentTotSolutions || [];
    totCtrl.resultFilters = {
      maxevol: 5,
      maxrank: 6,
      speed1: true,
      speed2: true,
      speed3: true,
      speed4: true,
    };
    totCtrl.unitparams = {
      maxrank: 6,
      tribe1: true,
      tribe2: true,
      tribe3: true,
      tribe4: true,
      maxevol: 1,
      air: true,
      ground: true,
      physical: true,
      magical: true
    };

    totCtrl.newsolution = [];
    totCtrl.templateNewsolutionparams = {
      gamespeed: 2,
      luck: false,
      note: ""
    };
    totCtrl.newsolutionparams = totCtrl.templateNewsolutionparams;

    totCtrl.roundvotes = window.roundvotes || {};
    totCtrl.currentRoundVotesSelf = window.myroundvotes || {};

    $scope.addUnitToNewSolution = function(unitid,rank,trans){
      if (totCtrl.newsolution.length >= 12) {return;}
      unitid = unitid || 0;
      rank = rank || 0;
      trans = trans || 0;
      totCtrl.newsolution.push({
        unitId: unitid,
        rank:   rank,
        trans:  trans
      });
    };

    $scope.removeUnitFromNewSolution = function(unitposition){
      totCtrl.newsolution.splice(unitposition, 1);
    };


    $scope.getRankBorder = function(unitRank){
      return 'unit-rarity-'+unitRank;
    };

    $scope.pad = function(num, size) {
      var s = num+"";
      while (s.length < size) s = "0" + s;
      return s;
    }

    $scope.toggle = function(floor, bool) {
      var floor = floor || 0;
      var bool = bool || false;
      angular.forEach(totCtrl.floors, function(value, key) {
        if (value.floorn == floor) {
          value.isCollapsed = !bool;
        }
      });
    }
    $scope.voted = function(solutionID) {
      solutionID = solutionID || 0;
      var solutionVoted = false;
      angular.forEach(totCtrl.currentRoundVotesSelf, function(value, key) {
        if (value.sid == solutionID) {
          solutionVoted = true;
        }
      });
      return solutionVoted;
    }
    $scope.voteType = function(solutionID) {
      solutionID = solutionID || 0;
      var solutionType = 0;
      angular.forEach(totCtrl.currentRoundVotesSelf, function(value, key) {
        if (value.sid == solutionID) {
          solutionType = value.votetype;
        }
      });
      return solutionType;
    };
    $scope.countvote = function(floor){
      floor = floor || 0;
      var n = 0;
      angular.forEach(totCtrl.roundvotes, function(value, key) {
        if (value.floor == floor) {
          n++;
        }
      });
      return n;
    }
    $scope.canaddsolution = function(floor){
      var can = false;
      if (floor == 1) {
        can = true;
      }
      angular.forEach(totCtrl.currentRoundVotesSelf, function(value, key) {
        if ((value.floor == floor-1) && (value.votetype > 0)) {
          can = true;
        }
      });
      return can;
    };
    $scope.floorCleared = function(floor){
      var can = false;
      angular.forEach(totCtrl.currentRoundVotesSelf, function(value, key) {
        if ((value.floor == floor) && (value.votetype > 0)) {
          can = true;
        }
      });
      return can;
    }


    totCtrl.sendNewSolution = function(round, floor) {
      round = round || 0;
      floor = floor || 0;
      if (!totCtrl.newsolution.length) {
        alert("You need to set your team first.");
        return;
      }
      if (round == 0 || floor == 0) {
        alert("There's an error, please refresh the page.");
        return;
      }
      (function(thisService){
        var newdata = {
          msg:'newsolutiondata',
          round: round,
          floor: floor,
          solution: thisService.newsolution,
          solutionparams: thisService.newsolutionparams
        };
        $http.post('/ajax/newsolution', newdata).
        success(function(data, status, headers, config) {
          //console.log(data);
          thisService.floors = data['solutions'];
          thisService.currentRoundVotesSelf = data['myroundvotes'];
          thisService.roundvotes = data['roundvotes'];
          thisService.newsolution = [];
          thisService.newsolutionparams = thisService.newsolutionparams;
        }).
        error(function(data, status, headers, config) {
          console.log('error', status, headers, config);
        });
      }(totCtrl));
    };

    totCtrl.voteSolution = function(solutionID, voteType) {
      solutionID = solutionID || 0;
      voteType = voteType || 0;
      (function(thisService){
        var newdata = {
          msg:'votesolutiondata',
          solutionID: solutionID,
          voteType: voteType,
        };
        thisService.newsolution = [];
        $http.post('/ajax/votesolution', newdata).
        success(function(data, status, headers, config) {
          console.log(data);
          thisService.currentRoundVotesSelf = data['myroundvotes'];
          thisService.roundvotes = data['roundvotes'];

        }).
        error(function(data, status, headers, config) {
          console.log('error', status, headers, config);
        });
      }(totCtrl));
    };
  }]);