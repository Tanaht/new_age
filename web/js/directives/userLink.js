/**
 * Created by Antoine on 23/03/2017.
 */
module.exports = function($log, rest, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/user_link.tpl.html',
        scope: {
            user: '='
        },
        link: function preLink(scope) {
            scope.popoverTemplate = config.base_uri + '/js/tpl/popover/user.tpl.html';
            if(angular.isNumber(scope.user) && angular.isUndefined(config.users[scope.user])) {
                config.users[scope.user] = {};
                rest.get('get_utilisateur', { id: scope.user }).then(function(success) {
                    config.users[scope.user] = success.data;
                    scope.utilisateur = config.users[scope.user];
                })
            } else if(angular.isObject(scope.user)) {

                if(angular.isUndefined(config.users[scope.user]))
                    config.users[scope.user.id] = scope.user;

                scope.utilisateur = config.users[scope.user.id];
            }
            else {
                if(config.debugMode)
                    $log.debug("[directive: userLink] scope.user neither a number or an object, destroying itself");
                scope.$destroy();
            }

        },
    }
};