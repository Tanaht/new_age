/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    provider('config', [require('./providers/config')]).
    /* TODO:typeahead little sample on how to declare angular controller to catch typeahead events */
    controller('profilController', ['$scope', '$log', require('./controllers/profil')]).
    controller('profilsController', ['$scope', '$log', require('./controllers/profils')]).
    controller('enseignementsController', ['$scope', '$log', require('./controllers/enseignements')]).
    service('rest', ["$http", "$location", "$log", require('./services/rest')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', 'configProvider', ['$log', 'rest', require('./directives/typeahead')]).
    config(["$logProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$log", "config", require('./clientSide')])
;