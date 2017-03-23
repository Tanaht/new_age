/**
 * Created by Antoine on 08/02/2017.
 */
module.exports= function($provide, $logProvider, $interpolateProvider, configProvider) {
    ngLogger = angular.injector(['ng']).get('$log');

    $logProvider.debugEnabled(configProvider.config.debugMode);
    $interpolateProvider.startSymbol('[$');
    $interpolateProvider.endSymbol('$]');

    /*
     * Decorator: here we decorate UI-Bootstrap directives to fit the application needs
    */
    $provide.decorator('uibAccordionDirective', function($delegate) {

        ngLogger.debug($delegate);
        let directive = $delegate[0];

        angular.extend(directive, {
            replace: true,
        });

        return $delegate;
    });

    $provide.decorator('uibAccordionGroupDirective', function($delegate) {

        ngLogger.debug($delegate);
        let directive = $delegate[0];

        angular.extend(directive, {
            replace: true,
        });

        return $delegate;
    });
};