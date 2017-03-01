/**
 * Created by Vostro on 01/03/2017.
 */
module.exports = function($scope, $log) {
    //TODO:typeahead little sample on how to catch typeahead events in angularControllers
    $scope.$on('typeahead', function(event, data) {
        angular.element('#recherche_utilisateur_form_identifiant').val(data.id);
        //$log.debug("[controllers:profil] Typeahead events", event, data);
    });
}
