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
            if(!angular.isObject(scope.user)) {
                rest.get('get_utilisateur', { id: scope.user }, function(success) {
                    scope.utilisateur = success.data;
                })
            } else {
                scope.utilisateur = scope.user;
            }

        },
    }
};