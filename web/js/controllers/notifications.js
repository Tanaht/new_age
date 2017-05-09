/**
 * Created by tanna on 22/03/2017.
 */
module.exports = function($scope, $log, router, config) {

    $scope.annee = "";
    $scope.mois = "";

    $scope.initialize = function(mois, annee) {
        $scope.mois = mois;
        $scope.annee = annee;
    };

    $scope.generateUrl = function() {
        return router.generate('visiteur_notifications', { mois: $scope.mois, annee: $scope.annee });
    };

    $scope.generatedFormClasses = function(form, isButton = false) {
        if(!isButton) {
            if (form.$valid)
                return 'has-success';
            else
                return 'has-error';

        }
        else {
            if(!form.$valid) {
                return 'btn-danger disabled';
            }
        }

    }
};