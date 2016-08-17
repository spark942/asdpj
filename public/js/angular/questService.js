angular.module('saocr')
  .service("questService", ["$http", "$timeout",
    function($http, $timeout) {
      this.charLocale = null;
      this.qwJS = window.table_quest_worlds;
      this.qcJS = window.table_quest_chapters;
      this.qgJS = window.table_quest_groups;
      this.qJS  = window.table_quests;

      this.initLang = function() {
        (function(thisService){
          $http.post('/lang/quest', {msg:'hello world!'}).
          success(function(data, status, headers, config) {
            thisService.charLocale = data;
          }).
          error(function(data, status, headers, config) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
          });
        }(this));
      };

      //this.initLang();

      this.dQWStoryName = function(qwID) {
        return this.qwJS[qwID].displayedStoryName != '' ? this.qwJS[qwID].displayedStoryName : this.qwJS[qwID].rawStoryName;
      };

      this.dQCName = function(qcID) {
        return this.qcJS[qcID].displayedName != '' ? this.qcJS[qcID].displayedName : this.qcJS[qcID].rawName;
      };
      this.dQCDescription = function(qcID) {
        return this.qcJS[qcID].displayedDescription != '' ? this.qcJS[qcID].displayedDescription : this.qcJS[qcID].rawDescription;
      };

      this.dQGName = function(qgID) {
        return this.qgJS[qgID].displayedName != '' ? this.qgJS[qgID].displayedName : this.qgJS[qgID].rawName;
      };
      this.dQGDescription = function(qgID) {
        return this.qgJS[qgID].displayedDescription != '' ? this.qgJS[qgID].displayedDescription : this.qgJS[qgID].rawDescription;
      };

      this.dQName = function(qID) {
        return this.qJS[qID].displayedName != '' ? this.qJS[qID].displayedName : this.qJS[qID].rawName;
      };
      /*this.dQDescription = function(qID) {
        return this.qJS[qID].displayedDescription != '' ? this.qJS[qID].displayedDescription : this.qJS[qID].rawDescription;
      };
*/

    }
  ]);