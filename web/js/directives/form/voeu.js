/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, rest, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            cours: '='
        },
        controller: function($scope) {

            $scope.voeu = {
                nb_heures: 0,
                cours: $scope.cours
            }


        }
    }
};