/**
 * Created by Vostro on 01/03/2017.
 */
module.exports = function($scope, $log) {
    //TODO:typeahead little sample on how to catch typeahead events in angularControllers
    $scope.$on('typeahead', function(event, data) {
        angular.element("#" + data.options.id).val(data.object.id);
        //$log.debug("[controllers:profil] Typeahead events", event, data);
    });
}
