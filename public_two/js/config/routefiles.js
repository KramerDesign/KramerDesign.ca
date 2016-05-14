'use strict';

angular.module('seedApp.configFiles', [])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/home', {templateUrl: 'partials/home.html', controller: 'homeCtrl'});
        $routeProvider.when('/resume', {templateUrl: 'partials/resume.html', controller: 'resumeCtrl'});
        $routeProvider.when('/wireframes', {templateUrl: 'partials/wireframes.html', controller: 'wireCtrl'});
        $routeProvider.when('/concepts', {templateUrl: 'partials/concepts.html', controller: 'conceptCtrl'});
        $routeProvider.when('/finished', {templateUrl: 'partials/finished.html', controller: 'finishedCtrl'});
        //$routeProvider.when('/view6', {templateUrl: 'partials/partial6.html', controller: 'MyCtrl6'});
        $routeProvider.when('/about', {templateUrl: 'partials/about.html', controller: 'aboutCtrl'});
        $routeProvider.when('/contact', {templateUrl: 'partials/contact.html', controller: 'contactCtrl'});
        $routeProvider.otherwise({redirectTo: '/home'});
    }]);