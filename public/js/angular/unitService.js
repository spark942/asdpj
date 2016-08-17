angular.module('saocr')
  .service("unitService", ["$http", "$timeout", 
    function($http, $timeout) {
      this.charLocale = null;

    }
  ]);