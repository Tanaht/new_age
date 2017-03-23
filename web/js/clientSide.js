module.exports = function($rootScope, $templateCache, $log, rest, config) {
    //just a little thing to catch typeahead event on the top off Angular App
    if(config.debugMode) {
        $rootScope.$on("typeahead", function($event, data) {
            $log.debug("[ClientSide] Typeahead event catched:", event, data);
        });
    }

    rest.serverErrorCallback = function(error) {
        $log.error(error);
        alert('A server error occured: ' + error.statusText + "\nThanks to contact administrators to report it. More informations in browser console.")
    };

    rest.get('get_profil', {}, function(success) {
        config.user = success.data;
        if(config.debugMode) {
            $log.debug("Configuration:", config);
        }

        $log.debug($rootScope);
        config.initizationCompleted = true;
    });


    $rootScope.isInitializationCompleted = function() {
        return config.initizationCompleted;
    }
};