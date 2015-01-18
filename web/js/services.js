'use strict';

angular.module('talklyApp.services', ['ngResource'])
    .factory('Topics', ['$resource', function ($resource) {
        return $resource('/api/topics', {}, {
            'query': {method: 'GET', isArray: true}
        });
    }]);