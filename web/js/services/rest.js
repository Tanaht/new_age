/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($http, $location, $log) {
    //TODO: ne pas oublier d'enlever api_dev.php pour la mise en production
    let base_path = "/new_age/web/app_dev.php/api";
    function successDebug(success) {
        $log.debug("Rest[success:debug]: " + success.config.method + " : " + success.config.url);

    }

    function errorDebug(error) {
        $log.debug("Rest[error:debug]: " + error.config.method + " : " + error.config.url);
    }

    this.headers = {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept': 'application/json'
    };


    this.get = function(url, successCallback, errorCallback){
        let request = $http({
            method: "GET",
            url: url,
            headers: this.headers,
            callback: 'JSON_CALLBACK'
        });

        request.then(
            function(success) {
                if(angular.isDefined(successCallback)) {
                    successCallback(success);
                }
                successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                errorDebug(error);
            }
        );
    };

    this.post = function(url, datas, successCallback, errorCallback) {
        let request = $http({
            method: "POST",
            url: url,
            data: datas,
            headers: this.headers,
            callback: 'JSON_CALLBACK'
        });

        request.then(
            function(success) {
                if(angular.isDefined(successCallback)) {
                    successCallback(success);
                }
                successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                errorDebug(error);
            }
        );
    };


    this.getProfil = function(successCallback, errorCallback) {
        this.get(base_path + "/profil", function(success) {
                if(angular.isDefined(successCallback)) {
                    successCallback(success);
                }
                successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                errorDebug(error);
            })
    }
};