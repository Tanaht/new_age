/**
 * Created by Antoine on 12/02/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:profil] Typeahead events", event, data);
    });
};
