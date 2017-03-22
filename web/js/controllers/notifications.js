/**
 * Created by tanna on 22/03/2017.
 */
module.exports = function($scope, $log, router, config) {

    $scope.annee = "";
    $scope.mois = "";

    $scope.router = router;

    $scope.initialize = function(mois, annee) {
        $scope.mois = mois;
        $scope.annee = annee;
    }
};