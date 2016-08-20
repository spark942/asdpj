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
    totCtrl.templateNewsolutionparams = JSON.stringify({
      gamespeed: 2,
      luck: false,
      note: ""
    });
    totCtrl.newsolutionparams = JSON.parse(totCtrl.templateNewsolutionparams);

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
    $scope.countvote = function(floor, solutionID, specific){
      floor = floor || null;
      solutionID = solutionID || null;
      specific = specific || null;
      var n = 0;
      angular.forEach(totCtrl.roundvotes, function(value, key) {
        if (
          (specific === null && value.floor == floor) ||
          (specific === 0 && value.floor == floor && value.votetype == specific && (solutionID === null || (value.id == solutionID))) ||
          (specific === 1 && value.floor == floor && value.votetype == specific && (solutionID === null || (value.id == solutionID))) ||
          (specific === 2 && value.floor == floor && value.votetype == specific && (solutionID === null || (value.id == solutionID)))
          ) {
          n++;
        }
      });
      return n;
    }

    $scope.votebarDisplay = function(floor, solutionID, returnSpecific, percent) {
      floor = floor || null;
      solutionID = solutionID || null;
      returnSpecific = (returnSpecific === undefined) ? null : returnSpecific;
      percent = percent || null;
      var n = 0, nAll = 0;
      angular.forEach(totCtrl.roundvotes, function(value, key) {
        if (floor == null) {
          /* Round votes */
          if (returnSpecific === null || (returnSpecific !== null && returnSpecific === value.votetype)){
            n++;
          }
          if (returnSpecific !== null) {
            nAll++;
          }
        } else if (floor !== null) {
          /* Floor votes */
          if (solutionID === null && floor == value.floor) {
            /* Floor vote, non specific to a solution */
            if (returnSpecific === null || (returnSpecific !== null && returnSpecific === value.votetype)){
              n++;
            }
            if (returnSpecific !== null) {
              nAll++;
            }
          } else if (solutionID !== null && solutionID == value.sid) {
            /* Floor vote, specific to a solution */
            if (returnSpecific === null || (returnSpecific !== null && returnSpecific === value.votetype)){
              n++;
            }
            if (returnSpecific !== null) {
              nAll++;
            }
          }
        }
      });

      if (percent == 'no') {
        if (returnSpecific===0) {
          console.log(n,nAll);
        }
        return n;
      } else {
        //console.log(floor, solutionID, n,nAll);
        return (n/nAll)*100 <= 100 ? (n/nAll)*100 : 0;
      }
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
          thisService.floors = data['solutions'];
          thisService.currentRoundVotesSelf = data['myroundvotes'];
          thisService.roundvotes = data['roundvotes'];
          thisService.newsolution = [];
          thisService.newsolutionparams = JSON.parse(thisService.templateNewsolutionparams);
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
          thisService.currentRoundVotesSelf = data['myroundvotes'];
          thisService.roundvotes = data['roundvotes'];
          console.log(totCtrl.floors);
        }).
        error(function(data, status, headers, config) {
          console.log('error', status, headers, config);
        });
      }(totCtrl));
    };
  }]);