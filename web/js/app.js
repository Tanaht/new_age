/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    provider('config', [require('./providers/config')]).
    /* TODO:typeahead little sample on how to declare angular controller to catch typeahead events */
    controller('profilController', ['$scope', '$log', require('./controllers/profil')]).
    service('rest', ["$http", "$location", "$log", require('./services/rest')]).
    directive('fileUpload', ['$log', require('./directives/fileUpload')]).
    directive('prototype', ['$log', require('./directives/prototype')]).
    directive('typeahead', ['$log', 'rest', require('./directives/typeahead')]).
    config(["$logProvider", "$interpolateProvider", "configProvider", require("./appConfig")]).
    run(["$rootScope", "$log", "config", require('./clientSide')])
;