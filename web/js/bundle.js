(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    controller('profilUpdate', ['$scope', '$log', 'rest', require('./controllers/profil/update')]).
    service('rest', ["$http", "$location", "$log", require('./services/rest')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', require('./directives/typeahead')]).
    config(["$logProvider", "$interpolateProvider",require("./appConfig")]);
},{"./appConfig":2,"./controllers/profil/update":3,"./directives/fileUpload":4,"./directives/prototype":5,"./directives/typeahead":6,"./services/rest":7}],2:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($logProvider, $interpolateProvider) {
    $logProvider.debugEnabled(true);
    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');
};
},{}],3:[function(require,module,exports){
/**
 * Created by Antoine on 12/02/2017.
 */
module.exports = function($scope, $log, rest) {
    //profil_update_controller
    /*
    $log.info("profilUpdateController is working");

    $scope.profil = {};

    rest.getProfil(function(success) {
        $scope.profil = success.data;
    });
    */
};
},{}],4:[function(require,module,exports){
module.exports = function ($log) {

    return {
        restrict: 'A',
        link: function(scope, element, attributes){
            if(!scope.isInputFileNode(element)) {
                scope.log('error',"Attempt to apply a file upload directive to a wrong node");
                return;
            }
            element.addClass('hide');
            let hasError = element.parent().hasClass('has-error');
            $log.debug(hasError);
            let uploadFileButton =
                "<button type='button' id='directives-file-upload-button' class='btn btn-file btn-block " + (hasError ? 'btn-danger' : '') + "'>" +
                    "<span class='glyphicon glyphicon-cloud-upload'></span>" +
                    " Modifier Image" +
                "</button>"


            element.after(uploadFileButton);

            element.parent()
                .find('#directives-file-upload-button')
                .on('click', element, scope.triggerUploadWindow)
            ;

            element.change(element, scope.autoSubmitForm);
            scope.log('debug', "File Upload Enabled");
        },
        controller:  function($scope) {
            $scope.log = function(type, message) {
                let prefix = "[directives:fileUpload]";
                switch (type) {
                    case 'warn':
                        $log.warn(prefix + message);
                        break;
                    case 'debug':
                        $log.debug(prefix + message);
                        break;
                    case 'info':
                        $log.info(prefix + message);
                        break;
                    case 'error':
                        $log.error(prefix + message);
                        break;
                    default:
                        $log.log(prefix + message);
                }
            };

            $scope.isInputFileNode = function(node) {
                return node.prop('tagName') == 'INPUT' && node.prop('type') == 'file';
            };

            $scope.triggerUploadWindow = function(event) {
                if(!$scope.isInputFileNode(event.data))
                    $scope.log('error', 'Attempt to apply a file upload directive to a wrong node');
                event.data.trigger('click');
            };

            $scope.autoSubmitForm = function(event) {
                if(!$scope.isInputFileNode(event.data))
                    $scope.log('error', 'Attempt to apply a file upload directive to a wrong node');

                //TODO: if this auto submit functionality is used in more than input type it can be useful to detach from it.
                $scope.log('debug', 'auto submit form requested');

                event.data.closest('form').submit();

            };
        },
    }
}
},{}],5:[function(require,module,exports){
/**
 * Created by Antoine on 12/02/2017.
 */
/*
 * angular directive for data-prototype on Symfony Form Collection type
 */
module.exports = function($log) {
    return {
        restrict: 'A',
        scope: {
            prototype:"@",
            allowAdd:"=",
            allowDelete:"=",
            what:"=",
        },
        link: function(scope, element, attributes){
            let what = "";
            if(angular.isDefined(scope.what))
                what = scope.what;

            let removeItemButton = '<button type="button" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-remove-sign"></span></button>';

            if(angular.isDefined(scope.allowDelete)) {
                //TODO: script to add delete button on items (and add onClickListener)
                /*$log.debug(scope.allowDelete);

                angular.forEach(element.find(angular.element(scope.prototype).prop('tagName')), function(value, key) {
                    $log.debug(value);
                    value.prepend()
                });*/
            }

            if(angular.isDefined(scope.allowAdd)) {
                let addItemButton = '<button type="button" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></button>';
                let length = element.find('[collection-item]').length;

                element.append(addItemButton);

                element.find('button').on('click', function (event) {
                    let clone = angular.element(scope.prototype).clone().html();

                    clone = clone
                        .replace(/__name__label__/g, what)
                        .replace(/__name__/g, length++)
                    ;

                    if(angular.isDefined(scope.allowDelete)) {
                        //TODO: script to add delete button in clone (and add onClick listener)
                    }

                    element.find('[collection-item]').last().after(clone);
                })
            }
        }
    }
};
},{}],6:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($log, rest) {
    return {
        restrict: 'A',
        scope: {
            typeahead:"=",
            url: '=',
        },
        link: function(scope, element, attributes){
            console.log(scope.typeahead, scope.url);

            rest.get(scope.url, function(success) {

                let usernames = success.data;

                //TODO: peut-etre retravailler cette partie pour permettre l'auto-complétion d'objets....
                let substringMatcher = function(strs) {
                    return function findMatches(q, cb) {
                        let matches, substrRegex;

                        // an array that will be populated with substring matches
                        matches = [];

                        // regex used to determine if a string contains the substring `q`
                        substrRegex = new RegExp(q, 'i');

                        // iterate through the pool of strings and for any string that
                        // contains the substring `q`, add it to the `matches` array
                        $.each(strs, function(i, str) {
                            if (substrRegex.test(str)) {
                                matches.push(str);
                            }
                        });

                        cb(matches);
                    };
                };

                element.typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                    {
                        name: scope.typeahead,
                        source: substringMatcher(usernames)
                    });

            });
        }
    };
};
},{}],7:[function(require,module,exports){
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

    this.put = function(url, datas, successCallback, errorCallback) {
        let request = $http({
            method: "PUT",
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


    //===========================================================


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
},{}]},{},[1]);
