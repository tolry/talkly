'use strict';

angular.module('talklyApp.services', ['ngResource'])
    .factory('Topics', ['$resource', 'BASE_URL',
        function ($resource, BASE_URL) {
            return $resource(BASE_URL + 'api/topics', {}, {
                'query': {method: 'GET', isArray: true, cache: true}
            });
        }])
    .factory('User', ['$resource', '$q', 'BASE_URL',
        function ($resource, $q, BASE_URL) {
            return $resource(BASE_URL + 'api/security/user', {}, {
                'get': {method: 'GET', cache: true}
            });
        }]);