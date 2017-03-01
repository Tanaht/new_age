/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($logProvider, $interpolateProvider, configProvider) {
    $logProvider.debugEnabled(configProvider.config.debugMode);
    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');
};