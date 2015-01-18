'use strict';

angular.module('talklyApp.directives', [])
    .directive('topicShort', function () {
        return {
            restrict: 'E',
            scope: {
                topic: '='
            },
            templateUrl: 'js/templates/topic-short.html'
        }
    });