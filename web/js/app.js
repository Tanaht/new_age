/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', ['ngCookies']).
    provider('config', [require('./providers/config')]).
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
    config(["$logProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$log", "rest", "config", require('./clientSide')])
;