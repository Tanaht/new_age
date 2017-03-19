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
                nbHeures: 0,
            };

            let persistObject = new PersistentObject('new_voeux', {id: $scope.cours.id}, $scope.voeu);


            $scope.submit = function() {
                persistObject.persist(rest);
            }


        }
    }
};