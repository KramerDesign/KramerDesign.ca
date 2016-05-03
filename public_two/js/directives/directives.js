'use strict';

/* Directives */


angular.module('seedApp.directiveFiles', []).
  directive('appVersion', ['version', function(version) {
    return function(scope, elm, attrs) {
      elm.text(version);
    };
  }]);
