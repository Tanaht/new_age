/**
 * Created by Antoine on 17/03/2017.
 */
module.exports = function($log, rest, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/voeu.tpl.html',
        scope: {
            ue: "="
        },
        controller: function($scope) {
            $scope.voeux = [];

            if(config.debugMode)
                $log.debug($scope.ue);

            angular.forEach($scope.ue.cours, function(cours, key) {
               $scope.voeux[cours.id] = ({id: cours.id, cours: cours, nb_heures: 0});
            });

            $scope.computeHeuresTotal = function(cours) {
                return cours.nb_groupe * cours.nb_heure;
            };

            $scope.computeHeuresLibre = function(cours) {
                let total = $scope.computeHeuresTotal(cours);

                let used = 0;

                angular.forEach(cours.voeux, function(voeu, key) {
                    used += voeu.nb_heures;
                });

                return Math.max(total - used, 0);
            };

            $scope.computeHeuresUtiliser = function(cours) {
                return $scope.computeHeuresTotal(cours) - $scope.computeHeuresLibre(cours);
            };

            $scope.computeHeuresEnTrop = function(cours) {
                let total = $scope.computeHeuresTotal(cours);

                let used = 0;

                angular.forEach(cours.voeux, function(voeu, key) {
                    used += voeu.nb_heures;
                });

                return Math.abs(Math.min(total - used, 0));
            };

            $scope.computePourcentageLibre = function(cours) {
                return $scope.computeHeuresLibre(cours) * 100 / ($scope.computeHeuresTotal(cours) + $scope.computeHeuresEnTrop(cours));
            };

            $scope.computePourcentageUtiliser = function(cours) {
                return $scope.computeHeuresUtiliser(cours) * 100 / ($scope.computeHeuresTotal(cours) + $scope.computeHeuresEnTrop(cours));
            };

            $scope.computePourcentageEnTrop = function(cours) {
                return $scope.computeHeuresEnTrop(cours) * 100 / ($scope.computeHeuresTotal(cours) + $scope.computeHeuresEnTrop(cours));
            };

            $scope.computeCoursLabelClass = function(cours) {
                $percentUtiliser = $scope.computePourcentageUtiliser(cours);
                $percentEnTrop = $scope.computePourcentageEnTrop(cours);

                if($percentEnTrop > 0)
                    return 'label-danger';

                if($percentUtiliser == 100)
                    return 'label-success';

                return 'label-info';
            }
        }
    }
};