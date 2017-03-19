/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, history, config) {
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

            let onQueue = false;
            let persistObject = new PersistentObject('new_voeux', {id: $scope.cours.id}, $scope.voeu);

            persistObject.onFailure = function(error) {
                $log.debug("failure occured");
            };

            $scope.$watch('voeu.nbHeures', function(newValue, oldValue) {
                if(!onQueue && newValue != 0 && newValue != undefined) {
                    history.push(persistObject);
                    onQueue = true;
                }
            });

            $scope.submit = function() {
                history.persist();
            }


        }
    }
};