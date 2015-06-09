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