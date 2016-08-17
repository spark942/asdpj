angular.module('saocr')
  .controller('AdminController', ['$http', '$scope', '$filter', 'characterService', 'weaponService',
    function($http, $scope, $filter, characterService, weaponService) {
    $scope.characterService = characterService;
    $scope.weaponService = weaponService;

    var admCtrl = this;
    var admCatRighs = adminCategoryRights;

    admCtrl.categories   = [
      {
        name       : 'overview',
        publicName : 'Overview',
        right      : admCatRighs.overview || 1,
        selected   : true,
      },
      {
        name       : 'dbtranslation',
        publicName : 'Game Database',
        right      : admCatRighs.dbtranslation || 0,
        selected   : false,
        tables     : [
          {
            name       : 'dboverview',
            publicName : 'Database Overview',
            selected   : true,
          },
          {
            name       : 'table_character_kind',
            publicName : 'Character kind',
            selected   : false,
            fileUsed   : 1,
          },
          {
            name       : 'table_quest_worlds',
            publicName : 'Quest Worlds',
            selected   : false,
            fileUsed   : 1,
          },
          {
            name       : 'table_quest_chapters',
            publicName : 'Quest Chapters',
            selected   : false,
            fileUsed   : 1,
          },
          {
            name       : 'table_quest_groups',
            publicName : 'Quest Groups',
            selected   : false,
            fileUsed   : 1,
          },
          {
            name       : 'table_quests',
            publicName : 'Quests',
            selected   : false,
            fileUsed   : 1,
          },
        ]
      },
      {
        name       : 'events',
        publicName : 'Events',
        right      : admCatRighs.events || 0,
        selected   : false,
      },
      {
        name       : 'articles',
        publicName : 'Articles',
        right      : admCatRighs.articles || 0,
        selected   : false,
      },

    ];

    admCtrl.dbtable = [];
    admCtrl.dbtablenote = '';
    admCtrl.datafilelist = [];
    /* specific data */
    $scope.charworlds = ['none','SAO','ALO','GGO'];
    $scope.charlimitedtypes = [
      {name:'none'},
      {name:'code'},
      {name:'scout event'},
      {name:'dungeon event'}];

    $scope.categorySelect = function(categoryName) {
      var categoryName = categoryName;

      var hasRight = false;
      // check is has rights
      angular.forEach(admCtrl.categories, function(category){
        if(categoryName == category.name && category.right != 0)
          hasRight = true;
      });

      if (!hasRight) return;

      angular.forEach(admCtrl.categories, function(category){
        if(categoryName == category.name)
          category.selected = true;
        else
          category.selected = false;
      });
    };

    $scope.dbtableSelect = function(dbtableName) {
      var dbtableName = dbtableName;

      angular.forEach(admCtrl.categories, function(category){
        if(category.name == 'dbtranslation') {
          angular.forEach(category.tables, function(dbtable){
            if (dbtableName == dbtable.name)
              dbtable.selected = true;
            else
              dbtable.selected = false;
          });
        }
      });
    };

    admCtrl.loadDbtable = function(dbtableName) {
      var dbtableName = dbtableName;

      (function(thisService){
        $http.post('/dbtable', {
          dbtable:dbtableName
        }).
        success(function(data, status, headers, config) {
          admCtrl.initDbtable(data);
        }).
        error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
      }(this));
    };

    admCtrl.initDbtable = function(dbData) {
      var dbData = dbData;

      (function(thisService){
        thisService.dbtable     = [];
        thisService.dbtablenote = '';
        /**
         * SPECIFIC TABLES
         */
        if (dbData[0] == 'table_character_kind') {
          angular.forEach(dbData[1], function(charkind){
            var newchar = {
              originalID        : charkind.id,
              originalHeadName  : charkind.head_name,
              originalName      : charkind.name,
              originalCodeName  : charkind.name_english,
              displayedHeadName : '',
              displayedName     : '',
              displayedWorld    : '',
              //displayedLimitedType : ''
            }

            thisService.dbtable.push(newchar);
          });
        } else if (dbData[0] == 'table_quest_worlds') {
          angular.forEach(dbData[1], function(qworld){
            var newrow = {
              originalID                : qworld.id,
              originalAcronymName       : qworld.acronymName,
              originalStoryName         : qworld.storyName,
              originalStoryDescription  : qworld.storyDescription,
              displayedStoryName        : '',
              displayedStoryDescription : '',
            };

            thisService.dbtable.push(newrow);
          });
        } else if (dbData[0] == 'table_quest_chapters') {
          angular.forEach(dbData[1], function(qchap){
            var newrow = {
              originalID           : qchap.id,
              originalName         : qchap.name,
              originalDescription  : qchap.description,
              displayedName        : '',
              displayedDescription : '',
            };

            thisService.dbtable.push(newrow);
          });
        } else if (dbData[0] == 'table_quest_groups') {
          angular.forEach(dbData[1], function(qgroup){
            var newrow = {
              originalID           : qgroup.id,
              originalName         : qgroup.name,
              originalDescription  : qgroup.description,
              displayedName        : '',
              displayedDescription : '',
            };

            thisService.dbtable.push(newrow);
          });
        } else if (dbData[0] == 'table_quests') {
          angular.forEach(dbData[1], function(quest){
            var newrow = {
              originalID           : quest.id,
              originalName         : quest.name,
              originalDescription  : quest.description,
              displayedName        : '',
              displayedDescription : '',
            };

            thisService.dbtable.push(newrow);
          });
        }
      }(this));
    };

    admCtrl.loadDataFileList = function(dbtableName) {
      var dbtableName = dbtableName;

      (function(thisService){
        $http.post('/loadfilelist', {
          dbtable : dbtableName
        }).
        success(function(data, status, headers, config) {
          thisService.datafilelist[dbtableName] = data;
        }).
        error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
        });
      }(this));
    };

    admCtrl.updateDbData = function(datafile, dbtableName) {
      var datafile    = datafile;
      var dbtableName = dbtableName;

      var script = document.createElement( 'script' );
      script.type = 'text/javascript';
      script.src = 'js/data/'+datafile;
      angular.element(document.querySelector('#dbfile')).append(script);


      setTimeout(function(){
        var dbdata = eval(dbtableName);

        angular.forEach(admCtrl.dbtable, function(tableRow){
          /**
           * SPECIFIC TABLES
           */
          switch(dbtableName) {
            case 'table_character_kind':
              tableRow.displayedHeadName   = dbdata[tableRow.originalID].displayedHeadName || '';
              tableRow.displayedName       = dbdata[tableRow.originalID].displayedName || '';
              tableRow.displayedWorld      = dbdata[tableRow.originalID].displayedWorld || '';
              break;
            case 'table_quest_worlds':
              tableRow.displayedStoryName        = dbdata[tableRow.originalID].displayedStoryName || '';
              tableRow.displayedStoryDescription = dbdata[tableRow.originalID].displayedStoryDescription || '';
              break;
            case 'table_quest_chapters':
            case 'table_quest_groups':
            case 'table_quests':
              tableRow.displayedName       = dbdata[tableRow.originalID].displayedName || '';
              tableRow.displayedDescripion = dbdata[tableRow.originalID].displayedDescription || '';
              break;
          }
        });
      }, 500);
    };

    admCtrl.saveDbtable = function(dbtableName) {
      var dbtableName = dbtableName;

      (function(thisService){
        if (thisService.dbtablenote == '') {
          alert("Please, leave a note to say what this save is about.");
          return;
        }
        $http.post('/datasavefile', {
          dbtable : dbtableName,
          note    : thisService.dbtablenote,
          data    : thisService.dbtable,
        }).
        success(function(data, status, headers, config) {
          alert("File saved, go to the overview");
        }).
        error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          alert("File NOT saved, please report to the developer");
        });
      }(this));
    };

    admCtrl.updateFileUsedByTable = function(dbtableName, fileID) {
      var dbtableName = dbtableName;
      var fileID      = fileID;

      (function(thisService){
        $http.post('/updatefileusedbytable', {
          dbtable   : dbtableName,
          fileID    : fileID,
        }).
        success(function(data, status, headers, config) {
          console.log(data);
        }).
        error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          alert("Can't update file for this table, please report to the developer");
        });
      }(this));
    };

    admCtrl.getFileUsedByTable = function(dbtableName, callback) {
      var dbtableName = dbtableName;

      (function(thisService){
        $http.post('/getfileusedbytable', {
          dbtable   : dbtableName,
        }).
        success(function(data, status, headers, config) {
          callback(data);
        }).
        error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
          alert("Can't update file for this table, please report to the developer");
        });
      }(this));
    };

    admCtrl.initFilesUsedForTables = function() {
      (function(thisService){
        angular.forEach(thisService.categories, function(category){
          if (category.name == 'dbtranslation') {
            angular.forEach(category.tables, function(table, tkey){
              if (tkey > 0) {
                function setFileID(data){
                  table.fileUsed = data.fileID;
                };
                this.getFileUsedByTable(table.name, setFileID);
              }
            }, this);
          }
        }, thisService);
      }(this));
    }

    admCtrl.addTodo = function() {
      admCtrl.todos.push({text:admCtrl.todoText, done:false});
      admCtrl.todoText = '';
    };

  }]);