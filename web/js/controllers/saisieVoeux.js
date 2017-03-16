/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, config) {

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("typeahead event add at input #" + data.options.id + " ==> " + data.object.id);
        angular.element("#" + data.options.id).val(data.object.id);
        //$log.debug("[controllers:profil] Typeahead events", event, data);
    });

};
