/**
 * Created by tanna on 15/03/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode) {
            $log.debug("[controllers:missions] Typeahead event", data);
        }
    });

};
