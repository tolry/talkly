'use strict';

angular.module('talklyApp.services', ['ngResource'])
    .factory('Topics', ['$resource', function ($resource) {
        return $resource('/api/topics', {}, {
            'query': {method: 'GET', isArray: true}
        });
    }])
    .factory('User', ['$resource', '$q',
        function ($resource, $q) {
            var user = null;

            return {
                getCurrent: function () {
                    var d = $q.defer();

                    if (null === user) {
                        var api = $resource('/api/security/user', {}, {
                            'get': {method: 'GET'}
                        });

                        user = api.get(function() {
                            d.resolve(user);
                        });
                    } else {
                        d.resolve(user)
                    }

                    return d.promise;
                }
            };
        }]);