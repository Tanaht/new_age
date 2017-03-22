/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($http, router, $log, config) {
    //TODO: ne pas oublier d'enlever api_dev.php pour la mise en production
    let base_path = config.rest_uri;
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


    this.get = function(route, options, successCallback, errorCallback){
        let self = this;
        let request = $http({
            method: "GET",
            url: router.generate(route, options),
            headers: this.headers,
            callback: 'JSON_CALLBACK'
        });

        request.then(
            function(success) {
                if(angular.isDefined(successCallback)) {
                    successCallback(success);
                }
                if(config.debugMode && config.debugRest)
                    successDebug(success);
            },
            function(error) {
                if(error.status == 500) {
                    if(angular.isDefined(errorCallback)) {
                        self.serverErrorCallback(error);
                    }
                }
                else {
                    if(angular.isDefined(errorCallback)) {
                        errorCallback(error);

                    }
                    if(config.debugMode && config.debugRest)
                        errorDebug(error);
                }
            }
        );
    };

    this.post = function(route, options, datas, successCallback, errorCallback) {
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
                if(angular.isDefined(successCallback)) {
                    successCallback(success);
                }
                if(config.debugMode && config.debugRest)
                    successDebug(success);
            },
            function(error) {
                if(error.status == 500) {
                    if(angular.isDefined(errorCallback)) {
                        self.serverErrorCallback(error);
                    }
                }
                else {

                    if(angular.isDefined(errorCallback)) {
                        errorCallback(error);

                    }
                    if(config.debugMode && config.debugRest)
                        errorDebug(error);
                }
            }
        );
    };
};