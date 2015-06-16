//var app = angular.module('myApp',['appControllers','appDirectives','appFilters','appServices','ngRoute','ngResource','appStores']);
var app = angular.module('myApp',['appControllers','appDirectives','appServices','appFilters','ngRoute','ngResource','angular-jquery-maskedinput','appAcl','appDynamicFieldsOfread', 'appAddLinkForRID']);

angular.module("template/typeahead/typeahead-popup.html", []).run(["$templateCache", function($templateCache) {
    $templateCache.put("template/typeahead/typeahead-popup.html",
        "<ul class=\"dropdown-menu\" ng-show=\"isOpen()\" ng-style=\"{top: position.top+'px', left: position.left+'px'}\" style=\"display: block;\" role=\"listbox\" aria-hidden=\"{{!isOpen()}}\">\n" +
        "    <li ng-repeat=\"match in matches track by $index\" ng-class=\"{active: isActive($index) }\" ng-mouseenter=\"selectActive($index)\" ng-click=\"selectMatch($index)\" role=\"option\" id=\"{{match.id}}\">\n" +
        "        <div typeahead-match index=\"$index\" match=\"match\" query=\"query\" template-url=\"templateUrl\"></div>\n" +
        "    </li>\n" +
        "</ul>");
}]);

app.factory('helper', function() {
 var guid =  function () {
    function s4() {
      return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
      s4() + '-' + s4() + s4() + s4();
  }

  var findWithAttr =  function (array, attr, value) {
    for(var i = 0; i < array.length; i += 1) {
        if(array[i][attr] === value) {
            return i;
        }
    }
    return -1;
  }
  return {
   'guid': guid,
   'findWithAttr':findWithAttr
  };
});



/*
app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/pages', {
        templateUrl: 'templates/pages.html',
        controller: 'TableCtrl'
      }).
      when('/:type', {
        templateUrl: 'templates/news.html',
        controller: 'TableCtrl'
      }).
      otherwise({
        redirectTo: '/news'
      });
  }]);
  */