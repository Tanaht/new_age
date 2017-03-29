/**
 * Created by Vostro on 01/03/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        angular.element("#" + data.options.id).val(data.object.id);
        if(config.debugMode)
            $log.debug("[controllers:profils] Typeahead event", data);
    });
};
