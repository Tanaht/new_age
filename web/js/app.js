/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    provider('config', [require('./providers/config')]).
    controller('profilController', ['$scope', '$log', 'config', require('./controllers/profil')]).
    controller('profilsController', ['$scope', '$log', 'config', require('./controllers/profils')]).
    controller('enseignementsController', ['$scope', '$log', 'config', require('./controllers/enseignements')]).
    controller('saisieVoeuxController', ['$scope', '$log', 'rest', 'config', 'router', require('./controllers/saisieVoeux')]).
    service('rest', ["$http", "router", "$log", 'config', require('./services/rest')]).
    service('history', ["$log", "rest", "config", require('./services/history')]).
    service('router', ['$log', 'config', require('./services/router')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', 'config',  require('./directives/typeahead')]).
    directive('voeu', ['$log', 'rest', 'config', require('./directives/voeu')]).
    config(["$logProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$log", "config", require('./clientSide')])
;