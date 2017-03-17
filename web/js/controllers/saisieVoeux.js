/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, rest ,config) {

    $scope.ues = [];

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);

        if(angular.isUndefined(data.options) || angular.isUndefined(data.options.route) || angular.isUndefined(data.options.params)) {
            $log.error("[controllers:saisieVoeux] Typeahead event expected options for routing got: ", data);
        }
        else {
            let url = data.options.route.replace(data.options.params.id, data.object.id);

            rest.get(url, function(success) {
                $scope.ues = success.data;
                $log.debug(success);
            }, function(error) {
                $log.debug(error);
            })
        }
    });

};
