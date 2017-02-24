/**
 * Created by Antoine on 12/02/2017.
 */
module.exports = function($scope, $log) {
    //TODO:typeahead little sample on how to catch typeahead events in angularControllers
    $scope.$on('typeahead', function(event, data) {
        $log.debug("[controllers:profil] Typeahead events", event, data);
    });
}
