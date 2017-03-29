(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', ['ngCookies', 'ui.bootstrap']).
    provider('config', [require('./providers/config')]).

    factory('modals', ['$q', '$log', 'symfonyErrorManager', '$uibModal', 'config', require('./factories/modals')]).

    controller('profilController', ['$scope', '$log', 'config', require('./controllers/profil')]).
    controller('profilsController', ['$scope', '$log', 'config', require('./controllers/profils')]).
    controller('enseignementsController', ['$scope', '$log', 'config', require('./controllers/enseignements')]).
    controller('saisieVoeuxController', ['$scope', '$log', '$cookies', 'rest', 'config', require('./controllers/saisieVoeux')]).

    service('rest', ["$q", "$http", "router", "$log", 'config', require('./services/rest')]).
    service('errorManager', ["$log", "$parse", require('./services/errorManagerOld')]).
    service('symfonyErrorManager', ["$log", "$parse", "$sce", require('./services/symfonyErrorManager')]).
    service('persistedQueue', ["$q", "$log", "rest", "config", require('./services/persistedQueue')]).
    service('router', ['$log', 'config', require('./services/router')]).

    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', 'config',  require('./directives/typeahead')]).
    directive('etapeView', ['$log', 'config', require('./directives/etapeView')]).
    directive('ueView', ['$log', 'config', require('./directives/ueView')]).
    directive('voeuForm', ['$log', '$sce', '$filter', 'symfonyErrorManager', 'persistedQueue', 'config', require('./directives/form/voeu')]).
    directive('persistedStateView', ['$log', "modals", 'persistedQueue', 'config', require('./directives/persistedStateView')]).
    directive('userLink', ['$log', 'rest', 'config', require('./directives/userLink')]).

    config(["$provide", "$logProvider", "$qProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$templateCache", "$location", "$cookies", "$log", "rest", "config", require('./clientSide')])
;
},{"./appConfig":2,"./clientSide":3,"./controllers/enseignements":4,"./controllers/profil":5,"./controllers/profils":6,"./controllers/saisieVoeux":7,"./directives/etapeView":8,"./directives/fileUpload":9,"./directives/form/voeu":10,"./directives/persistedStateView":11,"./directives/prototype":12,"./directives/typeahead":13,"./directives/ueView":14,"./directives/userLink":15,"./factories/modals":16,"./providers/config":17,"./services/errorManagerOld":18,"./services/persistedQueue":19,"./services/rest":20,"./services/router":21,"./services/symfonyErrorManager":22}],2:[function(require,module,exports){
/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($provide, $logProvider, $qProvider, $interpolateProvider, configProvider) {
    ngLogger = angular.injector(['ng']).get('$log');

    $logProvider.debugEnabled(configProvider.config.debugMode);
    $qProvider.errorOnUnhandledRejections(configProvider.config.debugMode);

    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');

    /*
     * Decorator: here we decorate UI-Bootstrap directives to fit the application needs
     * Little sample:
    */
    /*$provide.decorator('uibAccordionDirective', function($delegate) {

        ngLogger.debug($delegate);
        let directive = $delegate[0];

        angular.extend(directive, {
            replace: true,
        });

        return $delegate;
    });*/
};
},{}],3:[function(require,module,exports){
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



    let profilCookie = $cookies.getObject('profil');

    if(angular.isDefined(profilCookie)) {
        config.user = profilCookie;
        config.initializationCompleted = true;
        angular.element('body').removeClass('hide');
    }
    else {
        rest.get('get_profil', {}).then(function(success) {
            config.user = success.data;
            config.initializationCompleted = true;
            $cookies.putObject('profil', config.user);
            angular.element('body').removeClass('hide');
        });
    }



    $rootScope.isInitializationCompleted = function() {
        return config.initializationCompleted;
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
module.exports = function($scope, $log, $cookies, rest, config) {
    const SELECTED_ETAPE_ID = "selected_etape_id";

    $scope.isEtapeFullyLoaded = false;
    $scope.etape = undefined;



    $scope.checkCookies = function() {
        let id = $cookies.get(SELECTED_ETAPE_ID);
        if(angular.isUndefined(id))
            return;

        rest.get('get_etape', {id: id}).then(function(success) {
            $scope.etape = success.data;
            $scope.isEtapeFullyLoaded = true;
        })
    };

    $scope.$on('typeahead', function(event, data) {
        if(config.debugMode)
            $log.debug("[controllers:saisieVoeux] Typeahead event", data);

            rest.get('get_etape', {id: data.object.id}).then(function(success) {
                $scope.etape = success.data;
                $cookies.put(SELECTED_ETAPE_ID, data.object.id);
                $scope.isEtapeFullyLoaded = true;
            });
    });


    $scope.checkCookies();

};

},{}],8:[function(require,module,exports){
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
},{}],9:[function(require,module,exports){
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
},{}],10:[function(require,module,exports){
/**
 * Created by Antoine on 18/03/2017.
 */
module.exports = function($log, $sce, $filter, errorManager, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/form/voeu.tpl.html',
        scope: {
            ueName: '=',
            cours: '='
        },
        controller: function($scope) {
            $scope.errm = errorManager;

            let route = 'new_voeux';
            let routing_options = {id: $scope.cours.id};

            let filtered = $filter('filter')($scope.cours.voeux, {utilisateur: { id: config.user.id }});

            if(filtered.length === 0) {

                $scope.voeu = {
                    nbHeures: 0,
                    utilisateur: config.user.id
                };

                $scope.cours.voeux.push($scope.voeu);

            }
            else if(filtered.length === 1){
                $scope.voeu = filtered[0];

                route = 'edit_voeux';
                routing_options.id = filtered[0].id;
            }
            else {
                $log.error("[Controller:VoeuForm] A voeu can only be linked to one user for the same cours");
            }

            let persistObject = new PersistentObject(route, routing_options, $scope.voeu);

            persistObject.setMessageCallback(function() {
                return '[' + $scope.ueName  + ':' + $scope.cours.type + "] Voeu de " + $scope.voeu.nbHeures + " Heures";
            });

            persistObject.handlePersistError($scope, config.base_uri + '/js/tpl/form/voeu.tpl.html');

            $scope.$watch('voeu.nbHeures', function(newValue, oldValue) {
                if(!persistedQueue.contains(persistObject) && !angular.equals(newValue, 0) && !angular.equals(newValue, undefined) && !angular.equals(newValue, oldValue)) {
                    persistedQueue.push(persistObject);
                }
            });



        }
    }
};
},{}],11:[function(require,module,exports){
/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, modals, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/persisted_state_view.tpl.html',
        controller: function($scope) {
            $scope.errorModalReason = {
                persistAll : 'persist-all',
                persistThis: 'persist-this',
                noPersist: 'no-persist',
            };

            $scope.popoverOpened = false;

            $scope.popoverTemplate = config.base_uri + "/js/tpl/popover/persisted_state_view.tpl.html";
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
                if (angular.equals($scope.queue.size(), 0)) {
                    return 'btn-default';
                }

                if ($scope.queue.hasNext() && angular.equals($scope.queue.first().state, config.persistentStates.ERROR_PERSIST)) {
                    return 'btn-danger';
                }

                if ($scope.queue.hasNext() && angular.equals($scope.queue.first().state, config.persistentStates.UN_PERSISTED)) {
                    return 'btn-primary';
                }

                return 'btn-success';
            };


            /**
             *
             * @param persistentObject
             */
            $scope.persistOne = function(persistentObject) {
                $scope.popoverOpened = true;
                $scope.icon = 'refresh';


                $scope.queue.persistOne(persistentObject).then(function() {
                    $scope.icon = "floppy-saved";
                    $scope.popoverOpened = false;
                }, function() {
                    $scope.icon = "floppy-remove";
                    $scope.popoverOpened = true;
                });
            };

            $scope.persist = function($event) {
                if(angular.isDefined($event))
                    $event.stopPropagation();
                $scope.popoverOpened = true;
                $scope.icon = 'refresh';

                $scope.queue.persist().then(function() {
                    $scope.icon = "floppy-saved";
                    $scope.popoverOpened = false;
                }, function() {
                    $scope.icon = "floppy-remove";
                    $scope.popoverOpened = true;
                });
            };


            $scope.openErrorModal = function(po) {

                if(po.persistErrorHandled) {
                    let persistentObjectModalPromise = modals.persistentObjectModal(po, {size: 'lg'});

                    persistentObjectModalPromise.then(function(resolvedReason) {
                        switch(resolvedReason) {
                            case 'saveThis':
                                $scope.persistOne(po);
                                break;
                            case 'save':
                                $scope.persist();
                                break;
                        }
                    });
                }
            }
        }
    }
};
},{}],12:[function(require,module,exports){
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
},{}],13:[function(require,module,exports){
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
},{}],14:[function(require,module,exports){
/**
 * Created by Antoine on 17/03/2017.
 */
module.exports = function($log, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/ue_view.tpl.html',
        scope: {
            edit: "=",
            ue: "="
        },
        controller: function($scope) {

            $scope.computeHeuresTotal = function(cours) {
                return cours.nbGroupe * cours.nbHeure;
            };

            $scope.computeHeuresLibre = function(cours) {
                let total = $scope.computeHeuresTotal(cours);

                let used = 0;

                angular.forEach(cours.voeux, function(voeu, key) {
                    used += voeu.nbHeures;
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
                    used += voeu.nbHeures;
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

                if(angular.equals($percentUtiliser, 100))
                    return 'label-success';

                return 'label-info';
            };
        }
    }
};
},{}],15:[function(require,module,exports){
/**
 * Created by Antoine on 23/03/2017.
 */
module.exports = function($log, rest, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/user_link.tpl.html',
        scope: {
            user: '='
        },
        link: function preLink(scope) {
            scope.popoverTemplate = config.base_uri + '/js/tpl/popover/user.tpl.html';
            if(!angular.isObject(scope.user)) {
                rest.get('get_utilisateur', { id: scope.user }).then(function(success) {
                    scope.utilisateur = success.data;
                })
            } else {
                scope.utilisateur = scope.user;
            }

        },
    }
};
},{}],16:[function(require,module,exports){
/**
 * Created by tanna on 25/03/2017.
 */
module.exports = function($q, $log, errorManager, $uibModal, config) {
    return {

        /**
         * @param modalParameters {object}
         * @returns {*|Window}
         */
        modalInstance: function(modalParameters) {
            return $uibModal.open(modalParameters);
        },

        /**
         * Open a modal who can manage a persistentObject.
         * Return a promise who is resolved when the persistentObject has to be saved and rejected if not.
         * Resolve and Rejected options: ['saveAll', 'save', 'return']
         * @param persistentObject
         * @param extendedModalOptions
         * @returns {promise|jQuery.promise}
         */
        persistentObjectModal: function(persistentObject, extendedModalOptions) {
            let deferred = $q.defer();
            let modalParameters = {
                bindToController: true,
                scope: persistentObject.scope,
                templateUrl: config.base_uri + '/js/tpl/modal/modal_wrapper.tpl.html',
                controllerAs: 'modalCtrl',
                controller: ['$scope', '$log', 'config', function($scope, $log, config) {

                    if(angular.isUndefined($scope.errm)) {
                        $log.warn('[factories:modals] errm is not defined on binded scope');
                        $scope.errm = errorManager;
                    }

                    $scope.persistentObjectUrl = persistentObject.templateUrl;
                    $scope.persistError = persistentObject.error;
                        $log.debug("modalCtrl ?", this, $scope);

                }],
            };

            $uibModal.open(angular.extend(modalParameters, extendedModalOptions)).result.then(undefined, function(reason) {
                if(angular.equals(reason, 'return'))
                    deferred.reject(reason);
                deferred.resolve(reason);
            });


            return deferred.promise;
        }
    };
};
},{}],17:[function(require,module,exports){
module.exports = function() {

    this.config = {
        initializationCompleted: false,
        debugMode: true,
        debugRouter: false,
        debugRest: false,
        debugPersistedQueue: false,
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
},{}],18:[function(require,module,exports){
/**
 * Created by tanna on 26/03/2017.
 */
module.exports = function($log, $parse) {
    this.isFormError = function(error) {
        if(angular.isUndefined(error))
            return false;
        return angular.isDefined(error.form) && angular.isDefined(error.errors)
    };

    this.isRequestError = function(error) {
        if(angular.isUndefined(error))
            return false;

        return angular.isDefined(error.error) && angular.isDefined(error.error.code);
    };


    this.isFormFlatten = function(error) {
        if(!this.isFormError(error))
            return false;
        angular.forEach(error.errors, function(key, value) {
            if(angular.isObject(value))
                return false;
        });
        return true;
    };

    this.getAllFormErrors = function(error) {
        if(!this.isFormFlatten(error))
            return false;

        return error.errors;
    };


    /**
     * Retrieve form errors that are not related with any of the form child.
     * @param error the whole error
     * @returns {*}
     */
    this.getFormRootErrors = function(error) {
        if(!this.isFormError(error))
            return [];

        return error.form.errors;
    };

    /**
     * Retrieve form errors that are related with the form name child.
     * @param name
     * @param error the whole error
     * @returns {*}
     */
    this.getInputErrors = function(name, error) {
        if(!this.isFormError(error))
            return [];

        let getter = $parse(name);
        let context = error.form.children;

        let input = getter(context);

        if(angular.isUndefined(input)) {
            $log.error("Cannot retrieve value:", name, " on object: ", context);
        }
        return angular.isDefined(input.errors) ? input.errors : [];
    }
};
},{}],19:[function(require,module,exports){
/**
 * Created by Antoine on 16/03/2017.
 * This service is used to managed update to database
 */
module.exports = function($q, $log, rest, config) {

    /**
     * History Queue
     * @type {Array}
     */
    this.persistedQueue = [];

    /**
     * Push persistedQueue to queue
     */
    this.push = function(object) {
        $log.debug(object);
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
        return !angular.equals(this.persistedQueue.indexOf(object), -1);
    };

    /**
     * Get head of the queue
     * @returns undefined|PersistentObject
     */
    this.first = function() {
        if(!this.hasNext())
            return undefined;

        for(let i = 0 ; i < this.persistedQueue.length ; i++) {
            if(!angular.equals(this.persistedQueue[i].state, config.persistentStates.ON_PERSIST))
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
     * Persist only one object
     * @param persistentObject
     * @returns {promise|undefined}
     */
    this.persistOne = function(persistentObject) {
        let deferred = $q.defer();
        let self = this;

        if(!this.contains(persistentObject)) {
            $log.error("[Service:persistedQueue] Trying to persist an invalid persistentObject");
            return deferred.promise;
        }

        if(persistentObject.state === config.persistentStates.PERSISTED) {
            $log.error("[Service:persistedQueue] Trying to persist an already persisted persistentObject");
            return deferred.promise;
        }


        persistentObject.persist(rest).then(function(success) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.debug("[Service:persistedQueue] Success Persist");

            self.remove(persistentObject);
            deferred.resolve();
        }, function(error) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.error("[Service:persistedQueue] Error Persist");
            deferred.reject();
        });

        return deferred.promise;
    };

    /**
     * Persist all PersistentObjects from persistedQueueQueue
     * @return {promise}
     */
    this.persist = function() {
        let deferred = $q.defer();
        let self = this;

        if(!this.hasNext()) {
            $log.error("[Service:persistedQueue] No persist to do");
            return deferred.promise;
        }

        let po = this.first();

        if(po.state === config.persistentStates.PERSISTED) {
            $log.error("[Service:persistedQueue] Trying to persist an already persisted persistentObject");
            return deferred.promise
        }



        po.persist(rest).then(function(success) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.debug("[Service:persistedQueue] Success Persist");

            self.remove(po);

            if(self.hasNext()) {
                self.persist().then(function() {
                    deferred.resolve();
                },function() {
                    deferred.reject();
                })
            }
            else {
                deferred.resolve();
            }
        }, function(error) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.error("[Service:persistedQueue] Error Persist");

            deferred.reject();
        });

        return deferred.promise;
    }
};
},{}],20:[function(require,module,exports){
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
},{}],21:[function(require,module,exports){
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
},{}],22:[function(require,module,exports){
/**
 * Created by Antoine on 27/03/2017.
 */
module.exports = function($log, $parse, $sce) {

    /**
     * Read an http content error and return true if it's a symfony form errors
     * @param error
     * @returns {*}
     */
    this.isFormError = function(error) {
        if(angular.isUndefined(error))
            return false;
        return angular.isDefined(error.form) && angular.isDefined(error.errors)
    };

    /**
     * Read an http content error and return true if it's some symfony exception
     * @param error
     * @returns {*}
     */
    this.isRequestError = function(error) {
        if(angular.isUndefined(error))
            return false;

        return angular.isDefined(error.error) && angular.isDefined(error.error.code);
    };

    /**
     *
     * Return Root Form errors
     * @returns {Array}
     */
    this.getFormError = function(error) {
        if(!this.isFormError(error))
            return [];
        return error.form.errors;
    };

    /**
     *
     * Return Specific Input Form errors
     * @returns {Array}
     */
    this.getInputError = function(name, error) {
        if(!this.hasInputError(name, error))
            return [];

        let getter = $parse(name);
        let context = error.form.children;

        let input = getter(context);

        return input.errors;
    };


    this.hasInputError = function(name, error) {

        if(!this.isFormError(error))
            return false;

        let getter = $parse(name);
        let context = error.form.children;

        let input = getter(context);

        return !angular.isUndefined(input.errors);

    };

        /**
     *
     * @param name
     * @param error
     * @returns {*}
     */
    this.getHtmlInputError = function(name, error) {
        if(!this.hasInputError(name, error))
            return "";

        let errors = this.getInputError(name, error);

        let getter = $parse(name + ".htmlErrors");
        let setter = getter.assign;
        let context = error.form.children;

        if(angular.isUndefined(getter(context))) {
            let htmlErrors = "";
            angular.forEach(errors, function(error) {
                htmlErrors += error + '<br/>';
            });
            setter(context, $sce.trustAsHtml(htmlErrors));
        }

        return getter(context);
    };

};
},{}]},{},[1]);
