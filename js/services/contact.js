'use strict';

(function () {
  'use strict';
  var module = angular.module('myApp.services');
  module.factory('contact', ["$http", function ($http) {
    return {
      send: function ( topic, fullName, phone, email, shortMessage) {
        return $http.post("./contact.php", {
          topic: topic,
          fullName: fullName,
          phone: phone,
          email: email,
          shortMessage: shortMessage
        });
      }
    };

  }]);
})();