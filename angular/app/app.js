//var app = angular.module('myApp',['appControllers','appDirectives','appFilters','appServices','ngRoute','ngResource','appStores']);
var app = angular.module('myApp',['appControllers','appDirectives','appServices','appFilters','ngRoute','ngResource','angular-jquery-maskedinput']);
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