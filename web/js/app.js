/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    controller('profilUpdate', ['$scope', '$log', 'rest', require('./controllers/profil/update')]).
    service('rest', ["$http", "$location", "$log", require('./services/rest')]).
    directive('typeahead', ['$log', 'rest', require('./directives/typeahead')]).
    config(["$logProvider", "$interpolateProvider",require("./appConfig")]);