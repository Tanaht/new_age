/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, $sce, $filter, errorManager, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            ueName: '=',
            cours: '=',
            edit: '='
        },
        controller: function($scope, $element) {
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
                delete routing_options.id;
                routing_options.idRelatedCours = $scope.cours.id;
            }
            else {
                $log.error("[Controller:VoeuForm] A voeu can only be linked to one user for the same cours");
            }

            let initializedVoeu = angular.copy($scope.voeu);

            let persistObject = new PersistentObject(route, routing_options, $scope.voeu);
            persistObject.routeGuesser = {
                infinity:  {
                    route: "edit_voeux",
                    options: {
                        idRelatedCours: $scope.cours.id
                    }
                },
            };
            persistObject.setMessageCallback(function() {
                return '[' + $scope.ueName  + ':' + $scope.cours.type + "] Voeu de " + $scope.voeu.nbHeures + " Heures";
            });

            persistObject.handlePersistError($scope, config.base_uri + '/js/tpl/form/voeu.tpl.html');

            $scope.$watch('voeu', function() {
                if(!persistedQueue.contains(persistObject) && persistObject.hasChanged()) {
                    persistedQueue.push(persistObject);
                }

                if(persistedQueue.contains(persistObject) && !persistObject.hasChanged())
                    persistedQueue.remove(persistObject);
            }, true);

            //Remove updated elements from Queue if the underlying etape is trying to change
            //
            $element.on('$destroy', function() {
                if(persistedQueue.contains(persistObject)) {
                    persistedQueue.remove(persistObject);
                }
            });


        }
    }
};