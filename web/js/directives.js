'use strict';

angular.module('talklyApp.directives', [])
    .directive('topicShort', ['User',
        function (User) {
            return {
                restrict: 'E',
                scope: {
                    topic: '='
                },
                templateUrl: 'js/templates/topic-short.html',
                controller: function ($scope) {
                    $scope.voted = false;
                    $scope.speaker = false;
                    $scope.created = false;

                    User.getCurrent().then(function (user) {
                        angular.forEach($scope.topic.votes, function (vote) {
                            if (user.id === vote.id) {
                                $scope.voted = true;
                            }
                        });

                        angular.forEach($scope.topic.speakers, function (speaker) {
                            if (user.id === speaker.id) {
                                $scope.speaker = true;
                            }
                        });
                        $scope.created = ($scope.topic.created_by === user.id);
                    });

                    $scope.concatUsers = function (users) {
                        var string = '';

                        angular.forEach($scope.topic.speakers, function (user) {
                            if (string !== '') {
                                string += ', ';
                            }

                            string += user.name;
                        });

                        return string;
                    };
                }
            }
        }]);