angular.module('saocr')
  .service("characterService", ["$http", "$timeout", 
    function($http, $timeout) {
      this.charLocale = null;
      this.ckJS = window.table_character_kind;

      this.initLang = function() {
        (function(thisService){
          $http.post('/lang/character', {msg:'hello world!'}).
          success(function(data, status, headers, config) {
            thisService.charLocale = data;
          }).
          error(function(data, status, headers, config) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
          });
        }(this));
      };

      this.initLang();

      this.langCharHeadName = function(charKindID) {
        return this.ckJS[charKindID].displayedHeadName != '' ? this.ckJS[charKindID].displayedHeadName : this.ckJS[charKindID].rawHeadName;
      };
      this.langCharName = function(charKindID) {
        return this.ckJS[charKindID].displayedName != '' ? this.ckJS[charKindID].displayedName : this.ckJS[charKindID].rawName;
      };
      this.langCharElement = function(elementID) {
        return this.charLocale ? this.charLocale.elemental[elementID] : 'Loading';
      };
      this.langWeaponCategory = function(weaponCategoryID) {
        return this.wcLocale ? this.wcLocale[weaponCategoryID].name : 'Loading';
      };
      this.langCharTypeName = function(typeID) {
        return this.charLocale ? this.charLocale.type[typeID].name : 'Loading';
      };
      this.langCharTypeColor = function(typeID) {
        return this.charLocale ? this.charLocale.type[typeID].color : 'Loading';
      };
    }
  ]);