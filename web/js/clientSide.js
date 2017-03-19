module.exports = function($rootScope, $log, rest, config) {

    //just a little thing to catch typeahead event on the top off Angular App
    if(config.debugMode) {
        $rootScope.$on("typeahead", function($event, data) {
            $log.debug("[ClientSide] Typeahead event catched:", event, data);
        });
    }

    rest.get('get_profil', {}, function(success) {
        config.user = success.data;

        if(config.debugMode) {
            $log.debug("Configuration:", config);
        }
    });
};