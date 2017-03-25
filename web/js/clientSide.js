module.exports = function($rootScope, $templateCache, $location, $cookies, $log, rest, config) {

    if(config.debugMode) {
        $rootScope.$on("typeahead", function($event, data) {
            $log.debug("[ClientSide] Typeahead event catched:", event, data);
        });

        $log.debug("Configuration:", config);
    }

    rest.serverErrorCallback = function(error) {
        $log.error(error);
        alert('A server error occured: ' + error.statusText + "\nThanks to contact administrators to report it. More informations in browser console.")
    };



    let profilCookie = $cookies.get('profil');

    if(angular.isDefined(profilCookie)) {
        config.user = profilCookie;
        config.initizationCompleted = true;
        angular.element('body').removeClass('hide');
    }
    else {
        rest.get('get_profil', {}, function(success) {
            config.user = success.data;
            config.initizationCompleted = true;
            $cookies.putObject('profil', config.user);
            angular.element('body').removeClass('hide');
        });
    }



    $rootScope.isInitializationCompleted = function() {
        return config.initizationCompleted;
    }
};