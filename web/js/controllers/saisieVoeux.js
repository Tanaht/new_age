/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, $cookies, rest, config) {
    const SELECTED_ETAPE_ID = "selected_etape_id";

    $scope.isEtapeFullyLoaded = false;
    $scope.etape = {};


    $scope.checkCookies = function() {
        let id = $cookies.get(SELECTED_ETAPE_ID);
        if(angular.isUndefined(id))
            return;

        rest.get('get_etape', {id: id}).then(function(success) {
            $scope.etape = success.data;
            $scope.isEtapeFullyLoaded = true;
        })
    };

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);

            rest.get('get_etape', {id: data.object.id}).then(function(success) {
                $scope.etape = success.data;
                $cookies.put(SELECTED_ETAPE_ID, data.object.id);
                $scope.isEtapeFullyLoaded = true;
            });
    });


    $scope.checkCookies();

};
