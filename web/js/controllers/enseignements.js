/**
 * Created by tanna on 15/03/2017.
 */
module.exports = function($scope, $log) {

    $scope.$on('typeahead', function(event, data) {
        $log.debug("[controllers:enseignements] Typeahead events", event, data);
        angular.element("#" + data.options.id).val(data.object.id);
    });

};
