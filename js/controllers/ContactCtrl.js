(function () {
    "use strict";

    var controllers = angular.module("seedApp.controllerFiles");

    controllers.controller("contactCtrl", ["$scope", "contact", function ($scope, contact) {


        //$scope.topic = $scope.topicsList[0];

        $scope.send = function () {
            contact.send(
                $scope.fullName,
                $scope.phone,
                $scope.email,
                $scope.shortMessage).then(function (response) {
                //$scope.topic.name = "website enquiry";
                $scope.fullName = "";
                $scope.phone = "";
                $scope.email = "";
                $scope.shortMessage = "";

                var data = response.data;
                alert(data.message);
            }, function (err) {
                var data = err.data;
                alert(data.error);
            });

        };

    }]);
})();