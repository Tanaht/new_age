/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', ['ngCookies', 'ui.bootstrap']).provider('config', [require('./providers/config')]).
    factory('modals', ['$log', '$uibModal', require('./factories/modals')]).
    controller('profilController', ['$scope', '$log', 'config', require('./controllers/profil')]).
    controller('profilsController', ['$scope', '$log', 'config', require('./controllers/profils')]).
    controller('enseignementsController', ['$scope', '$log', 'config', require('./controllers/enseignements')]).
    controller('saisieVoeuxController', ['$scope', '$log', '$cookies', 'rest', 'config', require('./controllers/saisieVoeux')]).
    service('rest', ["$q", "$http", "router", "$log", 'config', require('./services/rest')]).
    service('errorManager', ["$log", "$parse", require('./services/errorManager')]).
    service('persistedQueue', ["$q", "$log", "rest", "config", require('./services/persistedQueue')]).
    service('router', ['$log', 'config', require('./services/router')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', 'config',  require('./directives/typeahead')]).
    directive('etapeView', ['$log', 'config', require('./directives/etapeView')]).
    directive('ueView', ['$log', 'config', require('./directives/ueView')]).
    directive('voeuForm', ['$log', '$sce', '$filter', 'errorManager', 'persistedQueue', 'config', require('./directives/form/voeu')]).
    directive('persistedStateView', ['$log', "modals", 'persistedQueue', 'config', require('./directives/persistedStateView')]).
    directive('userLink', ['$log', 'rest', 'config', require('./directives/userLink')]).
    directive('errorModalContentWrapper', ['$log', '$templateRequest', '$compile', "errorManager", 'config', require('./directives/errorModalContentWrapper')]).
    config(["$provide", "$logProvider", "$qProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$templateCache", "$location", "$cookies", "$log", "rest", "config", require('./clientSide')])
;