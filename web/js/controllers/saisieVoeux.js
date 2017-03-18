/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, rest ,config, router) {
    $scope.ues = [];

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);


            rest.get('get_etape_ues', {id: data.object.id}, function(success) {
                $scope.ues = success.data;
                $log.debug(success);
            }, function(error) {
                $log.debug(error);
            })
    });

};
