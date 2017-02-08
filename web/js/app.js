/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    service('rest', ["$http", "$log", require('./services/rest')]).
    directive('typeahead', ['$log', 'rest', require('./directives/typeahead')]).
    config(["$logProvider", "$interpolateProvider",require("./appConfig")]);