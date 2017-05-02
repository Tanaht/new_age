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
            if(angular.isNumber(scope.user)) {
                rest.get('get_utilisateur', { id: scope.user }).then(function(success) {
                    scope.utilisateur = success.data;
                })
            } else if(angular.isObject(scope.user)) {
                scope.utilisateur = scope.user;
            }
            else {
                if(config.debugMode)
                    $log.debug("[directive: userLink] scope.user neither a number or an object, destroying itself");
                scope.$destroy();
            }

        },
    }
};