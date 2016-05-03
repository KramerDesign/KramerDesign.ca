(function () {
    'use strict';

    var module = angular.module('seedApp.controllerFiles');

    module.controller('ContactCtrl', ['$scope', '$location', function($scope, $location) {
        $scope.firstName = "John";
        $scope.lastName = "Doee";

    }]);

}());