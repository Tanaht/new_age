/**
 * Created by Vostro on 01/03/2017.
 */
module.exports = function($scope, $log) {
    //TODO:typeahead little sample on how to catch typeahead events in angularControllers
    $log.debug("ready");
    $scope.$on('typeahead', function(event, data) {
        $log.debug("typeahead event add at input #" + data.options.id + " ==> " + data.object.id);
        angular.element("#" + data.options.id).val(data.object.id);
        //$log.debug("[controllers:profil] Typeahead events", event, data);
    });
}
