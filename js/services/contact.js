'use strict';

(function () {
  'use strict';
  var module = angular.module('seedApp.serviceFiles');
  module.factory('contact', ["$http", function ($http) {
    return {
      send: function (fullName, phone, email, shortMessage) {
        return $http.post("./contact.php", {
          fullName: fullName,
          phone: phone,
          email: email,
          shortMessage: shortMessage
        });
      }
    };

  }]);
})();