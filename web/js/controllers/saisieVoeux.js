/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, config) {

    $scope.$on('typeahead', function(event, data) {
        angular.element("#" + data.options.id).val(data.object.id);
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);
    });

};
