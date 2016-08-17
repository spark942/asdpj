angular.module('saocr')
  .service("weaponService", ["$http", "$timeout", 
    function($http, $timeout) {
      this.weaponCategoriesLocale = null;

      this.initLang = function() {
        (function(thisService){
          $http.post('/lang/weapon/categories', {msg:'hello world!'}).
          success(function(data, status, headers, config) {
            thisService.weaponCategoriesLocale = data;
          }).
          error(function(data, status, headers, config) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
          });
        }(this));
      };

      this.initLang();

      this.langWeaponCategory = function(weaponCategoryID) {
        return this.weaponCategoriesLocale ? this.weaponCategoriesLocale[weaponCategoryID].name : 'Loading';
      };

    }
  ]);