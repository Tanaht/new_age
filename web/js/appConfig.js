/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($logProvider, $interpolateProvider) {
    $logProvider.debugEnabled(true);
    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');
};