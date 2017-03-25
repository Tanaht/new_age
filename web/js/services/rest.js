/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($q, $http, router, $log, config) {

    function successDebug(success) {
        $log.debug("Rest[success:debug]: " + success.config.method + " : " + success.config.url);
    }

    function errorDebug(error) {
        $log.debug("Rest[error:debug]: " + error.config.method + " : " + error.config.url);
        $log.error(error);
    }

    this.headers = {
        'Content-Type': 'application/json; charset=UTF-8',
        'Accept': 'application/json'
    };

    //server error callback
    this.serverErrorCallback = undefined;


    /**
     *
     * @param route
     * @param options
     * @returns {jQuery.promise|promise|*|e}
     */
    this.get = function(route, options){
        let deferred = $q.defer();
        let self = this;

        let request = $http({
            method: "GET",
            url: router.generate(route, options),
            headers: this.headers,
            callback: 'JSON_CALLBACK'
        });

        request.then(
            function(success) {
                if(config.debugMode && config.debugRest)
                    successDebug(success);

                deferred.resolve(success);
            },
            function(error) {
                if(angular.equals(error.status, 500)) {
                    if(angular.isDefined(self.serverErrorCallback) && angular.isFunction(self.serverErrorCallback)) {
                        self.serverErrorCallback(error);
                    }
                }
                else {
                    if(config.debugMode && config.debugRest)
                        errorDebug(error);
                    deferred.reject(error);
                }
            }
        );

        return deferred.promise;
    };

    /**
     * @param route
     * @param options
     * @param datas
     * @returns {jQuery.promise|promise|*|e}
     */
    this.post = function(route, options, datas) {
        let deferred = $q.defer();
        let self = this;
        let request = $http({
            method: "POST",
            url: router.generate(route, options),
            data: { datas: datas },
            headers: this.headers,
            callback: 'JSON_CALLBACK'
        });

        request.then(
            function(success) {
                if(config.debugMode && config.debugRest)
                    successDebug(success);

                deferred.resolve(success);
            },
            function(error) {
                if(angular.equals(error.status, 500)) {
                    if(angular.isDefined(self.serverErrorCallback) && angular.isFunction(self.serverErrorCallback)) {
                        self.serverErrorCallback(error);
                    }
                }
                else {
                    if(config.debugMode && config.debugRest)
                        errorDebug(error);

                    deferred.reject(error);
                }
            }
        );

        return deferred.promise;
    };
};