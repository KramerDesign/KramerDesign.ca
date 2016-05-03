'use strict';

angular.module('seedApp.configFiles', [])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/view1', {templateUrl: 'partials/partial1.html', controller: 'MyCtrl1'});
        $routeProvider.when('/view2', {templateUrl: 'partials/partial2.html', controller: 'MyCtrl2'});
        $routeProvider.when('/view3', {templateUrl: 'partials/partial3.html', controller: 'MyCtrl3'});
        $routeProvider.when('/view4', {templateUrl: 'partials/partial4.html', controller: 'MyCtrl4'});
        $routeProvider.when('/view5', {templateUrl: 'partials/partial5.html', controller: 'MyCtrl5'});
        $routeProvider.when('/view6', {templateUrl: 'partials/partial6.html', controller: 'MyCtrl6'});
        $routeProvider.when('/view7', {templateUrl: 'partials/partial7.html', controller: 'MyCtrl7'});
        $routeProvider.when('/contact', {templateUrl: 'partials/contact.html', controller: 'ContactCtrl'});
        $routeProvider.otherwise({redirectTo: '/view1'});
    }]);