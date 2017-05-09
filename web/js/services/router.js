/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, config) {

    this.generate = function(route, options, global) {
        let uri = Routing.generate(route, options, global);
        if(config.debugMode && config.debugRouter)
            $log.debug('[Service:router] Generate url: ' + uri);
        return uri;
    };

    this.debug = function() {
        if(config.debugMode) {

            angular.forEach(Routing.getRoutes().a, function(route, key) {
                $log.debug(key, route);
            });
        }
    };

    if(config.debugMode && config.debugRouter) {
        $log.debug('[Service:router] Print routes:');
        this.debug();
    }
};