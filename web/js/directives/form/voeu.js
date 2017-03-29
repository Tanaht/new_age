/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, $sce, $filter, errorManager, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            ueName: '=',
            cours: '='
        },
        controller: function($scope) {
            $scope.errm = errorManager;

            let route = 'new_voeux';
            let routing_options = {id: $scope.cours.id};

            let filtered = $filter('filter')($scope.cours.voeux, {utilisateur: { id: config.user.id }});

            if(filtered.length === 0) {

                $scope.voeu = {
                    nbHeures: 0,
                    utilisateur: config.user.id,
                    commentaire: ""
                };

                $scope.cours.voeux.push($scope.voeu);

            }
            else if(filtered.length === 1){
                $scope.voeu = filtered[0];

                route = 'edit_voeux';
                routing_options.id = filtered[0].id;
            }
            else {
                $log.error("[Controller:VoeuForm] A voeu can only be linked to one user for the same cours");
            }

            let persistObject = new PersistentObject(route, routing_options, $scope.voeu);

            persistObject.setMessageCallback(function() {
                return '[' + $scope.ueName  + ':' + $scope.cours.type + "] Voeu de " + $scope.voeu.nbHeures + " Heures";
            });

            persistObject.handlePersistError($scope, config.base_uri + '/js/tpl/form/voeu.tpl.html');

            $scope.$watch('voeu.nbHeures', function(newValue, oldValue) {
                if(!persistedQueue.contains(persistObject) && !angular.equals(newValue, 0) && !angular.equals(newValue, undefined) && !angular.equals(newValue, oldValue)) {
                    persistedQueue.push(persistObject);
                }
            });



        }
    }
};