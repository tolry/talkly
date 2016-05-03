'use strict';

angular.module('talklyApp.directives', [])
    .directive(
        'topicShort',
        [
            'User',
            'TEMPLATE_TOPIC_SHORT',
            function (User, TEMPLATE_TOPIC_SHORT) {
                return {
                    restrict: 'E',
                    scope: {
                        topic: '='
                    },
                    templateUrl: TEMPLATE_TOPIC_SHORT,
                    controller: function ($scope) {
                        $scope.voted = false;
                        $scope.speaker = false;
                        $scope.created = false;

                        User.get(function (user) {
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
            }
        ]
    );