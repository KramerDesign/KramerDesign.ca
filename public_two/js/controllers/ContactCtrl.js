(function () {
    "use strict";

    var controllers = angular.module("myApp.controllers");

    controllers.controller("contactCtrl", ["$scope", "contact", function ($scope, contact) {

        $scope.topicsList = [
            {id:'general', name:'General Enquiry'},
            {id:'bookConsultation', name:'Book Free Consultation'},
            {id:'requestQuote', name:'Request Quote'},
            {id:'testimonial', name:'Leave a Testimonial'}
        ];
        $scope.topic = $scope.topicsList[0];

        $scope.send = function () {
            contact.send(
                $scope.topic.name,
                $scope.fullName,
                $scope.phone,
                $scope.email,
                $scope.shortMessage).then(function (response) {
                $scope.topic.name = "";
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