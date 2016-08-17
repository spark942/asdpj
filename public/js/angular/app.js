angular.module('saocr', ['angularUtils.directives.dirPagination']);

angular.module('saocr')
  .filter('reverse', function() {
    return function(items) {
      return items.slice().reverse();
    };
  })
  .filter('unitBookFilter', function() {
  return function( items, unitparams) {
    unitparams = unitparams || {};
    var filtered = [];
    angular.forEach(items, function(item) {
      if( (item.rank <= unitparams.maxrank) &&
          (
            (item.tribe == "1" && unitparams.tribe1) ||
            (item.tribe == "2" && unitparams.tribe2) ||
            (item.tribe == "3" && unitparams.tribe3) ||
            (item.tribe == "4" && unitparams.tribe4)
            ) &&
          (
            (item.evolId == "-1" && unitparams.maxevol >= 2) ||
            (item.evolId != "-1" && unitparams.maxevol == 1)
            ) &&
          (
            (item.isAirUnit == "Y" && unitparams.air) ||
            (item.isAirUnit == "N" && unitparams.ground)
            ) &&
          (
            (item.damageType == "physical" && unitparams.physical) ||
            (item.damageType == "magical" && unitparams.magical)
            ) && (item.showBook == "Y")
          ) {
          filtered.push(item);
        }
      });
      return filtered;
    };
  })
  .filter('totSolutionsFilter', function() {
  return function( items, filters) {
    filters = filters || {};
    var filtered = [];
    angular.forEach(items, function(item) {
      console.log(item.maxevol);
      if( (item.maxrank <= filters.maxrank) &&
          (item.maxevol <= filters.maxevol) &&
          (
            (item.gamespeed == "1" && filters.speed1) ||
            (item.gamespeed == "2" && filters.speed2) ||
            (item.gamespeed == "3" && filters.speed3) ||
            (item.gamespeed == "5" && filters.speed5)
          )
          ) {
          filtered.push(item);
        }
      });
      return filtered;
    };
  });