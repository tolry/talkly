'use strict';

angular.module('talklyApp.services', ['ngResource'])
    .factory('Topics', ['$resource', 'BASE_URL',
        function ($resource, BASE_URL) {
            return $resource(BASE_URL + 'api/topics', {}, {
                'query': {method: 'GET', isArray: true}
            });
        }])
    .factory('User', ['$resource', '$q', 'BASE_URL',
        function ($resource, $q, BASE_URL) {
            var user = null;

            return {
                getCurrent: function () {
                    var d = $q.defer();

                    if (null === user) {
                        var api = $resource(BASE_URL + 'api/security/user', {}, {
                            'get': {method: 'GET'}
                        });

                        user = api.get(function () {
                            d.resolve(user);
                        });
                    } else {
                        d.resolve(user)
                    }

                    return d.promise;
                }
            };
        }]);