(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    provider('config', [require('./providers/config')]).
    controller('profilController', ['$scope', '$log', 'config', require('./controllers/profil')]).
    controller('profilsController', ['$scope', '$log', 'config', require('./controllers/profils')]).
    controller('enseignementsController', ['$scope', '$log', 'config', require('./controllers/enseignements')]).
    controller('saisieVoeuxController', ['$scope', '$log', 'rest', 'config', require('./controllers/saisieVoeux')]).
    service('rest', ["$http", "$location", "$log", 'config', require('./services/rest')]).
    service('history', ["$log", "rest", "config", require('./services/history')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', 'config',  require('./directives/typeahead')]).
    directive('voeu', ['$log', 'rest', 'config', require('./directives/voeu')]).
    config(["$logProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$log", "config", require('./clientSide')])
;
},{"./appConfig":2,"./clientSide":3,"./controllers/enseignements":4,"./controllers/profil":5,"./controllers/profils":6,"./controllers/saisieVoeux":7,"./directives/fileUpload":8,"./directives/prototype":9,"./directives/typeahead":10,"./directives/voeu":11,"./providers/config":12,"./services/history":13,"./services/rest":14}],2:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($logProvider, $interpolateProvider, configProvider) {
    $logProvider.debugEnabled(configProvider.config.debugMode);
    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');
};
},{}],3:[function(require,module,exports){
module.exports = function($rootScope, $log, config) {

    //just a little thing to catch typeahead event on the top off Angular App
    if(config.debugMode) {
        $rootScope.$on("typeahead", function($event, data) {
            $log.debug("[ClientSide] Typeahead event catched:", event, data);
        });
    }
};
},{}],4:[function(require,module,exports){
/**
 * Created by tanna on 15/03/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        angular.element("#" + data.options.id).val(data.object.id);
        if(config.debugMode) {
            $log.debug("[controllers:enseignements] Typeahead event", data);
        }
    });

};

},{}],5:[function(require,module,exports){
/**
 * Created by Antoine on 12/02/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:profil] Typeahead event", data);
    });
};

},{}],6:[function(require,module,exports){
/**
 * Created by Vostro on 01/03/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        angular.element("#" + data.options.id).val(data.object.id);
        if(config.debugMode)
            $log.debug("[controllers:profils] Typeahead event", data);
    });
};

},{}],7:[function(require,module,exports){
/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, rest ,config) {

    $scope.ues = [];

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);

        if(angular.isUndefined(data.options) || angular.isUndefined(data.options.route) || angular.isUndefined(data.options.params)) {
            $log.error("[controllers:saisieVoeux] Typeahead event expected options for routing got: ", data);
        }
        else {
            let url = data.options.route.replace(data.options.params.id, data.object.id);

            rest.get(url, function(success) {
                $scope.ues = success.data;
                $log.debug(success);
            }, function(error) {
                $log.debug(error);
            })
        }
    });

};

},{}],8:[function(require,module,exports){
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
},{}],9:[function(require,module,exports){
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

            let removeItemButton = '<button type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span></button>';

            //Method requested when user click on the red cancel button (the event pass the item to delete in parameters
            scope.deleteClickListener = function(event) {
                event.data.item.remove();
            };

            //Method requested when user click on the green plus button
            scope.addClickListener = function(event) {
                let clone = angular.element(scope.prototype).clone().html();

                clone = clone
                    .replace(/__name__label__/g, what)
                    .replace(/__name__/g, length++)
                ;

                if(angular.isDefined(scope.allowDelete)) {
                    let removeItemButtonCloned = angular.element(removeItemButton).clone();

                    clone = angular.element(clone).append(removeItemButtonCloned);

                    removeItemButtonCloned.on('click', {item: clone }, function (event) {
                        scope.deleteClickListener(event)
                    });
                }

                if(length > 1)
                    element.find('[collection-item]').last().after(clone);
                else
                    element.find("button.typeahead-add-btn").before(clone);
            };


            //Add listener to elements if collection allow add event.
            if(angular.isDefined(scope.allowAdd)) {
                let addItemButton = '<button type="button" class="typeahead-add-btn btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></button>';
                let length = element.find('[collection-item]').length;

                element.append(addItemButton);

                element.find('button').on('click', function(event) {
                    scope.addClickListener(event)
                });
            }

            //Add listener to elements if collection allow delete event.
            if(angular.isDefined(scope.allowDelete)) {
                angular.forEach(element.find('[collection-item]'), function(collectionItem, key) {
                    let removeItemButtonClonednoConflict = angular.element(removeItemButton).clone();

                    angular.element(collectionItem).append(removeItemButtonClonednoConflict);

                    removeItemButtonClonednoConflict.on('click', {item: collectionItem },  function (event) {
                        scope.deleteClickListener(event);
                    });
                });
            }
        }
    }
};
},{}],10:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($log, config) {
    return {
        restrict: 'A',
        scope: {
            typeahead:"=",
            display:"@",
            url: '@',
            eventSuffix: "@",
            options: "=",
        },
        link: function(scope, element, attributes){
            let searcher = new Bloodhound({
                datumTokenizer: function(datum) {//TODO: this is a fix to allow real autocomplete maybe to costly.
                    let tokens = Bloodhound.tokenizers.whitespace(datum[scope.display]);
                    $.each(tokens,function(k,v){

                        for(let i = 1 ; i < v.length ; i++) {
                            tokens.push(v.substr(i,v.length));
                        }
                    });
                    //$log.debug(tokens);
                    return tokens;
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: scope.url,
                    cache: false
                },
                identify: function(objet) {
                    if(angular.isUndefined(objet.id))
                        $log.error('[directives:typeahead]Cannot retrieve id property of items at ' + scope.url);
                    return objet.id;
                },
                /*remote: {
                    url: '../data/films/queries/%QUERY.json',
                    wildcard: '%QUERY'
                }*/
            });

            element.typeahead({
                showHintOnFocus: true,
                displayText: function(object){ return object[scope.display];},
                source: searcher.ttAdapter(),
                updater: function(selectedValue) {
                    //if(config.debugMode)
                    //    $log.debug("Typeahead event :" + selectedValue);
                    scope.select(selectedValue);
                    return selectedValue;
                }
            })
            ;
        },
        controller: function($scope) {

            if(config.debugMode)
                $log.debug(config);

            $scope.eventName = "typeahead";

            if(angular.isDefined($scope.eventSuffix)) {
                $scope.eventName += ':' +  $scope.eventSuffix;
            }

            $scope.select = function(selectedValue) {
                $log.debug("Typeahead find that object:", selectedValue);
                $scope.$emit($scope.eventName, {object: selectedValue, options: $scope.options });
            }
        }
    };
};
},{}],11:[function(require,module,exports){
/**
 * Created by Antoine on 17/03/2017.
 */
module.exports = function($log, rest, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/voeu.tpl.html',
        scope: {
            ue: "="
        },
        controller: function($scope) {
            $scope.voeux = [];

            if(config.debugMode)
                $log.debug($scope.ue);

            angular.forEach($scope.ue.cours, function(cours, key) {
               $scope.voeux[cours.id] = ({id: cours.id, cours: cours, nb_heures: 0});
            });

            $scope.computeHeuresTotal = function(cours) {
                return cours.nb_groupe * cours.nb_heure;
            };

            $scope.computeHeuresLibre = function(cours) {
                let total = $scope.computeHeuresTotal(cours);

                let used = 0;

                angular.forEach(cours.voeux, function(voeu, key) {
                    used += voeu.nb_heures;
                });

                return Math.max(total - used, 0);
            };

            $scope.computeHeuresUtiliser = function(cours) {
                return $scope.computeHeuresTotal(cours) - $scope.computeHeuresLibre(cours);
            };

            $scope.computeHeuresEnTrop = function(cours) {
                let total = $scope.computeHeuresTotal(cours);

                let used = 0;

                angular.forEach(cours.voeux, function(voeu, key) {
                    used += voeu.nb_heures;
                });

                return Math.abs(Math.min(total - used, 0));
            };

            $scope.computePourcentageLibre = function(cours) {
                return $scope.computeHeuresLibre(cours) * 100 / ($scope.computeHeuresTotal(cours) + $scope.computeHeuresEnTrop(cours));
            };

            $scope.computePourcentageUtiliser = function(cours) {
                return $scope.computeHeuresUtiliser(cours) * 100 / ($scope.computeHeuresTotal(cours) + $scope.computeHeuresEnTrop(cours));
            };

            $scope.computePourcentageEnTrop = function(cours) {
                return $scope.computeHeuresEnTrop(cours) * 100 / ($scope.computeHeuresTotal(cours) + $scope.computeHeuresEnTrop(cours));
            };

            $scope.computeCoursLabelClass = function(cours) {
                $percentUtiliser = $scope.computePourcentageUtiliser(cours);
                $percentEnTrop = $scope.computePourcentageEnTrop(cours);

                if($percentEnTrop > 0)
                    return 'label-danger';

                if($percentUtiliser == 100)
                    return 'label-success';

                return 'label-info';
            };
            
            $scope.send = function () {
                rest.post(config.rest_uri + '/etapes/4/ues', $scope.voeux);
            }
        }
    }
};
},{}],12:[function(require,module,exports){
module.exports = function() {

    this.config = {
        debugMode: true,
        base_uri: "/new_age/web",
    };

    this.config.rest_uri = this.config.base_uri + "/app_dev.php/api";


    //TODO cannot be injected in controller or services
    this.$get = function() {
        return this.config;
    }
};
},{}],13:[function(require,module,exports){
/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($log, rest, config) {
    /**
     * History Queue
     * @type {Array}
     */
    this.history = [];

    /**
     * Push history to queue
     */
    this.push = function(object) {
        this.history.push(object);
    };

    /**
     * poll history from queue
     */
    this.poll = function() {
        let object = this.history[0];
        return this.history.shift();
    };
};
},{}],14:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports = function($http, $location, $log, config) {
    //TODO: ne pas oublier d'enlever api_dev.php pour la mise en production
    let base_path = config.rest_uri;
    function successDebug(success) {
        $log.debug("Rest[success:debug]: " + success.config.method + " : " + success.config.url);

    }

    function errorDebug(error) {
        $log.debug("Rest[error:debug]: " + error.config.method + " : " + error.config.url);
    }

    this.headers = {
        'Content-Type': 'application/json; charset=UTF-8',
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
                if(config.debugMode)
                    successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                if(config.debugMode)
                    errorDebug(error);
            }
        );
    };

    this.post = function(url, datas, successCallback, errorCallback) {
        let request = $http({
            method: "POST",
            url: url,
            data: { datas: datas },
            headers: this.headers,
            callback: 'JSON_CALLBACK'
        });

        request.then(
            function(success) {
                if(angular.isDefined(successCallback)) {
                    successCallback(success);
                }
                if(config.debugMode)
                    successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                if(config.debugMode)
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
                if(config.debugMode)
                    successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                if(config.debugMode)
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
                if(config.debugMode)
                    successDebug(success);
            },
            function(error) {
                if(angular.isDefined(errorCallback)) {
                    errorCallback(error);

                }
                if(config.debugMode)
                    errorDebug(error);
            })
    }
};
},{}]},{},[1]);
