(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', ['ngCookies', 'ui.bootstrap']).provider('config', [require('./providers/config')]).
    controller('notificationsController', ['$scope', '$log', 'router', 'config', require('./controllers/notifications')]).
    controller('profilController', ['$scope', '$log', 'config', require('./controllers/profil')]).
    controller('profilsController', ['$scope', '$log', 'config', require('./controllers/profils')]).
    controller('enseignementsController', ['$scope', '$log', 'config', require('./controllers/enseignements')]).
    controller('saisieVoeuxController', ['$scope', '$log', '$cookies', 'rest', 'config', require('./controllers/saisieVoeux')]).
    service('rest', ["$http", "router", "$log", 'config', require('./services/rest')]).
    service('persistedQueue', ["$log", "rest", "config", require('./services/persistedQueue')]).
    service('router', ['$log', 'config', require('./services/router')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', 'config',  require('./directives/typeahead')]).
    directive('etapeView', ['$log', 'config', require('./directives/etapeView')]).
    directive('ueView', ['$log', 'rest', 'config', require('./directives/ueView')]).
    directive('voeuForm', ['$log', '$filter', 'persistedQueue', 'config', require('./directives/form/voeu')]).
    directive('persistedStateView', ['$log', 'persistedQueue', 'config', require('./directives/persistedStateView')]).
    config(["$logProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$log", "rest", "config", require('./clientSide')])
;
},{"./appConfig":2,"./clientSide":3,"./controllers/enseignements":4,"./controllers/notifications":5,"./controllers/profil":6,"./controllers/profils":7,"./controllers/saisieVoeux":8,"./directives/etapeView":9,"./directives/fileUpload":10,"./directives/form/voeu":11,"./directives/persistedStateView":12,"./directives/prototype":13,"./directives/typeahead":14,"./directives/ueView":15,"./providers/config":16,"./services/persistedQueue":17,"./services/rest":18,"./services/router":19}],2:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($logProvider, $interpolateProvider, configProvider) {
    $logProvider.debugEnabled(configProvider.config.debugMode);
    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');
};
},{}],3:[function(require,module,exports){
module.exports = function($rootScope, $log, rest, config) {

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
    });
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
 * Created by tanna on 22/03/2017.
 */
module.exports = function($scope, $log, router, config) {

    $scope.annee = "";
    $scope.mois = "";

    $scope.initialize = function(mois, annee) {
        $scope.mois = mois;
        $scope.annee = annee;
    };

    $scope.generateUrl = function() {
        return router.generate('visiteur_notifications', { mois: $scope.mois, annee: $scope.annee });
    };

    $scope.generatedFormClasses = function(form, isButton = false) {
        if(!isButton) {
            if (form.$valid)
                return 'has-success';
            else
                return 'has-error';

        }
        else {
            if(!form.$valid) {
                return 'btn-danger disabled';
            }
        }

    }
};
},{}],6:[function(require,module,exports){
/**
 * Created by Antoine on 12/02/2017.
 */
module.exports = function($scope, $log, config) {
    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:profil] Typeahead event", data);
    });
};

},{}],7:[function(require,module,exports){
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

},{}],8:[function(require,module,exports){
/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($scope, $log, $cookies, rest, config) {
    const SELECTED_ETAPE_ID = "selected_etape_id";
    $scope.etape = {};


    $scope.checkCookies = function() {
        let id = $cookies.get(SELECTED_ETAPE_ID);
        if(angular.isUndefined(id))
            return;

        rest.get('get_etape', {id: id}, function(success) {
            $scope.etape = success.data;
        })
    };

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);

            rest.get('get_etape', {id: data.object.id}, function(success) {
                $scope.etape = success.data;
                $cookies.put(SELECTED_ETAPE_ID, data.object.id);
            });
    });


    $scope.checkCookies();

};

},{}],9:[function(require,module,exports){
/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, config) {
    return {
        restrict : 'E',
        templateUrl: config.base_uri + '/js/tpl/etape_view.tpl.html',
        scope: {
            etape: '='
        },
    }
};
},{}],10:[function(require,module,exports){
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
                "</button>";


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
},{}],11:[function(require,module,exports){
/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, $filter, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            ueName: '=',
            cours: '='
        },
        link: function(scope, element, attrs, controller) {
        },
        controller: function($scope) {
            let route = 'new_voeux';
            let options = {id: $scope.cours.id};

            let filtered = $filter('filter')($scope.cours.voeux, {utilisateur: { id: config.user.id }});

            if(filtered.length !== 1) {//assume that a user can be only one voeu for a lesson (if not, we need to change)

                $scope.voeu = { nb_heures:0, utilisateur: config.user.id };
                $scope.cours.voeux.push($scope.voeu);

            }
            else {
                $scope.voeu = filtered[0];

                route = 'edit_voeux';
                options.id = filtered[0].id;
            }

            let persistObject = new PersistentObject(route, options, $scope.voeu, config);

            persistObject.setMessages(function() {
                return '[' + $scope.ueName  + ':' + $scope.cours.type + "] Voeu de " + $scope.voeu.nb_heures + " Heures";
            }, function(error) {
            });

            $scope.$watch('voeu.nb_heures', function(newValue, oldValue) {
                if(!persistedQueue.contains(persistObject) && newValue != 0 && newValue != undefined && newValue != oldValue) {
                    persistedQueue.push(persistObject);
                }
            });
        }
    }
};
},{}],12:[function(require,module,exports){
/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/persisted_state_view.tpl.html',
        controller: function($scope) {

            $scope.popoverTemplate = config.base_uri + "/js/tpl/persisted_state_view_popover.tpl.html";
            $scope.queue = persistedQueue;
            $scope.count = persistedQueue.size();
            $scope.icon = 'floppy-saved';//refresh, floppy-disk, floppy-saved, floppy-remove;

            $scope.$watch('queue.size()', function(newValue) {
                if(angular.isDefined(newValue)) {
                    $scope.count = newValue;
                    if (newValue > 0) {
                        $scope.icon = 'floppy-disk';
                    }
                }
            });

            $scope.classState = function() {
                if ($scope.queue.size() == 0) {
                    return 'btn-default';
                }

                if ($scope.queue.hasNext() && $scope.queue.first().state == config.persistentStates.ERROR_PERSIST) {
                    return 'btn-danger';
                }

                if ($scope.queue.hasNext() && $scope.queue.first().state == config.persistentStates.UN_PERSISTED) {
                    return 'btn-primary';
                }

                return 'btn-success';
            };



            $scope.persist = function() {
                $scope.icon = 'refresh';
                $scope.queue.persist(function() {
                    $scope.icon = "floppy-saved";
                }, function() {
                    $log.debug("remove floppy");
                    $scope.icon = "floppy-remove";
                });
            }
        }
    }
};
},{}],13:[function(require,module,exports){
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
},{}],14:[function(require,module,exports){
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
},{}],15:[function(require,module,exports){
/**
 * Created by Antoine on 17/03/2017.
 */
module.exports = function($log, rest, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/ue_view.tpl.html',
        scope: {
            edit: "=",
            ue: "="
        },
        controller: function($scope) {

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
        }
    }
};
},{}],16:[function(require,module,exports){
module.exports = function() {

    this.config = {
        debugMode: true,
        debugRouter: false,
        debugRest: true,
        debugPersistedQueue: true,
        base_uri: "/new_age/web",
        persistentStates: {
            UN_PERSISTED: 0,
            PERSISTED: 1,
            ON_PERSIST: 99,
            ERROR_PERSIST: -1,
        },
        user: {}
    };

    this.config.rest_uri = this.config.base_uri + "/app_dev.php/api";


    this.$get = function() {
        return this.config;
    }
};
},{}],17:[function(require,module,exports){
/**
 * Created by Antoine on 16/03/2017.
 * This service is used to managed update to database
 */
module.exports = function($log, rest, config) {

    /**
     * History Queue
     * @type {Array}
     */
    this.persistedQueue = [];

    /**
     * Push persistedQueue to queue
     */
    this.push = function(object) {
        object.state = config.persistentStates.UN_PERSISTED;
        this.persistedQueue.push(object);
    };

    this.size = function() {
        return this.persistedQueue.length;
    };


    /**
     * Return whether or not the PersistentObject in parameters is in queue.
     * @param object PersistentObject
     * @returns {boolean}
     */
    this.contains = function(object) {
        return this.persistedQueue.indexOf(object) != -1;
    };

    /**
     * Get head of the queue
     * @returns undefined|PersistentObject
     */
    this.first = function() {
        if(!this.hasNext())
            return undefined;

        for(let i = 0 ; i < this.persistedQueue.length ; i++) {
            if(this.persistedQueue[i].state != config.persistentStates.ON_PERSIST)
                return this.persistedQueue[0];
        }
        return this.persistedQueue[0];
    };

    /**
     * remove PersistentObject from the queue
     * @param object PersistentObject
     */
    this.remove = function(object) {
        if(this.contains(object)) {
            this.persistedQueue.splice(this.persistedQueue.indexOf(object), 1);
        }
    };


    this.hasNext = function() {
        return this.size() > 0;
    };


    /**
     * Persist all PersistentObjects from persistedQueueQueue
     * @param onPersistedSuccess: promise callable called when all queue is persisted.
     * @param onPersistedFailure: promise callable called when an error occured.
     */
    this.persist = function(onPersistedSuccess, onPersistedFailure) {
        let self = this;

        if(!this.hasNext()) {
            if(angular.isDefined(onPersistedSuccess))
                onPersistedSuccess();
            return;
        }

        let po = this.first();

        if(po.state === config.persistentStates.PERSISTED) {
            self.remove(po);
            self.persist(onPersistedSuccess, onPersistedFailure);
            return;
        }

        po.persist(rest, function(success) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.debug("[Service:persistedQueue] Success Persist");

            self.remove(po);
            self.persist(onPersistedSuccess, onPersistedFailure);
        }, function(error) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.error("[Service:persistedQueue] Error Persist");

            if(angular.isDefined(onPersistedFailure)) {
                $log.debug("call onPersistedFailure");
                onPersistedFailure();
            }
            else {
                $log.debug(onPersistedFailure);
            }
        });
    }
};
},{}],18:[function(require,module,exports){
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
},{}],19:[function(require,module,exports){
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
},{}]},{},[1]);
