/**
 * Created by Antoine on 08/02/2017.
 */
angular.module('clientSide', []).
    controller('profil_update_controller', ['$log', 'rest', require('./controllers/profil/update_controller')]).
    service('rest', ["$http", "$log", require('./services/rest')]).
    directive('typeahead', ['$log', 'rest', require('./directives/typeahead')]).
    config(["$logProvider", "$interpolateProvider",require("./appConfig")]);