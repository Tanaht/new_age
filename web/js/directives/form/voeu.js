/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            cours: '='
        },
        controller: function($scope) {
            $log.debug($scope.cours);
            $scope.voeu = {
                nbHeures: 0,
            };

            let persistObject = new PersistentObject('new_voeux', {id: $scope.cours.id}, $scope.voeu, config);

            $scope.$watch('voeu.nbHeures', function(newValue, oldValue) {
                if(!persistedQueue.contains(persistObject) && newValue != 0 && newValue != undefined) {
                    persistedQueue.push(persistObject);
                }
            });

            $scope.submit = function() {
                persistedQueue.persist();
            }


        }
    }
};