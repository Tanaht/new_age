/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, $filter, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            ueName: '=',
            cours: '='
        },
        link: function(scope, element, attrs, controller) {
        },
        controller: function($scope) {
            let route = 'new_voeux';
            let options = {id: $scope.cours.id};

            let filtered = $filter('filter')($scope.cours.voeux, {utilisateur: { id: config.user.id }});

            if(filtered.length !== 1) {//assume that a user can be only one voeu for a lesson (if not, we need to change)

                $scope.voeu = { nb_heures:0, utilisateur: config.user.id };
                $scope.cours.voeux.push($scope.voeu);

            }
            else {
                $scope.voeu = filtered[0];

                route = 'edit_voeux';
                options.id = filtered[0].id;
            }

            let persistObject = new PersistentObject(route, options, $scope.voeu, config);
            persistObject.templateUrl = config.base_uri + '/js/tpl/form/voeu.tpl.html';
            persistObject.scope = $scope;

            persistObject.setMessage(function() {
                return '[' + $scope.ueName  + ':' + $scope.cours.type + "] Voeu de " + $scope.voeu.nb_heures + " Heures";
            });

            $scope.$watch('voeu.nb_heures', function(newValue, oldValue) {
                if(!persistedQueue.contains(persistObject) && !angular.equals(newValue, 0) && !angular.equals(newValue, undefined) && !angular.equals(newValue, oldValue)) {
                    persistedQueue.push(persistObject);
                }
            });
        }
    }
};